<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testResponseHeaders()
    {
        $response = new Response();

        $response->addHeader('Content-Type', 'application/pdf');

        $this->assertEquals('application/pdf', $response->getHeaders()->get('Content-Type'));
    }

    public function testResponseStatus()
    {
        $response = new Response();

        $response->setStatus('404');

        $this->assertEquals('HTTP/1.1 404 Not Found', $response->getStatusText());
    }
}
