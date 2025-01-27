<div <?= get_block_wrapper_attributes() ?>>
    <?php foreach (get_field('associated_events') as $post): ?>
        <?php $post = get_post($post); ?>
        <a class="event_card" href="<?= get_permalink($post) ?>">
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="event_details">
                <p class="event_name"><?= $post->post_title ?></p>
                <p class="event_description"><?= get_the_excerpt($post) ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>