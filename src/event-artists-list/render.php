<div <?= get_block_wrapper_attributes() ?>>
    <?php foreach (get_field('listed_artists') as $post): ?>
        <?php $post = get_post($post); ?>
        <a class="artist_card" href="<?= get_permalink($post) ?>">
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="artist_details">
                <p class="artist_name"><?= $post->post_title ?></p>
                <p class="artist_bio"><?= get_the_excerpt($post) ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>