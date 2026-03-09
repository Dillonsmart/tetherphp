<?php view('partials/header'); ?>

<div class="flex min-h-screen">
    <aside class="w-64 shrink-0 border-r px-6 py-8 hidden lg:block">
        <a href="/docs" class="text-sm font-bold mb-6 block">Documentation</a>
        <?php foreach ($navigation as $section): ?>
            <div class="mb-6">
                <h4 class="text-xs font-bold uppercase tracking-wider mb-2 opacity-50"><?php echo $section['label']; ?></h4>
                <ul class="space-y-1">
                    <?php foreach ($section['items'] as $item): ?>
                        <?php
                            $slug = basename($item['href']);
                            $isActive = $slug === $currentPage;
                        ?>
                        <li>
                            <a href="<?php echo $item['href']; ?>"
                               class="block text-sm py-1 <?php echo $isActive ? 'font-bold underline underline-offset-4' : 'opacity-60 hover:opacity-100'; ?>">
                                <?php echo $item['label']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </aside>

    <div class="flex-1 min-w-0">
        <nav class="lg:hidden px-4 py-4 border-b overflow-x-auto">
            <ul class="flex gap-4 text-sm whitespace-nowrap">
                <?php foreach ($navigation as $section): ?>
                    <?php foreach ($section['items'] as $item): ?>
                        <?php
                            $slug = basename($item['href']);
                            $isActive = $slug === $currentPage;
                        ?>
                        <li>
                            <a href="<?php echo $item['href']; ?>"
                               class="<?php echo $isActive ? 'font-bold underline underline-offset-4' : 'opacity-60'; ?>">
                                <?php echo $item['label']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </nav>

        <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-8 max-w-3xl">
