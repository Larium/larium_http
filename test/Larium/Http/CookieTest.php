<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class CookieTest extends \PHPUnit_Framework_TestCase
{
    public function testValidCookie()
    {
        $cookie = new Cookie('a_cookie', 'value');

        $this->assertEquals('a_cookie', $cookie->getName());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCookieName()
    {

        $cookie = new Cookie('=a_cookie', 'value');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCookieValue()
    {
        $cookie = new Cookie('a_cookie', ';value');
    }
}
