<?php themeInclude('header'); ?>

<section class="post">
    <h1><?= postTitle(); ?></h1>
    <article>
        <?= postContent(); ?>
    </article>
</section>

<section class="comments">
    ZE COMMENTS
</section>

<?php themeInclude('footer'); ?>