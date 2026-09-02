<?php
$pageTitle = $appName;
include views_dir() . '/partials/header.php';
?>

<main class="max-w-2xl mx-auto px-6 py-16">
    <h1 class="text-3xl mb-3"><?php echo htmlspecialchars($appName); ?></h1>
    <p class="mb-10 opacity-70"><?php echo htmlspecialchars($tagline); ?></p>

    <p class="mb-4">This page is <code class="text-sm">app/Views/pages/home/index.php</code>, rendered by the
        <code class="text-sm">Home</code> action, domain and responder in <code class="text-sm">app/</code>.</p>

    <p>Routes are defined in <code class="text-sm">routes/web.php</code>. Run
        <code class="text-sm">php tether help</code> to see what you can generate.</p>
</main>

<?php include views_dir() . '/partials/footer.php'; ?>
