<?php include views_dir() . '/partials/docs-layout-start.php'; ?>

<h1 class="text-3xl sm:text-4xl mb-4">Routing</h1>
<p class="text-lg sm:text-xl mb-8 opacity-70">An elegant routing solution</p>

<p class="mb-10">Routes are defined in <code>routes/web.php</code>. Each route maps a URL pattern to an Action class.</p>

<h2 class="text-2xl mb-4">GET and POST Routes</h2>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>$router->get('/path', YourAction::class);
$router->post('/path', YourAction::class);</code></pre>

<p class="mb-10">When a request matches, the Kernel instantiates the Action and invokes it. The Action handles the rest — calling the Domain for data and passing it to the Responder.</p>

<h2 class="text-2xl mb-4">View Routes</h2>

<p class="mb-4">Not every page needs the full ADR cycle. For static pages, return a view directly:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>$router->view('/about', 'pages.about');</code></pre>

<p class="mb-10 text-sm opacity-60">This renders <code>app/Views/pages/about.php</code> without an Action, Domain, or Responder.</p>

<h2 class="text-2xl mb-4">Dynamic Routes</h2>

<p class="mb-4">Use curly braces to define URL parameters:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>$router->get('/user/{id}', UserAction::class);</code></pre>

<p class="mb-10 text-sm opacity-60">The <code>{id}</code> segment will match any value and be available as a route parameter.</p>

<h2 class="text-2xl mb-4">Route Groups</h2>

<p class="mb-4">Group routes under a shared prefix:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>$router->group('/admin', function (Router $router) {
    $router->get('/dashboard', DashboardAction::class);
    $router->get('/users', UsersAction::class);
});</code></pre>

<p class="mb-10 text-sm opacity-60">This registers <code>/admin/dashboard</code> and <code>/admin/users</code>.</p>

<div class="flex justify-between">
    <a href="/docs/usage" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; Usage</a>
    <a href="/docs/responders" class="text-sm underline underline-offset-4 hover:opacity-70">Next: Responders &rarr;</a>
</div>

<?php include views_dir() . '/partials/docs-layout-end.php'; ?>
