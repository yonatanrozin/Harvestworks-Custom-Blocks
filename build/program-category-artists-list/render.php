<?php 

    $term = get_queried_object();

    $artists = get_field("artists", $term); 

    $template = $block->parsed_block;
?>
<?php if (!empty($artists)): ?>
    <?php foreach ($artists as $artist): ?>
        <?php 
            $item = new WP_Block( $template, ["hw/artist-info" => $artist] );
        ?>
        <?= $item->render( array( 'dynamic' => false ) ) ?>
    <?php endforeach; ?>
<?php endif; ?>