<?php
/**
 * Oauth parameter handler for access token flow
 *
 * @author      sumh <oalite@gmail.com>
 * @package     Oauth
 * @copyright   (c) 2010 OALite
 * @license     ISC License (ISCL)
 * @link        http://www.oalite.cn
 * @see         Oauth_Parameter
 * *
 */
class Oauth2_Parameter_Access extends Oauth2_Parameter {

    /**
     * REQUIRED
     *
     * @access	public
     * @var		string	$oauth_token
     */
    public $oauth_token;

    /**
     * Parameters parsed from Authorization header, URI-Query parameters or Form-Encoded Body
     *
     * @access	protected
     * @var		string	$_params
     */
    protected $_params;

    /**
     * Load request parameters from Authorization header, URI-Query parameters or Form-Encoded Body
     *
     * @access	public
     * @param	string	$args	parameters are required, `array('oauth_token' => TRUE,)`
     * @return	void
     */
    public function __construct(array $args)
    {
        $params = array();
        /**
         * Load oauth token from authorization header
         */
        isset($_SERVER['HTTP_AUTHORIZATION']) OR $_SERVER['HTTP_AUTHORIZATION'] = getenv('HTTP_AUTHORIZATION');

        if (substr($_SERVER['HTTP_AUTHORIZATION'], 0, 12) === 'OAuth token=')
        {
            $offset = 0;
            $pattern = '/(([-_a-z]*)=("([^"]*)"|([^,]*)),?)/';
            while(preg_match($pattern, $_SERVER['HTTP_AUTHORIZATION'], $matches, PREG_OFFSET_CAPTURE, $offset) > 0)
            {
                $match  = $matches[0];
                $name   = $matches[2][0];
                $offset = $match[1] + strlen($match[0]);
                if($value = Oauth2::urldecode(isset($matches[5]) ? $matches[5][0] : $matches[4][0]))
                {
                    $params[$name]  = $value;
                }
            }
        }

        // Replace the name of token to oauth_token
        if(isset($params['token']))
        {
            $params['oauth_token'] = $params['token'];
            unset($params['token']);

            // Check all required parameters should NOT be empty
            foreach($args as $key => $val)
            {
                if($val === TRUE)
                {
                    if( ! empty($params[$key]))
                    {
                        throw new Oauth2_Exception_Access('invalid_request');
                    }
                }
            }
        }

        /**
         * Load oauth_token from form-encoded body
         */
        if(isset($_POST['oauth_token']))
        {
            isset($_SERVER['CONTENT_TYPE']) OR $_SERVER['CONTENT_TYPE'] = getenv('CONTENT_TYPE');

            // oauth_token already send in authorization header or the encrypt Content-Type is not single-part
            if(isset($params['oauth_token']) OR stripos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') === FALSE)
            {
                throw new Oauth2_Exception_Access('invalid_request');
            }
            else
            {
                // Check all required parameters should NOT be empty
                foreach($args as $key => $val)
                {
                    if($val === TRUE)
                    {
                        if(isset($_POST[$key]) AND $value = Oauth2::urldecode($_POST[$key]))
                        {
                            $params[$key] = $value;
                        }
                        else
                        {
                            throw new Oauth2_Exception_Access('invalid_request');
                        }
                    }
                }
            }
        }

        /**
         * Load oauth_token from uri-query component
         */
        if(isset($_GET['oauth_token']))
        {
            // oauth_token already send in authorization header or form-encoded body
            if(isset($params['oauth_token']))
            {
                throw new Oauth2_Exception_Access('invalid_request');
            }
            else
            {
                // Check all required parameters should NOT be empty
                foreach($args as $key => $val)
                {
                    if($val === TRUE)
                    {
                        if(isset($_GET[$key]) AND $value = Oauth2::urldecode($_GET[$key]))
                        {
                            $params[$key] = $value;
                        }
                        else
                        {
                            throw new Oauth2_Exception_Access('invalid_request');
                        }
                    }
                }
            }
        }

        if(empty($params))
        {
            throw new Oauth2_Exception_Access('invalid_request');
        }

        $this->oauth_token = $params['oauth_token'];

        unset($params['oauth_token']);

        $this->_params = $params;
    }

    /**
     * MUST verify that the verification code, client identity, client secret,
     * and redirection URI are all valid and match its stored association.
     *
     * @access  public
     * @return  Oauth_Token
     * @todo    impletement timestamp, nonce, signature checking
     */
    public function access_token($client)
    {
        $response = new Oauth2_Token;

        if(isset($this->_params['format']))
        {
            $response->format = $this->_params['format'];
        }

        //if(isset($this->_params['nonce']) AND $client['nonce'] !== $this->_params['nonce'])
        //{
        //    throw new Oauth_Exception_Access('invalid_request');
        //}

        if($client['access_token'] !== $this->oauth_token)
        {
            throw new Oauth2_Exception_Access('invalid_token');
        }

        if(isset($this->_params['scope']) AND ! empty($client['scope']))
        {
            if( ! in_array($this->_params['scope'], explode(' ', $client['scope'])))
                throw new Oauth2_Exception_Access('insufficient_scope');
        }

        if(isset($this->_params['timestamp']) AND $client['timestamp'] < $this->_params['timestamp'])
        {
            throw new Oauth2_Exception_Access('invalid_token');
        }

        // Verify the signature
        if( ! empty($this->_params['signature']) AND ! empty($this->_params['algorithm']))
        {
            $uri = URL::base(FALSE, TRUE).Request::$instance->uri;

            $string = Oauth2::normalize(Request::$method, $uri, $this->_params);

            $this->_params['algorithm'] = strtolower($this->_params['algorithm']);

            if($this->_params['algorithm'] === 'rsa-sha1' OR $this->_params['algorithm'] === 'hmac-sha1')
            {
                $response->public_cert = $client['ssh_key'];
                $response->private_cert = $this->_params['signature'];
            }

            if( ! Oauth2::signature($this->_params['algorithm'], $string)->check($response, $this->_params['signature']))
            {
                throw new Oauth2_Exception_Access('invalid_signature');
            }
        }

        return $response;
    }

} // END Oauth_Parameter_Access
