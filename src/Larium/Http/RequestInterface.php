<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

interface RequestInterface
{
    public function getPath();
    
    public function getBasePath();

    public function getMethod();

    public function getUrl();
    
    public function getQuery();

    public function getPost();

    public function getFiles();
}
