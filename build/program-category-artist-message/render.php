<?php if (!empty($block->context['hw/artist-info']['message'])): ?>
    <div <?= get_block_wrapper_attributes() ?>>
        <?= $block->context['hw/artist-info']['message']?>
    </div>
<?php endif; ?>