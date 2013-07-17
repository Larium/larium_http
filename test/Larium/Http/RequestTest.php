<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPath()
    {
        $server = array(
            'HTTP_HOST' => 'demo.local',
            'SERVER_ADDR' => '127.0.0.1',
            'SERVER_PORT' => 80,
            'REMOTE_ADDR' => '127.0.0.1',
            'DOCUMENT_ROOT' => '/srv/http/test',
            'SCRIPT_FILENAME' => '/srv/http/test/php/index.php',
            'REDIRECT_QUERY_STRING' => 'page=1&test=2',
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => 'page=1&test=2',
            'REQUEST_URI' => '/php/show/1?page=1&test=2',
            'SCRIPT_NAME' => '/php/index.php',
            'PHP_SELF' => '/php/index.php'
        );

        $request = new Request(null, array(), array(), array(), array(), $server);

        $this->assertEquals('/show/1', $request->getPath());

        $this->assertEquals('/php/', $request->getBasePath());

        $this->assertEquals('http://demo.local/php/show/1?page=1&test=2', $request->getUrl());

        $this->assertEquals('demo.local', $request->getHeaders()->get('Host'));


        $server = array(
            'HTTP_HOST' => 'demo.local',
            'SERVER_ADDR' => '127.0.0.1',
            'SERVER_PORT' => 80,
            'REMOTE_ADDR' => '127.0.0.1',
            'DOCUMENT_ROOT' => '/srv/http/test',
            'SCRIPT_FILENAME' => '/srv/http/test/index.php',
            'REDIRECT_QUERY_STRING' => 'page=1&test=2',
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => 'page=1&test=2',
            'REQUEST_URI' => '/artist/show/id/1?page=1&test=2',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php'
        );

        $request = new Request(null, array(), array(), array(), array(), $server);

        $this->assertEquals('/artist/show/id/1', $request->getPath());

        $this->assertEquals('/', $request->getBasePath());

        $this->assertEquals('http://demo.local/artist/show/id/1?page=1&test=2', $request->getUrl());

    }

    /**
     * @dataProvider urls
     */
    public function testRequestFromUri($url, $path, $method, $scheme, $host, $port, $referer, $ajax, $protocol, $query_string, $query_array, $is_post)
    {
        $request = new Request($url);

        $this->assertEquals($path, $request->getPath());
        $this->assertEquals($method, $request->getMethod());
        $this->assertEquals($scheme, $request->getScheme());
        $this->assertEquals($host, $request->getHost());
        $this->assertEquals($port, $request->getPort());
        $this->assertEquals($referer, $request->getReferer());
        $this->assertEquals($ajax, $request->isAjax());
        $this->assertEquals($protocol, $request->getProtocol());
        $this->assertEquals($query_string, $request->getQueryString());
        $this->assertEquals($query_array, $request->getQuery());
        $this->assertEquals($is_post, $request->isPost());
    }


    public function urls()
    {
         return array(
            array(
                "http://example.com/home/show/id/1/slug/test",
                "/home/show/id/1/slug/test",
                "GET",
                "http",
                "example.com",
                80,
                null,
                false,
                'HTTP/1.1',
                null,
                array(),
                false
            ),
            array(
                "http://www.example.com/show/1?foo=bar",
                "/show/1",
                "GET",
                "http",
                "www.example.com",
                80,
                null,
                false,
                'HTTP/1.1',
                'foo=bar',
                array('foo'=>'bar'),
                false
            )
        );
    }

    public function testRequestPostMethod()
    {
        $server = array(
            'HTTP_HOST' => 'demo.local',
            'SERVER_ADDR' => '127.0.0.1',
            'SERVER_PORT' => 80,
            'REMOTE_ADDR' => '127.0.0.1',
            'DOCUMENT_ROOT' => '/srv/http/test',
            'SCRIPT_FILENAME' => '/srv/http/test/index.php',
            'REDIRECT_QUERY_STRING' => 'page=1&test=2',
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_METHOD' => 'POST',
            'QUERY_STRING' => 'page=1&test=2',
            'REQUEST_URI' => '/artist/show/id/1?page=1&test=2',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php'
        );

        $request = new Request(null, array(), array('foo'=>'bar'), array(), array(), $server);

        $this->assertEquals(RequestInterface::POST_METHOD, $request->getMethod());
    }

    public function testRequestPostMethodWithFiles()
    {
        $server = array(
            'HTTP_HOST' => 'demo.local',
            'SERVER_ADDR' => '127.0.0.1',
            'SERVER_PORT' => 80,
            'REMOTE_ADDR' => '127.0.0.1',
            'DOCUMENT_ROOT' => '/srv/http/test',
            'SCRIPT_FILENAME' => '/srv/http/test/index.php',
            'REDIRECT_QUERY_STRING' => 'page=1&test=2',
            'GATEWAY_INTERFACE' => 'CGI/1.1',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REQUEST_METHOD' => 'POST',
            'QUERY_STRING' => 'page=1&test=2',
            'REQUEST_URI' => '/artist/show/id/1?page=1&test=2',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php'
        );

        $files = array(
            'file' => array(
                'name' => 'foo',
                'tmp_name' => '/tmp/phpXdresd',
                'type' => 'image/jpeg',
                'error' => 0,
                'size' => '1024'
            )
        );

        $request = new Request(null, array(), array('foo'=>'bar'), array(), $files, $server);

        $this->assertEquals(RequestInterface::POST_METHOD, $request->getMethod());

        $this->assertEquals($files, $request->getFiles());
    }
}
