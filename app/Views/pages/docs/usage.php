<?php include views_dir() . '/partials/docs-layout-start.php'; ?>

<h1 class="text-3xl sm:text-4xl mb-4">Usage</h1>
<p class="mb-10 opacity-70">TetherPHP is designed to be straightforward to use, with minimal dependencies.</p>

<h2 class="text-2xl mb-4" id="gettingStarted">Getting Started</h2>

<p class="mb-6">The recommended way to create a new project is with Composer:</p>

<pre class="mb-6 px-4 py-3 text-sm overflow-x-auto border"><code>composer create-project dillonsmart/tetherphp my-project</code></pre>

<p class="mb-6">Alternatively, clone the repository directly:</p>

<pre class="mb-8 px-4 py-3 text-sm overflow-x-auto border"><code>git clone https://github.com/Dillonsmart/tetherphp.git my-project</code></pre>

<h2 class="text-2xl mb-4">Configuration</h2>

<p class="mb-4">Copy the environment file and set your application name:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>cp .env.example .env</code></pre>

<p class="mb-8 text-sm opacity-60">The <code>.env</code> file contains <code>APP_NAME</code> and <code>APP_DEBUG</code>. No database or API keys required to get started.</p>

<h2 class="text-2xl mb-4">Installing Dependencies</h2>

<p class="mb-4">Generate the Composer autoload file:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>composer install</code></pre>

<p class="mb-8 text-sm opacity-60">TetherPHP has zero package dependencies. Composer is only used for autoloading.</p>

<h2 class="text-2xl mb-4" id="clearBoilerPlate">Clearing Boilerplate</h2>

<p class="mb-4">The skeleton ships with a home page to get you started. To remove it and start from scratch:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>php tether boilerplate:clear</code></pre>

<p class="mb-10 text-sm opacity-60">This removes the default Action, Domain, Responder, and View — giving you a blank canvas.</p>

<h2 class="text-2xl mb-4">Creating Features</h2>

<p class="mb-4">Use the CLI to scaffold a new ADR feature:</p>

<pre class="mb-4 px-4 py-3 text-sm overflow-x-auto border"><code>php tether make:feature Blog</code></pre>

<p class="mb-10 text-sm opacity-60">This creates <code>Actions/Blog.php</code>, <code>Domains/Blog.php</code>, and <code>Responders/Blog.php</code> — wired together and ready to use.</p>

<div class="flex justify-between">
    <a href="/docs/requirements" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; Requirements</a>
    <a href="/docs/routing" class="text-sm underline underline-offset-4 hover:opacity-70">Next: Routing &rarr;</a>
</div>

<?php include views_dir() . '/partials/docs-layout-end.php'; ?>
