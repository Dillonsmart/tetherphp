<?php view('partials/header'); ?>
    <div style="margin: 2rem 0;">
        <div class="heading-area">
            <div class="container">
                <h1>Responders</h1>
            </div>
        </div>

        <div class="container">
            <section>
                <h3>Returning a View</h3>
                <p>In TetherPHP, you can return a view directly from your action or route definition. This is useful for static pages or simple responses.</p>
                <pre><code>$router->view('/path', 'pages.home');</code></pre>
                <p>This will render the view located at <code>app/Views/pages/home.php</code>.</p>
                <p>Alternatively, from a Responder you can return views like so:</p>
                <pre><code>namespace Responders;

class ExampleResponder extends Responder
{
    public function __invoke(): string
    {
        return $this->response()->view('path.to.view');
    }
}
                    </code></pre>
                <h3>Returning JSON</h3>
                <p>To return a JSON response, you can use the <code>json</code> method of the response object. This is particularly useful for APIs or AJAX requests.</p>
                <pre><code>namespace Responders;

class Post extends Responder
{
    public function __invoke(): string
    {
        return $this->response()->json([
            'status' => 'success',
            'message' => 'Post request handled successfully',
            'data' => $this->request->payload
        ]);
    }
}</code></pre>
            </section>
        </div>
    </div>
<?php view('partials/footer'); ?>