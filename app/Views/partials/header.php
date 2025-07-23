<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $pageTitle ?? env('APP_NAME') ;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header>
        <div class="container flex justify-between">
            <h1><a href="/">TetherPHP</a></h1>
            <nav>
                <ul>
                    <li><a href="/docs">Docs</a></li>
                    <li><a href="https://github.com/DillonSmart/tetherphp" target="_blank">GitHub</a></li>
                    <li><a href="https://x.com/dillon_smart" target="_blank">Follow on X</a></li>
                </ul>
            </nav>
        </div>
    </header>