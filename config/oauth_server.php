<?php defined('SYSPATH') or die('No direct script access.');

return array(

    'default' => array(

        'formats' => array(
            'json'      => TRUE,
            'xml'       => TRUE,
            'form'      => FALSE
        ),

        'types'    => array(
            'request'       => 'web_server',
            'deny'          => 'user_denied',
            'access'        => 'username',
            'credential'    => 'client_credentials'
        ),

        'token'    => array(
            'access_token'  => '',
            'expires_in'    => '',
            'refresh_token' => '',
            'scope'         => '',
        ),

        'request_methods'   => array(
            'HEAD'      => TRUE,
            'GET'       => TRUE,
            'POST'      => TRUE,
            'PUT'       => TRUE,
            'DELETE'    => TRUE
        ),

        /**
         * Parameters should be required when request authorization code
         * cryptographic token or bear token
         */
        'code_params'     => array(
            'client_id'     => TRUE,
            'redirect_uri'  => TRUE,
            'scope'         => FALSE,
            'state'         => FALSE
        ),

        /**
         * Parameters should be required when request access token
         */
        'grant_params'     => array(
            'authorization-code'    => array(
                'code'              => TRUE,
                'redirect_uri'      => TRUE,
                'client_id'         => TRUE,
                'client_secret'     => TRUE,
            ),
            'basic-credentials'     => array(
                'username'          => TRUE,
                'password'          => TRUE,
                'client_id'         => TRUE,
                'client_secret'     => TRUE,
            ),
            'assertion'             => array(
                'assertion_type'    => TRUE,
                'assertion'         => TRUE,
                'client_id'         => TRUE,
                'client_secret'     => TRUE,

            ),
            'refresh-token'         => array(
                'refresh_token'     => TRUE,
                'client_id'         => TRUE,
                'client_secret'     => TRUE,
            ),
            'none'                  => array(
                'client_id'         => TRUE,
                'client_secret'     => TRUE,
            ),
            'signature'             => FALSE
        ),

        /**
         * Parameters should be required when access protected resource
         * cryptographic token or bear token
         */
        'access_params'     => array(
            'oauth_token'   => TRUE,
            'timestamp'     => TRUE,
            'scope'         => FALSE,
            'nonce'         => FALSE,
            'algorithm'     => FALSE,
            'signature'     => FALSE
        ),

        'secret_types'  => array(
            'plaintext' => TRUE,
            'rsa-sha1'  => TRUE,
            'hmac-sha1' => TRUE,
            'md5'       => FALSE
        ),

        'scopes'    => array(
            //
        ),

        'max_requests'  => array(
            500,        // common client
            1000,       // first class client
            1500,       // vip client
        ),

        'durations'     => array(
            'code'          => 120,     // authorization code expires time, default is 2 minutes
            'oauth_token'   => 3600,    // token expires time, default is 1 hour
            'refresh_token' => 86400    // refresh token expires time, default is 1 day
        ),

        #section-3.2.1 Error Codes
        'req_code_errors'   => array(
            'invalid-request'       => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'invalid-client-id'     => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'unauthorized-client'   => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'redirect-uri-mismatch' => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'access-denied'         => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'unsupported-response-type' => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'invalid-scope'         => array(
                'error_description' => '',
                'error_uri'         => '',
            )
        ),
        #section-4.3.1 Error Codes
        'req_token_errors'  => array(
            'invalid-request'       => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'invalid-client-credentials' => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'unauthorized-client'   => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'invalid-grant'         => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'unsupported-grant-type'=> array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'invalid-scope'         => array(
                'error_description' => '',
                'error_uri'         => '',
            )
        ),
        #section-5.2.1 Error Codes
        'access_res_errors' => array(
            'invalid-request'       => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'invalid-token'         => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'expired-token'         => array(
                'error_description' => '',
                'error_uri'         => '',
            ),
            'insufficient-scope'    => array(
                'error_description' => '',
                'error_uri'         => '',
            )
        )
    )
);