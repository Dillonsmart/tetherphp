<?php
/**
 * What Responders\User\Index hands this view.
 *
 * @var list<array<string, mixed>> $records
 * @var array<string, mixed>       $query
 */
$pageTitle = 'User';
include views_dir() . '/partials/header.php';
?>

<main class="max-w-2xl mx-auto px-6 py-16">
    <div class="flex items-baseline justify-between mb-10">
        <h1 class="text-3xl">User</h1>
        <a href="/user/create" class="underline underline-offset-4 hover:opacity-70">New User</a>
    </div>

    <?php if ($records === []): ?>
        <p class="opacity-70">Nothing here yet.</p>
    <?php else: ?>
        <ul class="space-y-4">
            <?php foreach ($records as $record): ?>
                <li>
                    <a href="/user/<?php echo htmlspecialchars((string) ($record['id'] ?? '')); ?>" class="underline underline-offset-4 hover:opacity-70">
                        <?php echo htmlspecialchars((string) ($record['id'] ?? '')); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p class="mt-10 text-sm opacity-60">
        <code>$records</code> and <code>$query</code> are named by
        <code>Responders\User\Index</code>. <code>$query</code> is the parsed
        query string, so this is where paging and filtering read their input from.
    </p>
</main>

<?php include views_dir() . '/partials/footer.php'; ?>
