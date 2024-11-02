<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

 $posts = get_posts(array("post_type" => "Event"));
 $post_info = array_map(function($post) {
    $post_meta = get_post_meta($post->ID);
    return array(
        "id" => $post->ID,
        "title" => $post->post_title,
        "date" => array($post_meta["date"][0], $post_meta["end_date"][0]),
        "time" => array($post_meta["time"][0], $post_meta["end_time"][0]),
    );
 }, $posts);
?>
<?php echo $content; ?>
<script type="text/javascript">
    const event_JSON = JSON.parse('<?php echo json_encode($post_info); ?>');
</script>
