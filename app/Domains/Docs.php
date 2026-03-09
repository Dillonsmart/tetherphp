<?php

namespace Domains;

class Docs extends Domain
{
    private array $pages = [
        'index' => ['title' => 'Documentation', 'view' => 'pages.docs.index'],
        'adr' => ['title' => 'What is ADR?', 'view' => 'pages.docs.adr'],
        'requirements' => ['title' => 'Requirements', 'view' => 'pages.docs.requirements'],
        'usage' => ['title' => 'Usage', 'view' => 'pages.docs.usage'],
        'routing' => ['title' => 'Routing', 'view' => 'pages.docs.routing'],
        'responders' => ['title' => 'Responders', 'view' => 'pages.docs.responders'],
    ];

    private array $navigation = [
        ['label' => 'Getting Started', 'items' => [
            ['label' => 'What is ADR?', 'href' => '/docs/adr'],
            ['label' => 'Requirements', 'href' => '/docs/requirements'],
            ['label' => 'Usage', 'href' => '/docs/usage'],
        ]],
        ['label' => 'Core Concepts', 'items' => [
            ['label' => 'Routing', 'href' => '/docs/routing'],
            ['label' => 'Responders', 'href' => '/docs/responders'],
        ]],
    ];

    public function handle(string $page = 'index'): array
    {
        if (!isset($this->pages[$page])) {
            return [
                'found' => false,
                'navigation' => $this->navigation,
                'currentPage' => $page,
            ];
        }

        $pageData = $this->pages[$page];

        return [
            'found' => true,
            'title' => $pageData['title'],
            'view' => $pageData['view'],
            'navigation' => $this->navigation,
            'currentPage' => $page,
        ];
    }
}
