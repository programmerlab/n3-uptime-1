# Sacnner Code for N3 Uptime - "n3UptimeScanner" lamda
This document will explain about n3uptime scanner lamda function. It will explain about nessary node modules, expected input & possible outputs.

## Required node modules
We need below node packages to execute "n3UptimeScanner" function.
* request
* valid-url
* signalfx
* aws-sdk

Create a folder called "scanner" and put index.js file with the scanner code.

Execute below command to create required packages.
```sh
cd scanner
npm install request valid-url signalfx aws-sdk
```
Create a zip file which should contain index.js file & node_modules folder inside it.

## Upload Zipfile to Lamda function
Go to Lambda from AWS console & create a function (n3UptimeScanner).
Upload created zip by selecting "Code entry type" as "Upload a.ZIP file" from aws Lambda console.
Set "Timeout" as 5 seconds to execute the function within 5 seconds.

Also, Set below variables under "Environment variables" section.
1. signalFXToken = {PROPER_SIGNALFX_TOKEN}
2. script_mode = 'prod'

## Execute the script to verify
Go go Lambda function & select "n3UptimeScanner" from the function list.
To execute the script go to "Test" button & "Configure test events".
And click on "Test" button.

### Input Parameters
Under input box from "Configure test events" please add below json params.
```json
{
  "hostedUrl": "http://acquia.com",
  "responseCode": 200
}
```
hostedUrl is mandatory.

The response code is optional. Default value is 200.
The response code is required in case any URL expects other response code as success.
Eg: http://abc.com expecting 400 as successful response 
    http://xyz.com expecting 404 as successful response
 

### Expected success output example
```json
{
  "status": "success",
  "scanned_url": "https://acquia.com",
  "message": "Successful scan.",
  "response_header": {
    "date": "Tue, 05 Jun 2018 10:14:13 GMT",
    "content-type": "text/html; charset=UTF-8",
    "transfer-encoding": "chunked",
    "connection": "close",
    "cache-control": "max-age=2764800, public",
    "x-drupal-dynamic-cache": "UNCACHEABLE",
    "link": "<https://www.acquia.com>; rel='shortlink', <https://www.acquia.com>; rel='canonical', <https://www.acquia.com/node/1>; rel='alternate'; hreflang='en', </node/1>; rel='canonical', </node/1>; rel='shortlink', </node/1>; rel='revision'",
    "x-ua-compatible": "IE=edge",
    "content-language": "en",
    "x-content-type-options": "nosniff",
    "expires": "Sun, 19 Nov 1978 05:00:00 GMT",
    "last-modified": "Mon, 04 Jun 2018 13:03:39 GMT",
    "vary": "Cookie,Accept-Encoding",
    "x-generator": "Drupal 8 (https://www.drupal.org)",
    "content-security-policy": "frame-ancestors \"self\" http://acquia.lookbookhq.com https://acquia.lookbookhq.com; report-uri /report-csp-violation",
    "x-content-security-policy": "frame-ancestors \"self\" http://acquia.lookbookhq.com https://acquia.lookbookhq.com; report-uri /report-csp-violation",
    "x-webkit-csp": "frame-ancestors \"self\" http://acquia.lookbookhq.com https://acquia.lookbookhq.com; report-uri /report-csp-violation",
    "strict-transport-security": "max-age=31536000",
    "x-drupal-cache": "MISS",
    "x-request-id": "v-b3736e80-67f7-11e8-8556-0ad33a4b3f10",
    "x-ah-environment": "prod",
    "x-varnish": "3679921 4043337",
    "age": "76233",
    "via": "1.1 varnish-v4",
    "x-cache": "HIT",
    "x-cache-hits": "15560",
    "expect-ct": "max-age=604800, report-uri='https://report-uri.cloudflare.com/cdn-cgi/beacon/expect-ct'",
    "server": "cloudflare",
    "cf-ray": "4261deff9dc6243e-IAD"
  },
  "status_code": "200"
}
```

### Expected error output examples

```json
{ 
  "status": "error",
  "message": "Not a valid URL",
  "scanned_url": "acquia.com",
  "response_header": { "message": "Not a valid URL" },
  "status_code": "400"
}
```

### How to do unit testing
Node JS should be installed already to execute the test cases.

Download index.js, index.sepc.js & package.json files from n3-uptime/lambda_functions/scanner/ folder in to your local device.
Go to scanner folder through terminal & execute below command. This command will install necessary node packages to execute the test cases.

```sh
cd scanner
npm install
```

Set env variable for signalFX token in your local machine.
```sh
export signalFXToken="{PROPER_SIGNALFX_TOKEN}"
```

Run below command to execute the test case locally.

```sh
./node_modules/.bin/mocha index.spec.js
```
