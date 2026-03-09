<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $pageTitle ?? env('APP_NAME'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo $metaDescription ?? 'TetherPHP - A redefined framework for PHP using the Action-Domain-Responder pattern.'; ?>" />

    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-serif">
<header class="flex justify-between items-center px-4 sm:px-6 py-4">
    <a href="/" class="text-sm font-bold tracking-wide"><?php echo env('APP_NAME'); ?></a>
    <nav>
        <ul class="flex gap-4 sm:gap-6 text-sm">
            <li><a href="/docs" class="underline underline-offset-4 hover:opacity-70">Docs</a></li>
            <li><a href="https://github.com/DillonSmart/tetherphp" class="underline underline-offset-4 hover:opacity-70">GitHub</a></li>
            <li><a href="https://x.com/d2sdev" class="underline underline-offset-4 hover:opacity-70">@d2sdev</a></li>
        </ul>
    </nav>
</header>
