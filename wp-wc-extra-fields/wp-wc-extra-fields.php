<?php
/**
 * Plugin Name:       wp-wc-extra-fields
 * Plugin URI:        https://github.com/FilipeOCastro/wp-wc-extra-fields
 * Description:       Add First Name, Last Name and CPF fields
 * Version:           1.1.2
 * Author:            Filipe Castro
 * Author URI:        http://filipecastro.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * GitHub Plugin URI: https://github.com/FilipeOCastro/wp-wc-extra-fields
 */

if ( ! defined( 'ABSPATH' ) ) { die; }

/**

	*Add First Name, Last Name and CPF fields for WooCommerce registration.
 
 */ 
function wp_wc_add_registration_fields() {
	?>
		<p class="form-row form-row-first">
			<label for="reg_billing_first_name">Nome <span class="required">*</span></label>
			<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" />
		</p>
		<p class="form-row form-row-last">
			<label for="reg_billing_last_name">Sobrenome <span class="required">*</span></label>
			<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name"  />
		</p>
		<p class="form-row form-row-cpf">
			<label for="reg_billing_cpf">CPF <span class="required">*</span></label>
			<input type="text" maxlength="14" placeholder="000.000.000-00" class="input-text" name="billing_cpf" id="reg_billing_cpf"  />
		</p>
	<?php
}
add_action( 'woocommerce_register_form_start', 'wp_wc_add_registration_fields' );


/**
 * Validate extra fields.
 
	* @param  username.
	* @param  email.
	* @param  $validation_errors WP_Error object. 
 
 */
function wp_wc_validate_extra_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		$validation_errors->add( 'billing_first_name_error', 'Digite o seu nome.' );
	}
	if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		$validation_errors->add( 'billing_last_name_error', 'Digite o seu sobrenome.' );
	}
	
	if ( isset( $_POST['billing_cpf'] ) && empty( $_POST['billing_cpf'] ) ) {
		$validation_errors->add( 'billing_cpf_error', 'Digite o seu CPF.' );
	}
	
}
add_action( 'woocommerce_register_post', 'wp_wc_validate_extra_fields', 9, 3 );

/**
 * Save fields.
 
	* @param  customer ID. 
	
 */
function wp_wc_save_extra_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		// WP first name
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
		// WC billing first name
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	}
	if ( isset( $_POST['billing_last_name'] ) ) {
		// WP last name
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		// WC billing last name
		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
	}	
}

add_action( 'woocommerce_created_customer', 'wp_wc_save_extra_fields' );