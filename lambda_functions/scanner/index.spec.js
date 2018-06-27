/* 
 * This is an unit test script for N3 Uptime scanner code.
 * Using Moucha & Chai to test the code.
 *
*/
'use strict';

// Including required packages 
var expect = require( 'chai' ).expect;
var myLambda = require( './index' );

describe( 'Test cases for scanner lambda function.', function() {

    // Context parameter which holds responce from scanner scanner code
    var context = {
      succeed: function( result ) {
        expect( result.status_code ).to.be.true;
        done();
      },
      fail: function() {
        done( new Error( 'never context.fail' ) );
      }
    };

    // Test case1. Sending proper URL to scanner code without status code. Status code is not mandatory parameter
    var url = 'https://accounts.acquia.com';
    it( `Successful scan without status code. URL = ${url}`, function( done ) {
      this.timeout(10000);
      myLambda.handler( { hostedUrl: url}, { /* context */ }, (err, result) => {
        try {
          expect( err ).to.not.exist;
          expect( result ).to.exist;
          expect( result.status ).to.be.eql('success');
          expect( result.status_code ).to.be.eql(200);
          done();
        }
        catch( error ) {
          done( error );
        }
      });
    });

    // Test case2. Sending proper URL & status code. This is a successful scan
    var url5 = 'https://accounts.acquia.com';
    var status_code5 = 200;
    it( `Successful scan with status code. URL = ${url}`, function( done ) {
      this.timeout(10000);
      myLambda.handler( { hostedUrl: url5, responseCode: status_code5 }, { /* context */ }, (err, result) => {
        try {
          expect( err ).to.not.exist;
          expect( result ).to.exist;
          expect( result.status ).to.be.eql('success');
          expect( result.status_code ).to.be.eql(200);
          done();
        }
        catch( error ) {
          done( error );
        }
      });
    });

    // Test case3. Scan with proper URL & wrong status code. This site returns 200 as a proper status code
    var url1 = 'http://acquia.com';
    var status_code1 = '400';
    it( `Proper URL with wrong status code. URL = ${url1}`, function( done ) {
      this.timeout(10000);
      myLambda.handler( { hostedUrl: url1, responseCode: status_code1 }, { /* context */ }, (err, result) => {
        try {
          expect( err ).to.not.exist;
          expect( result ).to.exist;
          expect( result.status ).to.be.eql('error');
          expect( result.status_code ).to.be.eql(200);
          expect( result.message ).to.contain('Status code error'); 
          done();
        }
        catch( error ) {
          done( error );
        }
      });
    });

    // Test case4. Scan with the URL application is not hosted under Acquia
    var url4 = 'https://google.com';
    var status_code4 = '200';
    it( `Proper URL. But not hosted on acquia. URL = ${url4}`, function( done ) {
      this.timeout(10000);
      myLambda.handler( { hostedUrl: url4, responseCode: status_code4 }, { /* context */ }, (err, result) => {
        try {
          expect( err ).to.not.exist;
          expect( result ).to.exist;
          expect( result.status_code ).to.be.eql(200);
          expect( result.message ).to.contain('not hosted');
          expect( result.status ).to.be.eql('error');
          done();
        }
        catch( error ) {
          done( error );
        }
      });
    });

    // Test case5. Scan with invalid URL
    var url2 = 'google.com';
    var status_code2 = '200';
    it( `Invalid URL = ${url2}`, function( done ) {
      this.timeout(10000);
      myLambda.handler( { hostedUrl: url2, responseCode: status_code2 }, { /* context */ }, (err, result) => {
        try {
          expect( err ).to.not.exist;
          expect( result ).to.exist;
          expect( result.status_code ).to.be.eql(400);
          expect( result.message ).to.contain('Not a valid URL');
          done();
        }
        catch( error ) {
          done( error );
        }
      });
    });
    
    // Test case6. Calling scanner code without parameters
    it( `Call API without parameters.`, function( done ) {
      this.timeout(10000);
      myLambda.handler( { }, { /* context */ }, (err, result) => {
        try {
          expect( err ).to.not.exist;
          expect( result ).to.exist;
          expect( result.status ).to.be.eql('error');                  
          done();
        }
        catch( error ) {              
          done( error );
        }
      });
    });

    // Test case7. Validating timeout error by reducing the execution time of scanner code
    it( `Validation for timout. Reducing timeout parameter for testing`, function( done ) {
        myLambda.handler( { hostedUrl: url4, responseCode: status_code4, timeoutSeconds:0.001 }, { /* context */ }, (err, result) => {
          try {
            expect( err ).to.not.exist;
            expect( result ).to.exist;
            expect( result.status ).to.be.eql('error');
            expect( result.status_code ).to.be.eql(408);
            expect( result.response_header.cause.code ).to.contain('TIMEDOUT');           
            done();
          }
          catch( error ) {              
            done( error );
          }
        });
      });

    // Test case8. Scan with unknow URL
    var url8 = 'https://abcdedghijk123.com';
    var status_code8 = '200';
    it( `Proper URL. But not hosted on acquia. URL = ${url8}`, function( done ) {
      this.timeout(10000);
      myLambda.handler( { hostedUrl: url8, responseCode: status_code8 }, { /* context */ }, (err, result) => {
        try {
          expect( err ).to.not.exist;
          expect( result ).to.exist;
          expect( result.status_code ).to.be.eql(404);
          expect( result.message ).to.contain('not found');
          expect( result.status ).to.be.eql('error');
          done();
        }
        catch( error ) {
          done( error );
        }
      });
    });
});
