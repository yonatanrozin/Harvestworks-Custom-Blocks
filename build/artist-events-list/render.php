<div <?= get_block_wrapper_attributes() ?>>

    <?php 

        $posts = get_posts(array(
            'post_type' => 'event',
            'meta_query' => array(
                array(
                    'key' => 'listed_artists', // name of custom field
                    'value' => '"' . get_the_ID() . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
                    'compare' => 'LIKE'
                )
            )
        ));

    ?>

    <?php foreach ($posts as $post): ?>
        <div class="event_card" >
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="event_details" >
                <a href="<?= get_permalink($post) ?>"><h4 class="event_title"><?= $post->post_title ?></h4></a>
                <h5 class="event_tagline"><?= get_field("tagline", $post->ID) ?></h5>
                <!-- <p ><?= $post->post_excerpt ?></p> -->
            </div>
        </div>
    <?php endforeach; ?>
</div>
