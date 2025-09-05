<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>
<h2 class="title-blue">Orders</h2>
<?php if ( $has_orders ) : ?>

<ul class="account-orden-list">

	<?php foreach ( $customer_orders->orders as $customer_order ):
		$order = wc_get_order( $customer_order );
		$items_count = count( $order->get_items() );
		$item_count = $order->get_item_count() - $order->get_item_count_refunded();
	?>
	<li class="account-orden-list-item">
		<table>
			<tr>
				<td>
					<label class="account-orden-list-order-name">Order #<?=$order->get_order_number()?></label>
					<label for="" class="account-orden-list-date"><?=wc_format_datetime($order->get_date_created())?></label>
					<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="account-orden-list-view-more">View more</a>
				</td>
				<td>
					<b>Number</b><br />
					#<?=$order->get_order_number()?>
				</td>
				<td>
					<b>Status</b><br />
					<?=$order->get_status() ?>
				</td>
				<td>
					<b>Order notes</b><br />
					<?=$order->get_customer_note()?>
				</td>
				<td>
					<b>Total</b><br />
					<?php echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $items_count, 'woocommerce' ), $order->get_formatted_order_total(), $items_count ) ); ?>
				</td>
				<td>
					<?php
					$actions = wc_get_account_orders_actions( $order );

					if ( ! empty( $actions ) ) {
						 
						foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
							if($key=="edit-order"):
							echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
							endif;
						}
					}
					?>
				</td>
			</tr>
		</table>
	</li>
	<?php endforeach;?>
</ul>



	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<!-- <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div> -->
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php
				$args = array(
			    'base'          => esc_url( wc_get_endpoint_url( 'orders') ) . '%_%',
			    'format'        => '%#%',
			    'total'         => $customer_orders->max_num_pages,
			    'current'       => $current_page,
			    'show_all'      => false,
			    'end_size'      => 3,
			    'mid_size'      => 3,
			    'prev_next'     => true,
			    'prev_text'     => '',
			    // 'prev_text'     => '&larr;',
			    'next_text'     => '',
			    // 'next_text'     => '&rarr;',
			    'type'          => 'list',
			    'add_args'      => false,
			    'add_fragment'  => ''
			);
			echo paginate_links( $args );
			?>
		</div>
				<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info my-order-no-orders">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Browse products', 'woocommerce' ); ?>
		</a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
