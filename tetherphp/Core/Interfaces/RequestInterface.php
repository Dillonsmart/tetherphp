<?php

namespace TetherPHP\Core\Interfaces;

interface RequestInterface
{
    public string $method {set; get;}
    public string $uri {set; get;}
}