<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

interface RequestInterface
{
    const GET_METHOD     = 'GET';
    const POST_METHOD    = 'POST';
    const PUT_METHOD     = 'PUT';
    const DELETE_METHOD  = 'DELETE';

    public function getPath();
    
    public function getBasePath();

    public function getMethod();

    public function getUrl();
    
    public function getQuery();

    public function getPost();

    public function getFiles();
}
