<?php
/** @var \DTOs\HomePageData $data */
view('partials/header'); ?>
    <div style="margin: 2rem 0;">
        <div class="heading-area">
            <div class="container">
                <h1>A Refined ADR Framework for PHP</h1>
            </div>
        </div>

        <section class="container">
            <h2>
                <span class="font-bold">A</span>ction &mdash;
                <span class="font-bold">D</span>omain &mdash;
                <span class="font-bold">R</span>esponder
            </h2>
            <p>Discover the elegance of PHP with TetherPHP, a framework crafted for clarity and simplicity.</p>
            <p>Build sophisticated websites and applications effortlessly, embracing the art of minimalism and precision.</p>
            <p>TetherPHP is in its early days&mdash;the framework is actively evolving and under development.</p>
        </section>

        <section class="container">
            <h2>Some stats</h2>
            <p>This page took [<span class="font-bold"><?php echo $data->time; ?> ms</span>] to load.</p>
        </section>
    </div>
<?php view('partials/footer'); ?>