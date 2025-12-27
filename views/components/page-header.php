<?php
/**
 * Reusable Page Header Component
 * Displays a gradient hero section with title and lead text
 * 
 * @param string $title - Page title
 * @param string $lead - Lead text/subtitle
 */
?>

<section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-6"><?= $title ?></h1>
        <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
            <?= $lead ?>
        </p>
    </div>
</section>