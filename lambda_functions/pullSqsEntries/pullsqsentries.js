/*
 * Code for the getting a data from sqs queue and pass it to N3 uptime scanner code
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

/*
 *  Including required packages  
 */
let AWS = require('aws-sdk');
let lambda = new AWS.Lambda();

/*
 *  setting an aws region
 */
AWS.config.update({
 region: 'us-east-1',
 credentials: {
  "accessKeyId": "AKIAIFDN3F4HWGX2VCQA",
  "secretAccessKey": "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",
  "region": "us-east-1"
 }
});
const sqs = new AWS.SQS({
 apiVersion: '2012-11-05'
});

/*
 *  setting a queue url.
 */
const queueURL = "https://sqs.us-east-1.amazonaws.com/672327909798/N3UptimeQueue.fifo";

/*
 *  params for the sqs receiveMessage
 */
let params = {
 AttributeNames: [
  "SentTimestamp"
 ],
 MaxNumberOfMessages: 1,
 MessageAttributeNames: [
  "All"
 ],
 QueueUrl: queueURL,
 VisibilityTimeout: 30,
 WaitTimeSeconds: 0
};

/*
 *  lambda function for pulling entries from sqs. 
 */


exports.handler = (event, context, callback) => {
 /* 
  * code for the pulling message from sqs 
  */
 sqs.receiveMessage(params, function(err, data) {

    if (err) 
    {
        callback(err, "Error fetching a messages from sqs")
    } 
    else if (data.Messages) 
    {
      console.log(data.Messages[0].Body);

     /*
      * Params for deleting a message from queue after pull
      */
     var deleteParams = {
      QueueUrl: queueURL,
      ReceiptHandle: data.Messages[0].ReceiptHandle
     };


     /*
      * Deleting a message from the sqs queue
      */
     sqs.deleteMessage(deleteParams, function(err, data) {
      if (err) {
       console.log('Delete error', err);
      } else {
       console.log('Message deleted ', data);
      }
     });



     /*
      * setting a params for the lambda function invocation.
      */
     var payload_obj = JSON.stringify(data.Messages[0].Body);
     var params = {
      FunctionName: 'n3UptimeScanner',
      InvocationType: 'RequestResponse',
      LogType: 'Tail',
      Payload: '{ "hostedUrl" :' + payload_obj + '}'
     };

     /*
      * Invoking scanner function
      */
     lambda.invoke(params, function(err, data) {
      if (err) {
        context.fail(err);
      } 
      else if (data) 
      {
        callback(null, data);
      }
   });
   //code end here

  } 
  else 
  {
   console.log('No message received from queue');
  }

 });
};