<?php view('partials/header'); ?>
    <div style="margin: 2rem 0;">
        <div class="heading-area">
            <div class="container">
                <h1>Usage</h1>
            </div>
        </div>

        <section class="container">
            <p>Thank you for choosing to use TetherPHP.</p>
            <p>Before getting started, please check you are able to meet the <a href="/docs/requirements">requirements</a>.</p>
            <p>
                TetherPHP is designed to be straightforward to use, with minimal dependencies.
                Its intuitive structure allows developers to quickly set up projects without complex configuration.
                The framework focuses on simplicity, enabling you to build robust applications with clean, maintainable code.
                By minimizing external requirements, TetherPHP ensures fast installation and reduces potential compatibility issues,
                making it ideal for both small and large-scale PHP projects.
            </p>
        </section>
        <section class="container">
            <h2>Getting started</h2>
            <p>The first step is to grab the latest copy of TetherPHP. You can do this by cloning the repository:</p>
            <pre>git clone https://github.com/Dillonsmart/tetherphp.git</pre>
            <p>After cloning the repository, you may notice the framework comes packaged with a complete copy of this website.</p>
            <p>The decision behind this was to ship with a fully functional website, demonstrating how to use the framework.</p>
            <h3>Clearing all the boilerplate</h3>
            <p>To clear all the boilerplate, you can run the following command. This will leave you with a blank slate to build whatever your heart desires.</p>
            <p>From the root directory of the project, run:</p>
            <pre>php tether boilerplate:clear</pre>
            <p>Now you've cleared all the boilerplate code, your ready to get to work.</p>
        </section>
    </div>
<?php view('partials/footer'); ?>