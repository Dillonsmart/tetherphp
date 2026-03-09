<?php view('partials/header'); ?>

    <main class="flex flex-col items-center justify-center px-4 sm:px-6" style="min-height: calc(100vh - 140px);">
        <section class="text-center max-w-2xl">
            <h1 class="text-5xl sm:text-6xl lg:text-8xl mb-6"><?php echo $appName; ?></h1>
            <p class="text-lg sm:text-xl lg:text-2xl mb-8 opacity-70"><?php echo $tagline; ?></p>

            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 mb-12">
                <a href="/docs" class="inline-block border px-6 py-3 text-sm hover:opacity-70">Get Started</a>
                <a href="https://packagist.org/packages/dillonsmart/tetherphp" class="inline-block border px-6 py-3 text-sm opacity-60 hover:opacity-100">Install via Composer</a>
            </div>

            <pre class="inline-block text-xs sm:text-sm px-4 sm:px-6 py-3 opacity-50 cursor-pointer max-w-full overflow-x-auto">composer create-project dillonsmart/tetherphp</pre>
        </section>
    </main>

    <footer class="flex flex-col sm:flex-row items-center justify-between gap-2 px-4 sm:px-6 py-4 text-xs opacity-50">
        <span>&copy; <?php echo date('Y'); ?> <?php echo $appName; ?></span>
        <div class="flex gap-4">
            <a href="https://github.com/DillonSmart/tetherphp" class="hover:opacity-70">GitHub</a>
            <a href="https://packagist.org/packages/dillonsmart/tetherphp" class="hover:opacity-70">Packagist</a>
            <a href="https://x.com/d2sdev" class="hover:opacity-70">@d2sdev</a>
        </div>
    </footer>

<?php view('partials/footer'); ?>
