<?php

namespace Action\HomePage;

class ShowHomePageAction
{
    public function __invoke(): string
    {
        // This method would typically render the home page view
        return "Welcome to the Home Page!";
    }
}