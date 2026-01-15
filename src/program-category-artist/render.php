<?php if (!empty($block->context['hw/artist-info'])): ?>
    <?php 
        $template = $block->parsed_block;

        $artist_ids = $block->context['hw/artist-info']['artists'];
    ?>
    <?php foreach ($artist_ids as $id): ?>
        <?php 
            global $post;
            $post = get_post($id);
            setup_postdata($post);

            $child_block = new WP_Block($template);
        ?>
        <?= $child_block->render(["dynamic" => false]); ?>
    <?php endforeach; wp_reset_postdata(); ?>
<?php endif; ?>
