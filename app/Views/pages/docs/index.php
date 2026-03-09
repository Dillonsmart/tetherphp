<?php include views_dir() . '/partials/docs-layout-start.php'; ?>

<h1 class="text-3xl sm:text-4xl mb-4">Documentation</h1>
<p class="mb-10 opacity-70">TetherPHP is in its early days — the documentation and features are actively being developed.</p>

<div class="space-y-10">
    <?php foreach ($navigation as $section): ?>
        <div>
            <h2 class="text-lg font-bold mb-4"><?php echo $section['label']; ?></h2>
            <ul class="space-y-3">
                <?php foreach ($section['items'] as $item): ?>
                    <li>
                        <a href="<?php echo $item['href']; ?>" class="group block">
                            <span class="text-sm underline underline-offset-4 group-hover:opacity-70"><?php echo $item['label']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<?php include views_dir() . '/partials/docs-layout-end.php'; ?>
