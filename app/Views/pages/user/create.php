<?php
/**
 * What Responders\User\Create hands this view — or what the
 * Responder for the write it submits to hands it, when the write is refused.
 *
 * @var string                $id          '' for a record that does not exist yet
 * @var array<string, mixed>  $attributes  what to show in the fields
 * @var array<string, string> $errors      field => message; empty when nothing is wrong
 */
$pageTitle = $id === '' ? 'New User' : 'Edit User ' . $id;
include views_dir() . '/partials/header.php';

/*
 * One form for both create and edit. A browser form can only send GET or POST,
 * so an update declares the verb it means in _method and Middleware\OverridesMethod
 * turns the POST into a PUT before routing. Leave that middleware out of
 * routes/middleware.php and this form posts to a route that does not exist.
 */
$action = $id === '' ? '/user' : '/user/' . $id;
?>

<main class="max-w-2xl mx-auto px-6 py-16">
    <p class="mb-6"><a href="/user" class="text-sm underline underline-offset-4 hover:opacity-70">&larr; All User</a></p>

    <h1 class="text-3xl mb-8"><?php echo htmlspecialchars($pageTitle); ?></h1>

    <form method="post" action="<?php echo htmlspecialchars($action); ?>" class="space-y-6">
        <?php if ($id !== ''): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

        <?php foreach ($attributes as $name => $value): ?>
            <div>
                <label for="<?php echo htmlspecialchars((string) $name); ?>" class="block mb-1">
                    <?php echo htmlspecialchars((string) $name); ?>
                </label>
                <input
                    id="<?php echo htmlspecialchars((string) $name); ?>"
                    name="<?php echo htmlspecialchars((string) $name); ?>"
                    value="<?php echo htmlspecialchars((string) $value); ?>"
                    class="w-full border px-3 py-2 bg-transparent"
                >
                <?php if (isset($errors[$name])): ?>
                    <p class="text-sm text-red-700 mt-1"><?php echo htmlspecialchars($errors[$name]); ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="border px-4 py-2 hover:opacity-70">Save</button>
    </form>
</main>

<?php include views_dir() . '/partials/footer.php'; ?>
