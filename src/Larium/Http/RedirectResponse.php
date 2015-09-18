<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Http;

class RedirectResponse extends Response
{
    public function __construct($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(sprintf('Invalid url %s', htmlspecialchars($url, ENT_QUOTES, 'UTF-8')));
        }

        parent::__construct(
            '',
            302,
            array(
                'Location' => $url
            )
        );
    }
}
