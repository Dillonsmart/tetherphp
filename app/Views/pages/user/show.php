<?php
/**
 * What Responders\User\Show hands this view.
 *
 * @var string               $id
 * @var array<string, mixed> $attributes
 */
$pageTitle = 'User ' . $id;
include views_dir() . '/partials/header.php';
?>

<main class="max-w-2xl mx-auto px-6 py-16">
    <p class="mb-6"><a href="/user" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; All User</a></p>

    <h1 class="text-3xl mb-8">User <?php echo htmlspecialchars($id); ?></h1>

    <dl class="space-y-4 mb-10">
        <?php foreach ($attributes as $name => $value): ?>
            <div>
                <dt class="text-sm opacity-60"><?php echo htmlspecialchars((string) $name); ?></dt>
                <dd><?php echo htmlspecialchars((string) $value); ?></dd>
            </div>
        <?php endforeach; ?>
    </dl>

    <div class="flex items-center gap-6">
        <a href="/user/<?php echo htmlspecialchars($id); ?>/edit" class="underline underline-offset-4 hover:opacity-70">Edit</a>

        <?php
        /*
         * A browser form sends GET or POST. The hidden _method field asks for
         * DELETE, and Middleware\OverridesMethod in routes/middleware.php
         * rewrites the verb before routing. Leave that middleware out and this
         * form posts to a route that does not exist.
         */
        ?>
        <form method="post" action="/user/<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <button type="submit" class="underline underline-offset-4 hover:opacity-70">Delete</button>
        </form>
    </div>
</main>

<?php include views_dir() . '/partials/footer.php'; ?>
