<?php

namespace DTOs;

class HomePageData
{
    public string $title;

    public float $time {
        set {
            $this->time = $value;
        }
    }

    public function __construct(array $data = [])
    {
        $this->title = $data['title'] ?? 'Home Page';
    }
}