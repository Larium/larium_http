<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

use Larium\Http\Session\SessionInterface;

class Request implements RequestInterface
{
    const GET_METHOD     = 'GET';
    const POST_METHOD    = 'POST';
    const PUT_METHOD     = 'PUT';
    const DELETE_METHOD  = 'DELETE';

    /**
     * @var \Larium\Http\ServerParams;
     * @access protected
     */
    protected $server;

    /**
     * @var \Larium\Http\Params;
     * @access protected
     */
    protected $query;

    /**
     * @var \Larium\Http\Params;
     * @access protected
     */
    protected $post;

    /**
     * @var \Larium\Http\Params;
     * @access protected
     */
    protected $cookies;

    /**
     * @var \Larium\Http\Params;
     * @access protected
     */
    protected $files;

    /**
     * @var \Larium\Http\Params;
     * @access protected
     */
    protected $headers;

    protected $path;
    
    protected $basepath;

    protected $method = self::GET_METHOD;

    protected $scheme;

    protected $script_file;

    /**
     * @var Larium\Http\Session\Session
     * @access protected
     */
    protected $session;
    
    /* -(  Initialize  )---------------------------------------------------- */

    public function __construct(
        $uri=null,
        array $get=array(),        
        array $post=array(),
        array $cookies=array(),
        array $files = array(),
        array $server = array()
    ) {

        $this->query    = new Params($get);

        if (null !== $uri) {
            $server = array_merge($server, $this->parse_uri($uri));
        } 
        
        $this->server   = new ServerParams($server);
        $this->post     = new Params($post);
        $this->cookies  = new Params($cookies);
        $this->files    = new Params($files);
        $this->method   = $this->server['REQUEST_METHOD'];

        $this->headers  = new Params($this->server->getHeaders());
        $this->scheme   = $this->server->getScheme();

        $this->set_scriptfile();
        $this->set_basepath();
        $this->set_path();

    }
    
    /**
     * @link http://www.php.net/manual/en/reserved.variables.php  PHP global 
     * variables for server and cli request
     */
    public static function createFromServer() {

        $request = new static(null, $_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);

        return $request;
    }

    protected function set_basepath()
    {
        $this->basepath = str_replace(
            $this->script_file,
            '',
            $this->server['SCRIPT_NAME']
        );
    }

    protected function set_scriptfile()
    {
        preg_match('/[\w\-]+\.php/', $this->server['SCRIPT_NAME'], $m);
        if (!empty($m)) {
            $this->script_file = $m[0];
        }
    }

    protected function set_path()
    {
        $request_uri = $this->server['REQUEST_URI'];
        $basepath = $this->basepath == '/' ? '' : $this->basepath;

        $find = array(
            $this->server['SCRIPT_NAME'], 
            $basepath, 
            '?'.$this->server['QUERY_STRING']
        );
        
        $this->path = str_replace($find, '', $request_uri);

        $this->path = '/'.ltrim($this->path,'/');
    }


    /* -(  Getters / Mutators  )--------------------------------------------- */

    public function getPath()
    {
        return $this->path;
    }

    public function getBasePath()
    {
        return $this->basepath;
    }

    public function getMethod()
    {
        return $this->method; 
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getHost()
    {
        return $this->headers['Host'];
    }

    public function getFullHost()
    {
        $port = $this->getPort()==80 || $this->getPort()==443
            ? null
            : ":".$this->getPort();

        return $this->getScheme() . '://' . $this->getHost() . $port;
    }

    public function getPort()
    {
        return $this->server['SERVER_PORT'];
    }

    public function getReferer()
    {
        return $this->headers['Referer'];
    }

    public function getUrl()
    {
        return $this->getFullHost() 
            . ($this->basepath ? rtrim($this->basepath, '/') : null)
            . ($this->path ?: null)
            . ($this->getQueryString() ? "?".$this->getQueryString() : null);
    }

    /**
     * @return boolean
     */
    public function isXHttpRequest()
    {
        return $this->headers['X-Requested-With'] 
            && $this->headers['X-Requested-With'] == 'XMLHttpRequest';
    }

    /**
     * Alias of isXHttpRequest method
     * 
     * @return boolean
     */
    public function isAjax()
    {
        return $this->isXHttpRequest();
    }

    public function getProtocol()
    {
        return $this->server['SERVER_PROTOCOL'];
    }

    public function getQueryString()
    {
        return $this->server['QUERY_STRING'];
    }

    public function getQuery()
    {
        return $this->query; 
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function isPost()
    {
        return in_array(
            $this->method,
            array(
                self::POST_METHOD,
                self::PUT_METHOD,
                self::DELETE_METHOD
            )
        );
    }

    protected function parse_uri($uri)
    {
        if (false === filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(sprintf('Invalid url %s', $uri));
        }

        $components = parse_url($uri);

        $server = array();

        $server['HTTP_HOST']   = $components['host'];
        if (isset($components['port'])) {
            $server['SERVER_PORT'] = $components['port'];
        } else {
            if (isset($components['scheme']) 
                && $components['scheme'] === 'https'
            ) {
                $server['SERVER_PORT'] = 443;
            } else {
                $server['SERVER_PORT'] = 80;
            }
        }
        if (isset($components['user'])) {
            $server['PHP_AUTH_USER'] = $compontents['user'];
        }
        if (isset($components['pass'])) {
            $server['PHP_AUTH_PW'] = $compontents['pass'];
        }

        $server['REQUEST_URI'] = '';
        if (isset($components['path'])) {
            $server['REQUEST_URI'] .= $components['path'];
        }

        if (isset($components['query'])) {
            $server['QUERY_STRING'] = $components['query'];
            $server['REQUEST_URI'] .= '?'.$components['query'];
            parse_str($components['query'], $query);
            $this->query->add($query);
        }

        return $server;
    }
}
