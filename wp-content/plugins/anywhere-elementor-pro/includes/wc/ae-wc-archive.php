<?php

namespace Aepro;

$frontend = Frontend::instance();


if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
return;
}
?>
<li <?php post_class(); ?>>
    <?php $frontend->apply_ae_wc_archive_template(); ?>
</li>