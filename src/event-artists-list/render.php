<div <?= get_block_wrapper_attributes() ?>>
    <?php foreach (get_field('listed_artists') as $post): ?>
        <?php $post = get_post($post); ?>
        <a class="artist_card" href="<?= get_permalink($post) ?>">
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="artist_details">
                <h2><?= $post->post_title ?></h2>
                <p><?= $post->post_excerpt ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>