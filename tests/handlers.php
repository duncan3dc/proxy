<?php

namespace duncan3dc\Proxy
{
    use duncan3dc\ProxyTests\Handlers;

    function header($header)
    {
        return Handlers::call("header", $header);
    }
}
