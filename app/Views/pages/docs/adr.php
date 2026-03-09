<?php include views_dir() . '/partials/docs-layout-start.php'; ?>

<h1 class="text-3xl sm:text-4xl mb-4">What is ADR?</h1>
<p class="text-lg sm:text-xl mb-8 opacity-70">Action — Domain — Responder</p>

<div class="space-y-4 mb-10">
    <p>ADR is a design pattern that separates the concerns of handling requests, executing business logic, and returning responses.</p>
    <p>Unlike MVC, which was originally designed for desktop applications, ADR is purpose-built for the request/response cycle of the web.</p>
</div>

<div class="border px-4 sm:px-6 py-4 sm:py-5 mb-10">
    <p class="text-sm mb-3 font-bold">The three responsibilities</p>
    <ul class="space-y-2 text-sm">
        <li><strong>Action</strong> — Receives the request, coordinates the Domain and Responder. Contains no business logic.</li>
        <li><strong>Domain</strong> — All business logic lives here. Returns data, knows nothing about HTTP.</li>
        <li><strong>Responder</strong> — Formats the output. Renders a view or returns JSON. Knows nothing about the Domain.</li>
    </ul>
</div>

<h2 class="text-2xl mb-6">A complete example</h2>

<p class="mb-4">A user makes a <code>GET</code> request to <code>/path</code>. Here is the full journey through each layer.</p>

<h3 class="text-sm font-bold mb-2 opacity-50">1. Define the route</h3>
<pre class="mb-6 px-4 py-3 text-sm overflow-x-auto border"><code>$router->get('/path', PathAction::class);</code></pre>

<h3 class="text-sm font-bold mb-2 opacity-50">2. The Action receives the request</h3>
<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>namespace Actions;

use Domains\Path as PathDomain;
use Responders\Path as PathResponder;
use TetherPHP\framework\Interfaces\ActionInterface;
use TetherPHP\framework\Requests\Request;

class PathAction extends Action implements ActionInterface
{
    public function __construct(protected Request $request)
    {
        $this->domain = new PathDomain();
        $this->responder = new PathResponder($request);
    }

    public function __invoke(): string
    {
        $data = $this->domain->handle();
        return $this->respond($data);
    }
}</code></pre>
<p class="mb-6 text-sm opacity-60">The Action is thin by design. It wires the Domain to the Responder and nothing more.</p>

<h3 class="text-sm font-bold mb-2 opacity-50">3. The Domain handles the logic</h3>
<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>namespace Domains;

class Path extends Domain
{
    public function handle(): array
    {
        return [
            'name' => 'Path Domain',
            'description' => 'This is where business logic lives.',
        ];
    }
}</code></pre>
<p class="mb-6 text-sm opacity-60">The Domain returns data. It has no knowledge of HTTP, views, or responses.</p>

<h3 class="text-sm font-bold mb-2 opacity-50">4. The Responder formats the output</h3>
<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>namespace Responders;

class Path extends Responder
{
    public function __invoke(array $data = []): string
    {
        return $this->view('pages.path.index', $data);
    }
}</code></pre>
<p class="mb-10 text-sm opacity-60">The Responder takes the data and renders a view. It could just as easily return JSON.</p>

<div class="flex justify-end">
    <a href="/docs/requirements" class="text-sm underline underline-offset-4 hover:opacity-70">Next: Requirements &rarr;</a>
</div>

<?php include views_dir() . '/partials/docs-layout-end.php'; ?>
