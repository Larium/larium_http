<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class Response implements ResponseInterface
{
    protected $status_codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );

    protected $headers;

    protected $protocol;

    protected $body;

    protected $status;

    protected $cookies=array();

    protected $deleted_cookies=array();

    public function __construct(
        $body = null,
        $status = 200,
        array $headers=array(),
        $protocol = ResponseInterface::PROTOCOL_1_1
    ) {
        $this->protocol = $protocol;

        $this->setBody($body);

        $this->headers = new Params($headers);

        $this->setStatus($status ?: 200);
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function addHeader($name, $value)
    {
        $this->headers->set($name, $value);

        return $this;
    }

    public function removeHeader($name)
    {
        $this->headers->remove($name);
    }

    public function setBody($value)
    {
        $this->body = $value;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setStatus($status)
    {
        if (array_key_exists($status, $this->status_codes)) {
            $this->status = $status;
        } else {
            throw new \InvalidArgumentException(sprintf('Not a valid status code %s', $status));
        }

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusText()
    {
        return sprintf(
            '%s %s %s',
            $this->protocol,
            $this->status,
            $this->status_codes[$this->status]
        );
    }

    public function hasRedirection()
    {
        return $this->headers->get('Location')
            || ($this->status > 300 && $this->status < 400);
    }

    public function send()
    {
        $this->send_headers();

        return $this->getBody();
    }

    public function setCookie(Cookie $cookie)
    {
        $this->cookies[$cookie->getName()] = $cookie;

        return $this;
    }

    public function deleteCookie($name)
    {
        $this->deleted_cookies[] = $name;

        return $this;
    }

    protected function send_headers()
    {

        if (headers_sent()) {
            return;
        }

        header($this->getStatusText());

        // Send headers
        foreach($this->headers as $name=>$value) {
            header(sprintf('%s: %s', $name, $value));
        }

        //Delete cookies
        foreach ($this->deleted_cookies as $d) {
            setcookie($d, "", time() - 3600);
        }

        // Send cookies
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpire(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->httpOnly()
            );
        }
    }

    public function __toString()
    {
        return $this->send();
    }
}
