<?php view('partials/header'); ?>
    <div style="margin: 2rem 0;">
        <div class="heading-area">
            <div class="container">
                <h1>What is ADR?</h1>
            </div>
        </div>

        <div class="container">
            <section>
                <h3><span class="font-bold">A</span>ction - <span class="font-bold">D</span>omain - <span>R</span>esponder</h3>
                <p>ADR is a design pattern that separates the concerns of handling requests, executing business logic, and returning responses.</p>
                <p>Unlike MVC, which was originally designed for desktop applications, ADR is designed for the web.</p>
                <p>In ADR, the <span class="font-bold">Action</span> handles the incoming request, the <span class="font-bold">Domain</span> contains the business logic, and the <span class="font-bold">Responder</span> formats the response.</p>
            </section>
            <section>
                <h3>ADR Example</h3>
                <p>Here, a user makes a GET request to the route <span class="font-bold">/path</span>.</p>
                <p class="font-bold">web.php</p>
                <pre><code>$router->get('/path', PathAction::class);</code></pre>
                <p>When making a GET request to this route, the action is invoked.</p>
                <p class="font-bold">app/Actions/PathAction.php</p>
                <pre><code>namespace Actions;

use Domains\PathDomain;
use Responders\PathResponder as Responder;
use TetherPHP\framework\Requests\Request;

class PathAction extends Action
{
    public PathDomain $domain;

    public function __construct(protected Request $request)
    {
        $this->responder = new Responder($request);
        $this->domain = new PathDomain();
    }

    public function __invoke()
    {
        return ($this->responder)($this->domain->getData());
    }
}</code></pre>
                <p>In this example, you will notice the Action only has one purpose, which is to take the Domain logic and pass it to the Responder.</p>
                <p class="font-bold">app/Domains/PathDomain.php</p>
                <pre><code>namespace Domains;

use DTOs\PathDTO;

class PathDomain extends Domain
{
    public function getData(): PathDTO
    {
        $dto = new PathDTO();
        $dto->name = 'Path Domain';
        $dto->description = 'This domain handles all the business logic.';

        return $dto;
    }
}</code></pre>
                <p>The Domain is where the business logic exists, in this example we create a new DTO (Data Transfer Object) and return it.</p>
                <p class="font-bold">app/Responders/PathResponder.php</p>
                <pre><code>namespace Responders;

use DTOs\PathDTO;

class PathResponder extends Responder
{
    public function __invoke(PathDTO $data): string
    {
        return $this->response()->view('path.to.view', [
            'name' => $data->name,
            'description' => $data->description,
        ]);
    }
}</code></pre>
                <p>The Responder only returns HTML or JSON. In this example, the Responder was passed the DTO from the Action. The data is sent to the View for rendering.</p>
            </section>
        </div>
    </div>
<?php view('partials/footer'); ?>