<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class Cookie
{

    protected $name;
    protected $value;
    protected $expire;
    protected $path;
    protected $domain;
    protected $secure;
    protected $httponly;

    public function __construct(
        $name, 
        $value=null,
        $expire=0,
        $path='/',
        $domain=null,
        $secure=false,
        $httponly=false
    ) {

        // Validate cookie name and value.
        // https://github.com/php/php-src/blob/php-5.4.16/ext/standard/head.c#L84
        $invalid_name = "=,; \t\r\n\013\014";
        if (preg_match("/[$invalid_name]/", $name)) {
            throw new \InvalidArgumentException(sprintf("Invalid cookie name %s. Cookie names cannot contain any of the following '%s'",$name, $invalid_name));
        }
        $invalid_value = ",; \t\r\n\013\014";
        if (preg_match("/[$invalid_value]/", $value)) {
            throw new \InvalidArgumentException(sprintf("Invalid cookie value %s. Cookie values cannot contain any of the following '%s'",$name, $invalid_value));
        }

        $this->name     = $name;
        $this->value    = $value;
        $this->expire   = $expire;
        $this->path     = $path;
        $this->domain   = $domain;
        $this->secure   = $secure;
        $this->httponly = $httponly;

    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function isSecure()
    {
        return $this->secure;
    }

    public function httpOnly()
    {
        return $this->httponly;
    }
}
