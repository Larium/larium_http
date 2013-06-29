<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

interface ResponseInterface
{
    const PROTOCOL_1_1 = "HTTP/1.1";
    const PROTOCOL_1_0 = "HTTP/1.0";

    public function getProtocol();

    public function addHeader($name, $value);

    public function getHeaders();

    public function setBody($body);

    public function getBody();

    public function send();
}
