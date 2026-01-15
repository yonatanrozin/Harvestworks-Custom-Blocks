<?php 

    $today = date('Ymd');

    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => 3,
        'meta_key'       => 'date',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ),
        ),
    );
    $events = get_posts($args);
    $gap = get_field("gap");
?>

<?php if (!$is_preview): ?><div <?= get_block_wrapper_attributes() ?>><?php endif; ?>
    <?php if ($is_preview): ?>
        <InnerBlocks 
            template="<?php echo esc_attr(wp_json_encode([
                ['core/template-part', ["slug" => "upcoming-event"]]
            ])); ?>"
            templateLock="true"
        />
    <?php endif; ?>
            
    <?php foreach ($events as $event): ?>
        <?php 
            global $post;
            $post = $event;
            setup_postdata($post);
            block_template_part("upcoming-event");    
        ?>
    <?php endforeach; wp_reset_postdata(); ?>
<?php if (!$is_preview): ?></div><?php endif; ?>
<style>
    .wp-block-hw-upcoming-events {
        gap: <?= $gap ?>rem;
    }
</style>