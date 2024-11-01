<?php
/* 
Plugin Name: WooCommerce - Multi-Bank
Plugin URI: http://www.basequatro.com
Description: WooCommerce Plugin for accepting payment through one more bank.
Author: Andre Carrano
Version: 1.0 
Author URI: http://www.basequatro.com
*/  

add_action('plugins_loaded', 'init_woocommerce_multibank', 0);

function init_woocommerce_multibank() {

class WC_Multibank extends WC_Payment_Gateway {

    /**
     * Constructor for the gateway.
     *
     * @access public
     * @return void
     */
    public function __construct() {
		$this->id				= 'multibank';
		$this->icon 			= apply_filters('woocommerce_multibank_icon', '');
		$this->has_fields 		= false;
		$this->method_title     = __( 'Deposit/Transfer', 'woomultibank' );
		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables
		$this->title 			= $this->settings['title'];
		$this->description      = $this->settings['description'];
		
		$this->title2			= $this->settings['title2'];
		$this->description2      = $this->settings['description2'];

		$this->title3 			= $this->settings['title3'];
		$this->description3      = $this->settings['description3'];
		
		$this->title4 			= $this->settings['title4'];
		$this->description4      = $this->settings['description4'];
		
		$this->title5 			= $this->settings['title5'];
		$this->description5      = $this->settings['description5'];		
		
		$this->account_name     = $this->settings['account_name'];
		$this->account_number   = $this->settings['account_number'];
		$this->sort_code        = $this->settings['sort_code'];
		$this->bank_name        = $this->settings['bank_name'];
		$this->iban             = $this->settings['iban'];
		$this->bic              = $this->settings['bic'];

		// Actions
		add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
    	add_action('woocommerce_thankyou_multibank', array(&$this, 'thankyou_page'));

    	// Customer Emails
    	add_action('woocommerce_email_before_order_table', array(&$this, 'email_instructions'), 10, 2);
    }


    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {

    	$this->form_fields = array(
			'enabled' => array(
							'title' => __( 'Enable/Disable', 'woomultibank' ),
							'type' => 'checkbox',
							'label' => __( 'Enable Bank Transfer', 'woomultibank' ),
							'default' => 'yes'
						),
			'title' => array(
							'title' => __( 'Title', 'woomultibank' ),
							'type' => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'woomultibank' ),
							'default' => __( 'Direct Bank Transfer', 'woomultibank' )
						),
			'description' => array(
							'title' => __( 'Customer Message', 'woomultibank' ),
							'type' => 'textarea',
							'description' => __( 'Give the customer instructions for paying via multibank, and let them know that their order won\'t be shipping until the money is received.', 'woomultibank' ),
							'default' => __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order wont be shipped until the funds have cleared in our account.', 'woomultibank')
						),
						
			'title2' => array(
				'title' => __( 'Title', 'woomultibank' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woomultibank' ),
				'default' => __( 'Direct Bank Transfer', 'woomultibank' )
			),
			'description2' => array(
				'title' => __( 'Customer Message', 'woomultibank' ),
				'type' => 'textarea',
				'description' => __( 'Give the customer instructions for paying via multibank, and let them know that their order won\'t be shipping until the money is received.', 'woomultibank' ),
				'default' => __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order wont be shipped until the funds have cleared in our account.', 'woomultibank')
			),		
			
			'title3' => array(
				'title' => __( 'Title', 'woomultibank' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woomultibank' ),
				'default' => __( 'Direct Bank Transfer', 'woomultibank' )
			),
			'description3' => array(
				'title' => __( 'Customer Message', 'woomultibank' ),
				'type' => 'textarea',
				'description' => __( 'Give the customer instructions for paying via multibank, and let them know that their order won\'t be shipping until the money is received.', 'woomultibank' ),
				'default' => __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order wont be shipped until the funds have cleared in our account.', 'woomultibank')
			),				


			'title4' => array(
							'title' => __( 'Title', 'woomultibank' ),
							'type' => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'woomultibank' ),
							'default' => __( 'Direct Bank Transfer', 'woomultibank' )
						),
			'description4' => array(
							'title' => __( 'Customer Message', 'woomultibank' ),
							'type' => 'textarea',
							'description' => __( 'Give the customer instructions for paying via multibank, and let them know that their order won\'t be shipping until the money is received.', 'woomultibank' ),
							'default' => __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order wont be shipped until the funds have cleared in our account.', 'woomultibank')
						),
						
			'title5' => array(
				'title' => __( 'Title', 'woomultibank' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woomultibank' ),
				'default' => __( 'Direct Bank Transfer', 'woomultibank' )
			),
			'description5' => array(
				'title' => __( 'Customer Message', 'woomultibank' ),
				'type' => 'textarea',
				'description' => __( 'Give the customer instructions for paying via multibank, and let them know that their order won\'t be shipping until the money is received.', 'woomultibank' ),
				'default' => __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order wont be shipped until the funds have cleared in our account.', 'woomultibank')
			),

			);

    }


	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @access public
	 * @return void
	 */
	public function admin_options() {
    	?>
    	<h3><?php _e('Bank Payment', 'woomultibank'); ?></h3>
    	<p><?php _e('Allows payments by multibank (Bank Account Clearing System), more commonly known as direct bank/wire transfer.', 'woomultibank'); ?></p>
    	<table class="form-table">
    	<?php
    		// Generate the HTML For the settings form.
    		$this->generate_settings_html();
    	?>
		</table><!--/.form-table-->
    	<?php
    }


    /**
     * Output for the order received page.
     *
     * @access public
     * @return void
     */
    function thankyou_page() {
		if ( $description = $this->get_description() )
        	echo wpautop( wptexturize( $description ) );

		?><h2><?php _e('Our Details', 'woomultibank') ?></h2><ul class="order_details multibank_details"><?php

		$fields = apply_filters('woocommerce_multibank_fields', array(
			'title' 	=> __('Title', 'woomultibank'),
			'description'=> __('Description', 'woomultibank'),
			'title2' 	=> __('Title', 'woomultibank'),
			'description2'=> __('Description', 'woomultibank'),
			'title3' 	=> __('Title', 'woomultibank'),
			'description3'=> __('Description', 'woomultibank'),			
			'title4' 	=> __('Title', 'woomultibank'),
			'description4'=> __('Description', 'woomultibank'),
			'title5' 	=> __('Title', 'woomultibank'),
			'description5'=> __('Description', 'woomultibank'),
		));

		foreach ($fields as $key=>$value) :
		    if(!empty($this->$key)) :
		    	echo '<li class="'.$key.'">'.$value.': <strong>'.wptexturize($this->$key).'</strong></li>';
		    endif;
		endforeach;

		?></ul><?php
    }


    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @return void
     */
    function email_instructions( $order, $sent_to_admin ) {

    	if ( $sent_to_admin ) return;

    	if ( $order->status !== 'on-hold') return;

    	if ( $order->payment_method !== 'multibank') return;

		if ( $description = $this->get_description() )
        	echo wpautop( wptexturize( $description ) );

		?><h2><?php _e('Our Details', 'woomultibank') ?></h2><ul class="order_details multibank_details"><?php

		$fields = apply_filters('woocommerce_multibank_fields', array(
			'title' 	=> __('Title', 'woomultibank'),
			'description'=> __('Description', 'woomultibank'),
			'title2' 	=> __('Title', 'woomultibank'),
			'description2'=> __('Description', 'woomultibank'),
			'title3' 	=> __('Title', 'woomultibank'),
			'description3'=> __('Description', 'woomultibank'),			
			'title4' 	=> __('Title', 'woomultibank'),
			'description4'=> __('Description', 'woomultibank'),
			'title5' 	=> __('Title', 'woomultibank'),
			'description5'=> __('Description', 'woomultibank'),
		));

		foreach ($fields as $key=>$value) :
		    if(!empty($this->$key)) :
		    	echo '<li class="'.$key.'">'.$value.': <strong>'.wptexturize($this->$key).'</strong></li>';
		    endif;
		endforeach;

		?></ul><?php
    }


    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    function process_payment( $order_id ) {
    	global $woocommerce;

		$order = new WC_Order( $order_id );

		// Mark as on-hold (we're awaiting the payment)
		$order->update_status('on-hold', __('Awaiting Bank payment', 'woomultibank'));

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		$woocommerce->cart->empty_cart();

		// Empty awaiting payment session
		unset($_SESSION['order_awaiting_payment']);

		// Return thankyou redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order->id, get_permalink(woocommerce_get_page_id('thanks'))))
		);
    }

}


/**
 * Add the gateway to WooCommerce
 *
 * @access public
 * @param array $methods
 * @package		WooCommerce/Classes/Payment
 * @return array
 */
function add_multibank_gateway( $methods ) {
	$methods[] = 'WC_Multibank';
	return $methods;
}

add_filter('woocommerce_payment_gateways', 'add_multibank_gateway' );

function add_textdomain_woomultibank() {
load_plugin_textdomain( 'woomultibank', false, 'woomultibank/languages' );
}
add_action( 'init', 'add_textdomain_woomultibank' );

}



