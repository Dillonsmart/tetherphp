<?php include views_dir() . '/partials/docs-layout-start.php'; ?>

<h1 class="text-3xl sm:text-4xl mb-6">Requirements</h1>

<ul class="space-y-4 mb-10">
    <li class="flex gap-3">
        <span class="font-bold shrink-0">PHP 8.4+</span>
        <span class="opacity-70">— TetherPHP uses modern PHP features like property hooks and backed enums</span>
    </li>
    <li class="flex gap-3">
        <span class="font-bold shrink-0">Composer</span>
        <span class="opacity-70">— For autoloading. TetherPHP has zero dependency requirements out of the box</span>
    </li>
    <li class="flex gap-3">
        <span class="font-bold shrink-0">Web Server</span>
        <span class="opacity-70">— Apache, Nginx, Laravel Herd, or PHP's built-in server</span>
    </li>
</ul>

<div class="flex justify-between">
    <a href="/docs/adr" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; What is ADR?</a>
    <a href="/docs/usage" class="text-sm underline underline-offset-4 hover:opacity-70">Next: Usage &rarr;</a>
</div>

<?php include views_dir() . '/partials/docs-layout-end.php'; ?>
