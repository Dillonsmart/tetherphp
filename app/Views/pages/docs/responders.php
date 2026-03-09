<?php include views_dir() . '/partials/docs-layout-start.php'; ?>

<h1 class="text-3xl sm:text-4xl mb-4">Responders</h1>
<p class="mb-10 opacity-70">The Responder is the final step in the ADR cycle. It takes data from the Domain and formats it as HTML or JSON.</p>

<h2 class="text-2xl mb-4">Returning a View</h2>

<p class="mb-4">From a Responder, render a view and pass data to it:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>namespace Responders;

class Blog extends Responder
{
    public function __invoke(array $data = []): string
    {
        return $this->view('pages.blog.index', $data);
    }
}</code></pre>

<p class="mb-10 text-sm opacity-60">This renders <code>app/Views/pages/blog/index.php</code>. The <code>$data</code> array is extracted — each key becomes a variable available in the view.</p>

<h2 class="text-2xl mb-4">Returning JSON</h2>

<p class="mb-4">For APIs or AJAX endpoints, return a JSON response:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>namespace Responders;

class ApiStatus extends Responder
{
    public function __invoke(array $data = []): string
    {
        return $this->json($data);
    }
}</code></pre>

<p class="mb-4 text-sm opacity-60">This sets the <code>Content-Type</code> header to <code>application/json</code> and returns the encoded data.</p>

<p class="mb-10">You can also set a custom status code:</p>

<pre class="mb-10 px-4 py-3 text-sm overflow-x-auto border"><code>return $this->json(['error' => 'Not found'], 404);</code></pre>

<h2 class="text-2xl mb-4">View Routes (without a Responder)</h2>

<p class="mb-4">For simple static pages that don't need data, skip the Responder entirely and use a view route:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>$router->view('/about', 'pages.about');</code></pre>

<p class="mb-10 text-sm opacity-60">This renders the view directly from the router — no Action, Domain, or Responder needed.</p>

<div class="flex justify-start">
    <a href="/docs/routing" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; Routing</a>
</div>

<?php include views_dir() . '/partials/docs-layout-end.php'; ?>
