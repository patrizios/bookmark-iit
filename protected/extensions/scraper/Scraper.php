<?php
/**
 *
 */
class Scraper
{
    /**
     * [$maxredirects]
     * Http client max redirects
     * @var integer
     */
    public $maxredirects = 1;

    /**
     * [$timeout]
     * Http client timeout
     * @var integer
     */
    public $timeout = 30;

    /**
     * [$permitted_content_types]
     * Permitted content types
     * @var array
     */
    public $permitted_content_types = array(
        'text/html' => 'html'
    );

    private $_url;
    private $_client;
    private $_response;

    /**
     * [__construct]
     *
     * @param string $url Url to scrape
     */
    public function __construct($url)
    {
        $this->setUrl($url);
        $this->run();
    }

    /**
     * [run]
     * Run the scrape operations
     *
     * @return null
     */
    protected function run()
    {
        $this->_initClient();
        $this->sendRequest();

        $response = $this->getResponse();

        if (!$response->isSuccessful()) {
            throw new ScraperRequestErrorException(
                'Request Error: ' . $this->_url
            );
        }

        if (!$this->isPermittedContentType()) {
            throw new ScraperContentNotPermittedException(
                'Content not permitted: ' . $this->getResponseContenType()
            );
        }
    }

    /**
     * [_initClient]
     * Init the client object
     *
     * @return null
     */
    private function _initClient()
    {
        if (!$this->getClient()) {
            $this->_client = new EHttpClient(
                $this->_url,
                array(
                    'maxredirects' => $this->maxredirects,
                    'timeout'      => $this->timeout
                )
            );
        }
    }

    /**
     * [setUrl]
     *
     * @param string $url Set uri
     *
     * @throws If not valid url
     * @return null
     */
    public function setUrl($url)
    {
        if ($this->isValidUrl($url)) {
            $this->_url = $url;
        } else {
            throw new ScraperInvalidUrlException(
                'Invalid URL: ' . $this->_url
            );
        }
    }

    /**
     * [getUrl]
     *
     * @return string Url property
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * [getClient]
     *
     * @return EHttpClient Http client object
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * [sendRequest]
     * Make the client send the request
     * Set the response property
     *
     * @return null
     */
    public function sendRequest()
    {
        $client             = $this->getClient();
        $this->_response    = $client->request();
    }

    /**
     * [getResponse]
     *
     * @return EHttpResponse Return the saved response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * [getResponseBody]
     *
     * @return string The response body
     */
    public function getResponseBody()
    {
        return $this->_response->getBody();
    }

    /**
     * [getResponseHeaders]
     * Get all response headers
     *
     * @return mixed Response HTTP headers, NULL if no Response
     */
    public function getResponseHeaders()
    {
        if ($this->_response) {
            return $this->_response->getHeaders();
        } else {
            return null;
        }
    }

    /**
     * [getResponseHeader]
     * Get a specific response header
     *
     * @param string $name Header name
     *
     * @return mixed Response HTTP headers, NULL if no Response
     */
    public function getResponseHeader($name)
    {
        if ($this->getResponseHeaders()) {
            $headers = $this->getResponseHeaders();

            if (isset($headers[$name])) {
                return $headers[$name];
            }
        }

        return null;
    }

    /**
     * [getResponseContenType]
     *
     * @return string response content type
     */
    public function getResponseContenType()
    {
        $content_type = $this->getResponseHeader('Content-type');

        if ($content_type) {

            $pos = strpos($content_type, ';');

            if ($pos !== false) {
                $ct_exploded    = explode(';', $content_type);
                $content_type   = $ct_exploded[0];
            }

            $content_type = strtolower($content_type);
        }

        return $content_type;
    }

    /**
     * [isPermittedContentType]
     * Check if the response have a permitted content type
     *
     * @return boolean [description]
     */
    public function isPermittedContentType()
    {
        $content_type = $this->getResponseContenType();

        if ($content_type) {
            $permitted_types = array_keys($this->permitted_content_types);
            return in_array($content_type, $permitted_types);
        }

        return false;
    }

    /**
     * [isValidUrl]
     * Check if a string is a valid url
     *
     * @param string $url Url to check
     *
     * @return boolean TRUE if valid url, FALSE if not
     */
    public static function isValidUrl($url)
    {
        // scheme
        $regex = "^(https?|ftp)\:\/\/";

        // user and pass (optional)
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";

        // http://x.xx(x) = minimum
        $regex .= "([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}";

        // port (optional)
        $regex .= "(\:[0-9]{2,5})?";

        // path (optional)
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";

        return preg_match("/$regex/siU", $url);
    }

    /**
     * [isAbsoluteUrl]
     * Check if a URL is absoute
     *
     * @param string $url URL to check
     *
     * @return boolean TRUE if absolute, FALSE if relative
     */
    public function isAbsoluteUrl($url)
    {
        $parsed = parse_url($url);

        if (isset($parsed['scheme']) && isset($parsed['domain']))
            return true;
        else
            return false;
    }

}
/**
 * Generic Scraper Exception
 */
class ScraperException extends Exception
{
}

/**
 * Invalid URL Exception
 */
class ScraperInvalidUrlException extends ScraperException
{
}

/**
 * Request Error Exception
 */
class ScraperRequestErrorException extends ScraperException
{
}

/**
 * Content not permitted Exception
 */
class ScraperContentNotPermittedException extends ScraperException
{
}
