<div <?= get_block_wrapper_attributes() ?>>
    <?php foreach (get_field('listed_artists') as $post): ?>
        <?php $post = get_post($post); ?>
        <div class="artist_card">
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="artist_details" >
                <a href="<?= get_permalink($post) ?>"><h2><?= $post->post_title ?></h2></a>
                <h3><?= get_field("tagline", $post->ID) ?></h3>
                <p ><?= $post->post_excerpt ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
