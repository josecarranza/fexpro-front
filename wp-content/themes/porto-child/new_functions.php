<?php

function kbnt_my_account_orders( $args ) {
    $args['posts_per_page'] = 10; // add number or -1 (display all orderes per page)
    return $args;
}
add_filter( 'woocommerce_my_account_my_orders_query', 'kbnt_my_account_orders', 10, 1 );



// add_filter( 'woocommerce_get_order_item_totals', 'remove_subtotal_from_orders_total_lines', 100, 1 );
// function remove_subtotal_from_orders_total_lines( $totals ) {
//     unset($totals['cart_subtotal']  );
//     return $totals;
// }