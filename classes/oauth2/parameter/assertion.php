<?php
/**
 * Oauth parameter handler for assertion flow
 *
 * @author      sumh <oalite@gmail.com>
 * @package     Oauth
 * @copyright   (c) 2010 OALite
 * @license     ISC License (ISCL)
 * @link        http://www.oalite.cn
 * @see         Oauth_Parameter
 * *
 */
class Oauth2_Parameter_Assertion extends Oauth2_Parameter {

    /**
     * assertion_type
     *      REQUIRED.  The format of the assertion as defined by the
     *      authorization server.  The value MUST be an absolute URI.
     */
    public $assertion_type;

    /**
     * assertion
     *      REQUIRED.  The assertion.
     */
    public $assertion;

    /**
     * client_id
     *      OPTIONAL.  The client identifier as described in Section 2.1.
     *      The authorization server MAY require including the client
     *      credentials with the request based on the assertion properties.
     * client_secret
     *      OPTIONAL.  The client secret as described in Section 2.1.  MUST
     *      NOT be included if the "client_id" parameter is omitted.
     * scope
     *      OPTIONAL.  The scope of the access request expressed as a list
     *      of space-delimited strings.  The value of the "scope" parameter
     *      is defined by the authorization server.  If the value contains
     *      multiple space-delimited strings, their order does not matter,
     *      and each string adds an additional access range to the
     *      requested scope.
     * format
     *      OPTIONAL.  The response format requested by the client.  Value
     *      MUST be one of "json", "xml", or "form".  Alternatively, the
     *      client MAY use the HTTP "Accept" header field with the desired
     *      media type.  Defaults to "json" if omitted and no "Accept"
     *      header field is present.
     */

    public function __construct($args = NULL)
    {
        $params = Oauth2::parse_query();
        $this->assertion_type = Arr::get($params, 'username');
        $this->assertion = Arr::get($params, 'password');

        if(NULL !== $client_id = Arr::get($params, 'client_id'))
            $this->client_id = $client_id;

        if(NULL !== $client_secret = Arr::get($params, 'client_secret'))
            $this->client_secret = $client_secret;

        if(NULL !== $scope = Arr::get($params, 'scope'))
            $this->scope = $scope;

        if(NULL !== $format = Arr::get($params, 'format'))
            $this->format = $format;
    }

    public function oauth_token($client)
    {
        $response = new Oauth2_Token;

        if($client['assertion_type'] !== $this->assertion_type)
        {
            $response->error = 'unknown-format';
            return $response;
        }
        else
        {
            $response->assertion_type = $this->assertion_type;
        }

        if($client['assertion'] !== $this->assertion
            OR (property_exists($this, 'client_id') AND $client['client_id'] !== $this->client_id)
            OR (property_exists($this, 'client_secret') AND $client['client_secret'] !== sha1($this->client_secret))
            OR (property_exists($this, 'scope') AND ! isset($client['scope'][$this->scope]))
        {
            $response->error = 'invalid_request';
            return $response;
        }

        // Grants Authorization
        // The authorization server SHOULD NOT issue a refresh token.
        $response->expires_in = 3000;
        $response->access_token = $client['access_token'];

        return $response;
    }

} // END Oauth_Parameter_Assertion
