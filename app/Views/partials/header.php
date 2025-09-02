<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $pageTitle ?? env('APP_NAME') ;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=TASA+Explorer:wght@400..800&display=swap" rel="stylesheet">

    <!-- styles -->
    <link rel="stylesheet" href="/css/app.css">
</head>
<header class="flex justify-end px-6 py-4">
    <nav>
        <ul>
            <li><a href="https://tetherphp.com/docs">Documentation</a></li>
        </ul>
    </nav>
</header>
<body class="dark">