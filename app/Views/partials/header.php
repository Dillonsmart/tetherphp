<?php
/**
 * Set by the page that includes this partial, or left unset for the defaults.
 *
 * @var string|null $pageTitle
 * @var string|null $metaDescription
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($pageTitle ?? (string) env('APP_NAME', 'TetherPHP')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription ?? (string) env('APP_NAME', 'TetherPHP')); ?>" />

    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="font-serif">
