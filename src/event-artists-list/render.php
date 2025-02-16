<div <?= get_block_wrapper_attributes() ?>>
    <?php foreach (get_field('listed_artists') as $artist_post): ?>
        <?php $artist_post = get_post($artist_post); ?>
        <div class="artist_card" href="<?= get_permalink($artist_post) ?>">
            <a class="card_anchor" href="<?= get_permalink($artist_post) ?>"></a>

            <div class="link_stack">
                <?php if (has_post_thumbnail($artist_post)): ?>
                    <img src=<?= get_the_post_thumbnail_url($artist_post) ?> />
                <?php endif; ?>

                <?php
                // Links
                $links = get_field('links', $artist_post->ID) ?? '';
                $links = trim($links);

                $split_links = explode("\n", $links);

                if (!empty($split_links) && $links !== '') {
                    echo "<div class='links'>";
                    foreach ($split_links as $link) {
                        $split_link = explode("|", $link);

                        if (count($split_link) !== 2) {
                            $label = trim(str_replace('https://', '', str_replace('http://', '', $link)));
                            $link = trim($link);
                            echo "<a class='link' href='{$link}' target='_blank'>{$label}</a>";
                            continue;
                        }

                        $link_url = trim($split_link[1]);
                        $link_label = trim($split_link[0]);
                        echo "<a class='link' href='{$link_url}' target='_blank'>{$link_label}</a>";
                    }
                    echo "</div>";
                }
                ?>
            </div>


            <div class="artist_details">
                <p class="artist_name"><?= $artist_post->post_title ?></p>
                <p class="artist_bio"><?= get_the_content($artist_post->ID) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>