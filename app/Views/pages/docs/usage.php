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
            <h2 class="font-semibold" id="gettingStarted">Getting started</h2>
            <p id="cloning">The first step is to grab the latest copy of TetherPHP. You can do this by either cloning the repo from <a href="https://github.com/Dillonsmart/tetherphp" target="_blank">GitHub</a>:</p>
            <pre>git clone https://github.com/Dillonsmart/tetherphp.git</pre>
            <p id="installing">Or you can create a new project using Composer:</p>
            <pre>composer create-project dillonsmart/tetherphp ./new-project</pre>
            <p>Once you have a copy of the project local, ensure you copy the <span class="font-bold">.env.example</span> to <span class="font-bold">.env</span></p>
            <h3>Installing Dependencies</h3>
            <p>TetherPHP doesn't require any dependencies out of the box. However, Composer is used to manage dependencies should you want to include any later on.</p>
            <p>Generate the autoload load file for Composer:</p>
            <pre>composer install</pre>
            <h3 id="clearBoilerPlate">Clearing all the boilerplate</h3>
            <p>To clear all the boilerplate, you can run the following command. This will leave you with a blank slate to build whatever your heart desires.</p>
            <p>From the root directory of the project, run:</p>
            <pre>php tether boilerplate:clear</pre>
            <p>Now you've cleared all the boilerplate code, your ready to get to work.</p>
        </section>
    </div>
<?php view('partials/footer'); ?>