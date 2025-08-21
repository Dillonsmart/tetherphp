<?php view('partials/header'); ?>
    <div style="margin: 2rem 0;">
        <div class="heading-area">
            <div class="container">
                <h1>Routing</h1>
            </div>
        </div>

        <div class="container">
            <section>
                <h2>An elegant routing solution</h2>
                <p>TetherPHP's routing system is designed to be simple and intuitive, allowing you to define routes with minimal effort.</p>
                <p>Routes are defined in the <span class="font-bold">routes/web.php</span> file, where you can specify the URL patterns and the corresponding actions.</p>
            </section>

            <section>
                <h3>On this page</h3>
                <ul>
                    <li><a href="#defining-routes">Defining routes</a></li>
                    <li><a href="#returning-views">Returning views</a></li>
                    <li><a href="#dynamic-routes">Dynamic routes</a></li>
                </ul>
            </section>

            <section>
                <h3 id="defining-routes">Defining routes</h3>
                <pre><code>$router->get('/path', YourAction::class);</code></pre>
                <p>In this example, the <code>/path</code> URL will trigger the <code>YourAction</code> class when accessed via a GET request. The action class will then be invoked and trigger your domain logic and return a responder.</p>
                <p>For POST requests, you can define routes similarly:</p>
                <pre><code>$router->post('/path', YourAction::class);</code></pre>
            </section>

            <section>
                <h3 id="returning-views">Returning Views</h3>
                <p>TetherPHP is an ADR framework, meaning by design, when calling a route an Action is invoked, Domain business logic is executed, and a Responder is returned.</p>
                <p>This can become overkill when you need to return some static HTML, CSS and JavaScript.</p>
                <p>To keep the code base cleaner, you can return views directly when making a GET request:</p>
                <pre><code>$router->view('/path', 'pages.home');</code></pre>
            </section>

            <section>
                <h3 id="dynamic-routes">Dynamic Routes</h3>
                <p>Dynamic routes can be defined using placeholders in the URL. For example:</p>
                <pre><code>$router->get('/user/{id}', UserAction::class);</code></pre>
            </section>
        </div>
    </div>
<?php view('partials/footer'); ?>