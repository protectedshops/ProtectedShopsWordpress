<?php

	// Remove WooCommerce enqueued styles
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

	// Remove WooCommerce prettyPhoto
	add_action( 'wp_enqueue_scripts', 'keydesign_remove_woo_scripts', 99 );
	function keydesign_remove_woo_scripts() {
	    wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
	    wp_dequeue_script( 'prettyPhoto' );
	    wp_dequeue_script( 'prettyPhoto-init' );
	}

	// Return 3 related products
	add_filter( 'woocommerce_output_related_products_args', 'keydesign_related_woo_scripts' );
	function keydesign_related_woo_scripts( $args ) {
	$args['posts_per_page']     = 3;
	$args['columns']            = 3;
	return $args;
	}

	// Return 9 products in shop page
	add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 8;' ), 20 );

	function keydesign_enqueue_woocommerce() {
		wp_enqueue_style( 'keydesign-woocommerce', get_template_directory_uri() . '/core/assets/css/woocommerce.css', array(), null, 'all' );
		wp_register_script( 'keydesign-ajaxcart', get_template_directory_uri() . '/core/assets/js/woocommerce-keydesign.js', array() , null );

		wp_localize_script(
			'keydesign-ajaxcart',
			'keydesign_menucart_ajax',array('nonce' => wp_create_nonce('keydesign-ajaxcart'))
		);
		wp_enqueue_script( 'keydesign-ajaxcart' );
	}

	add_action('wp_enqueue_scripts', 'keydesign_enqueue_woocommerce');

	function keydesign_get_cart_items() {
		global $woocommerce;
		$articles = sizeof( $woocommerce->cart->get_cart() );
		$cart = $items_total = '';

		if (  $articles > 0 ) {
			$items_total = $woocommerce->cart->cart_contents_count;
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', $woocommerce->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					$cart .= '<li class="cart-item-list clearfix">';
					if ( ! $_product->is_visible() ) {
						$cart .= str_replace( array( 'http:', 'https:' ), '', $thumbnail );
					} else {
						$cart .= '<a class="cart-thumb" href="'.esc_url(get_permalink( $product_id )).'">
									'.str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '
								</a>';
					}
					$cart .= '<div class="cart-desc"><a class="cart-item" href="'.esc_url(get_permalink( $product_id )).'">' . $product_name . '</a>';
					$cart .= '<span class="product-quantity">'. apply_filters( 'woocommerce_widget_cart_item_quantity',  '<span class="quantity-container">' . sprintf( '%s &times; %s',$cart_item['quantity'] , '</span>' . $product_price ) , $cart_item, $cart_item_key ) . '</span>';
					$cart .= '</div>';
					$cart .= '</li>';
				}
			}
			$cart .= '<li class="subtotal"><span><strong>' . esc_html__('Subtotal:', 'intact') . '</strong> ' . $woocommerce->cart->get_cart_total() . '</span></li>';
			$cart .= '<li class="buttons clearfix">
						<a href="'.wc_get_cart_url().'" class="wc-forward btn btn-link"><i class="fa fa-bag"></i>'.esc_html__( 'View Cart', 'intact' ).'</a>
						<a href="'.wc_get_checkout_url().'" class="checkout wc-forward btn btn-link"><i class="fa fa-square-check"></i>'.esc_html__( 'Checkout', 'intact' ).'</a>
					  </li>';
		}
		return array('cart' => $cart, 'articles' => $items_total);
	}

	function keydesign_woomenucart_ajax() {
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "keydesign-ajaxcart")) {
				exit();
		}
		$cart = keydesign_get_cart_items();
		echo json_encode($cart);
		die();
	}

	add_action( 'wp_ajax_woomenucart_ajax', 'keydesign_woomenucart_ajax');
	add_action( 'wp_ajax_nopriv_woomenucart_ajax', 'keydesign_woomenucart_ajax' );

	function keydesign_add_cart_in_menu() {
		global $woocommerce;

		$items_total = $woocommerce->cart->cart_contents_count;
		$get_cart_items = keydesign_get_cart_items();

		$cart_container = '<ul role="menu" class="drop-menu cart_list product_list_widget keydesign-cart-dropdown">'.((isset($get_cart_items['cart']) && $get_cart_items['cart'] !=='') ? $get_cart_items['cart'] : '<li><span class="empty-cart">' . esc_html__('Your cart is currently empty','intact') . '</span></li>').'</ul>';
		$kd_cart_items = '<div class="keydesign-cart menu-item menu-item-has-children dropdown">
					      <a href="'.wc_get_cart_url().'" class="dropdown-toggle" title="cart">
						  <span class="cart-icon-container">';
		$kd_cart_items .= '<i class="fa nc-icon-outline-cart"></i>';
		$kd_cart_items .= (( $items_total !== 0 ) ? '<span class="badge">'.$items_total.'</span>' : '<span class="badge" style="display: none;"></span>').'</span></a>'.$cart_container.'</div>';
		return $kd_cart_items;
	}


	function keydesign_wc_loop_add_to_cart_scripts() {
    if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) : ?>

	<script>
		jQuery( document ).ready( function( $ ) {
			$( document ).on( 'change', '.quantity .qty', function() {
				$( this ).parent( '.quantity' ).next( '.add_to_cart_button' ).attr( 'data-quantity', $( this ).val() );
			});
		});
	</script>
	    <?php endif;
	}

	add_action( 'wp_footer', 'keydesign_wc_loop_add_to_cart_scripts' );
?>
