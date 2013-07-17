<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

/**
 * ServerParams
 *
 * * PHP_SELF
 * * PHP_AUTH_DIGEST
 * * PHP_AUTH_USER
 * * PHP_AUTH_PW
 * * AUTH_TYPE
 *
 * * GATEWAY_INTERFACE (cgi version)
 *
 * * SERVER_ADDR (ip address of server)
 * * SERVER_NAME (name of the server host)
 * * SERVER_SOFTWARE
 * * SERVER_PROTOCOL (HTTP/1.0 or HTTP/1.1)
 * * SERVER_ADMIN
 * * SERVER_PORT
 * * SERVER_SIGNATURE
 *
 * * REQUEST_METHOD
 * * REQUEST_TIME (timestamp of the start of the request)
 * * REQUEST_TIME_FLOAT
 * * REQUEST_URI
 *
 * * QUERY_STRING
 * * DOCUMENT_ROOT
 * * HTTPS
 * * PATH_TRANSLATED (optional must set AcceptPathInfo = On in Apache)
 * * PATH_INFO
 * * ORIG_PATH_INFO
 *
 * * HTTP_ACCEPT (Header Accept:)
 * * HTTP_ACCEPT_CHARSET (Header Accept-Charset:)
 * * HTTP_ACCEPT_ENCODING (Header Accept-Encoding:)
 * * HTTP_ACCEPT_LANGUAGE (Header Accept-Language:)
 * * HTTP_CONNECTION (Header Connection:)
 * * HTTP_HOST (Header Host:)
 * * HTTP_REFERER (Header Referer:)
 * * HTTP_USER_AGENT (Header User-Agent:)
 * * CONTENT_TYPE (Header Content-Type:)
 * * CONTENT_LENGTH (Header Content-Length:)
 *
 *
 * * REMOTE_ADDR (ip address of client)
 * * REMOTE_HOST (host name of client. Must set HostnameLookups On in Apache.)
 * * REMOTE_PORT
 * * REMOTE_USER
 * * REDIRECT_REMOTE_USER
 *
 * * SCRIPT_FILENAME (absolute pathname of script)
 * * SCRIPT_NAME
 *
 * @uses    Params
 * @package Larium\Http
 * @author  Andreas Kollaros <php@andreaskollaros.com>
 * @license MIT {@link http://opensource.org/licenses/mit-license.php}
 */
class ServerParams extends Params
{

    public function __construct($storage)
    {
        $server = array(
            'SERVER_NAME'           => 'localhost',
            'SERVER_ADDR'           => '127.0.0.1',
            'SERVER_PORT'           => 80,
            'REMOTE_ADDR'           => '127.0.0.1',
            'SERVER_PROTOCOL'       => 'HTTP/1.1',
            'REQUEST_METHOD'        => 'GET',
            'HTTP_HOST'             => 'localhost',
            'HTTP_CONNECTION'       => 'keep-alive',
            'HTTP_CACHE_CONTROL'    => 'max-age=0',
            'HTTP_ACCEPT'           => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_USER_AGENT'       => 'Larium',
            'HTTP_ACCEPT_ENCODING'  => 'gzip,deflate,sdch',
            'HTTP_ACCEPT_LANGUAGE'  => 'en-US,en;q=0.8,el;q=0.6',
        );

        $storage = array_replace($server, $storage);

        parent::__construct($storage);
    }
    /**
     * Gets http headers with the canonical name.
     *
     * PHP stores standard and custom header info in $_SERVER global by
     * prepending 'HTTP_' string. Also converts '-' char to '_' and
     * uppercase header name.
     *
     * This converts names of header to canonical and return an array of them.
     *
     * @access public
     * @return void
     */
    public function getHeaders()
    {
        $headers = array();

        foreach ($this->toArray() as $key=>$value) {
            if (0 === strpos($key, 'HTTP_')) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$name] = $value;
            } elseif($key == 'CONTENT_TYPE'
                  || $key == 'CONTENT_LENGTH'
                  || $key == 'CONTENT_MD5'
            ) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }

    public function getScheme()
    {
        return isset($this->storage['HTTPS'])
            || $this->storage['SERVER_PORT'] == 443
            ? 'https'
            : 'http';
    }
}
