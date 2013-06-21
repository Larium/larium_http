<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class ServerParamsTest extends \PHPUnit_Framework_TestCase
{
    public function testHeaders()
    {
        $server = array(
            'HTTP_HOST' => 'localhost',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_USER_AGENT' => 'PHP Test',
            'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8,el;q=0.6',
            'CONTENT_TYPE' => 'multipart/form-data; boundary=----WebKitFormBoundaryAOM8U15CmDrqcHu1',
            'CONTENT_LENGTH' => 393,
        );

        $params = new ServerParams($server);

        $headers = $params->getHeaders();

        $this->assertEquals('localhost', $headers['Host']);
        $this->assertEquals('keep-alive', $headers['Connection']);
        $this->assertEquals('max-age=0', $headers['Cache-Control']);
        $this->assertEquals('text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', $headers['Accept']);
        $this->assertEquals('PHP Test', $headers['User-Agent']);
        $this->assertEquals('gzip,deflate,sdch', $headers['Accept-Encoding']);
        $this->assertEquals('en-US,en;q=0.8,el;q=0.6', $headers['Accept-Language']);
        $this->assertEquals('multipart/form-data; boundary=----WebKitFormBoundaryAOM8U15CmDrqcHu1', $headers['Content-Type']);
        $this->assertEquals(393, $headers['Content-Length']);

    }
}

