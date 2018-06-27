/* This is an N3 uptime scanner code which reads the Acquia hosted domain URL
 *
 * @param json  { 
 *     hostedUrl string: This parameter is to send a URL hosted under Acquia
 *     responseCode int: This parameter is to send the expected response code for successful return
 * }
 * Example input params:
 * {
 *  "hostedUrl": "https://accounts.acquia.com/sign-in",
 *  "responseCode": 200 // optional
 * }
 * 
 * @response json {
 *     status string: This will contain scanned status
 *     message string: This will contain error or success message statement
 *     response_header json: This parameter holds HTTP header response in JSON format
 *     status_code int: This parameter hold HTTP status code
 * }
 * Example output response:
 * {
 *  status:'error/success',
 *  message: "[ERROR_MESSAGE]/[SUCCESS_MESSAGE]",
 *  response_header: response.headers,
 *  status_code: [HTTP_STATUS_CODE]
 * }
*/
'use strict';
// Including required packages 
const request = require('request-promise');
const validUrl = require('valid-url');
const signalfx = require('signalfx');

// Timeout variable for each scan
let timeoutSeconds;
let responseCode;
let hostedUrl;

exports.handler = async function(event, context, callback) {
    // Response message & header to capture in JSON format
    let statusResponse = {};
    // get hosted URL & response code from Lamda inputs 
    hostedUrl = event.hostedUrl;
    // default value for response code is 200
    responseCode = (typeof event.responseCode !== 'undefined')?event.responseCode:200;

    timeoutSeconds = (typeof event.timeoutSeconds !== 'undefined')?event.timeoutSeconds:5;
    
    // script environment to send log to cloud watch only under lambda production
    let script_env =  (typeof process.env.script_mode !== 'undefined')?'prod':'test';

    // validate the URL
    if (!validUrl.isUri(hostedUrl))
    {
        statusResponse = {
            status:'error',
            message: "Not a valid URL",
            scanned_url: hostedUrl,
            response_header: {message:"Not a valid URL"},
            status_code: 400
        };

        sendDatatoSignalFX(statusResponse);
        if(script_env != 'test')
        {
            console.log(statusResponse);
            context.done(null, statusResponse);
        }
        else
        {
            callback(null, statusResponse);
        }
    }
    else 
    {
        // Header for request method
        let headers = {
            'User-Agent':       'Uptime/v2 (https://www.acquia.com)',
            'Content-Type':     'application/x-www-form-urlencoded',
            'Cache-Control': 'private, no-cache="set-cookie"'
        }

        // Configure the request
        let options = {
            url: hostedUrl,
            method: 'GET',
            headers: headers,
            maxAttempts: 10,
            timeout: timeoutSeconds * 1000,
            resolveWithFullResponse: true
        }

        try {
            const response = await request(options);
            // in case of success response
            statusResponse = processResponse(response);
        } catch (error) {
            // in case of any error response
            statusResponse = processError(error);
        }

        // send response to signalfx 
        sendDatatoSignalFX(statusResponse);

        if(script_env != 'test')
        {
            // print the response which will send data to cloud watch
            console.log(statusResponse);                
            // print response & exit the execution
            context.done(null, statusResponse);
        }
        else
        {
            // send back response to test script
            callback(null, statusResponse);
        }
    }
   
};

/**  
 * Process Error
 * This method will get the error code & return proper error message in json format
 * @param
 *      error json: Error message from scanned URL
 * @return  
 *      statusResponse json: Error messages & headers in JSON format
 */
function processError(error)
{
    // Response message in json format
    let statusResponse = {};
    let message;
    let status_code;
    switch (error.cause.code)
    {
        // timeout error
        case 'ETIMEDOUT':
        case 'ESOCKETTIMEDOUT':
        {
            message = 'The site scan is exceeding '+timeoutSeconds+' seconds.';
            status_code = 408;
            break;
        }
        // page not found error
        case 'ENOTFOUND':
        {
            message = 'The site URL is not found.';
            status_code = 404;
            break;
        }
        // any outher error returns error message with error code
        default:
        {
            message = 'Something went wrong.';
            status_code = 520;
            break;
        }
    }
    statusResponse = {
        status:'error',
        scanned_url: hostedUrl,
        message: message,
        response_header: error,
        status_code: status_code
    };
    return statusResponse;
}

/**  
 * Process Responce
 * This method will get the response code & return proper message in json format
 * @param
 *      response json: Response message from scanned URL
 * @return  
 *      statusResponse json: Status messages & headers in JSON format
 */
function processResponse(response)
{
    let statusResponse = {};
    // check if the site is hosted under acquia or not
    let hosted  = typeof response['headers']['x-ah-environment'] === 'undefined';
    let status  = 'error';
    let message =  hosted? 'The site being scanned is not hosted on Acquia.' : 'Status code error.';

    // in case of successful scan
    if (!hosted && response.statusCode == responseCode) {
        status  = 'success';
        message = 'Successful scan.';
    }

    statusResponse = {
        status: status,
        scanned_url: hostedUrl,
        message: message,
        response_header: response.headers,
        status_code: response.statusCode
    };
    return statusResponse;
}


/**
 * Send raw data to signalFX
 * This method will send the scanned result to signalFX  
 * 
 * @input {json} Scanned response header from domain URL
 *
 */
function sendDatatoSignalFX(jsondata)
{
    if(typeof process.env.signalFXToken === 'undefined')
    {
        return false;
    }
    // Create default client
    let client = new signalfx.Ingest(process.env.signalFXToken);
    let myJSON = JSON.stringify(jsondata);
    const gauge_metric = [
        {
            metric: 'n3uptime.scanner',
            value: jsondata.status_code,
            timestamp: Date.now(),
            dimensions: {
                'domain': jsondata.scanned_url,
                'status_code': String(jsondata.status_code),
                'scanned_status': jsondata.status 
            },
            properties: {
                'scanned_result': myJSON
            }
        }
    ];

    try {
        client.send({gauges: gauge_metric});
    }
    catch(err) {
       console.log(err);
    }
}
