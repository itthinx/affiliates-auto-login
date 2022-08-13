<?php
/**
 * affiliates-auto-login.php
 *
 * Copyright (c) 2022 www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author itthinx
 * @package affiliates-auto-login
 * @since affiliates-recaptcha 1.0.0
 *
 * Plugin Name: Affiliates Auto Login
 * Plugin URI: https://github.com/itthinx/affiliates-auto-login
 * Description: Automatically log new affiliates in after registration, for <a href="https://wordpress.org/plugins/affiliates/">Affiliates</a>, <a href="https://www.itthinx.com/shop/affiliates-pro/">Affiliates Pro</a> and <a href="https://wordpress.org/plugins/affiliates-enterprise/">Affiliates Enterprise</a>.
 * Version: 1.0.0
 * Author: itthinx
 * Author URI: https://www.itthinx.com
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Automatic Login
 */
class Affiliates_Auto_Login {

	/**
	 * Adds action and filter hooks.
	 */
	public static function init() {
		//
		// We hook into the action that fires after an affiliate account has been registered
		//
		// Note: We do not hook on 'affiliates_after_register_affiliate' to do this, as the affiliate entry is not related to the user account yet.
		//
		add_action( 'affiliates_stored_affiliate', array( __CLASS__, 'affiliates_stored_affiliate' ), 10, 2 );
	}


	public static function affiliates_stored_affiliate( $affiliate_id, $affiliate_user_id ) {
		if ( !is_user_logged_in() ) {
			wp_set_current_user( $affiliate_user_id );
			wp_set_auth_cookie( $affiliate_user_id, true );
			if ( apply_filters( 'affiliates_auto_login_redirect', true ) ) {
				// We just redirect to the current URL, expecting to get directly to the affiliate dashboard after registration, instead of staying on the page with the thanks etc. message
				$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$parsed = parse_url( $current_url );
				$redirect_url = $parsed['scheme'] . '://' . $parsed['host'] . $parsed['path'];

				// We could also redirect to a specific section of the dashboard, for example to the overview:
				// $dashboard = new Affiliates_Dashboard();
				// $redirect_url = $dashboard->get_url( array( Affiliates_Dashboard::SECTION_URL_PARAMETER => Affiliates_Dashboard_Overview::get_key() ) );

				// Allow to alter the redirect URL
				$redirect_url = apply_filters( 'affiliates_auto_login_redirect_url', $redirect_url );

				if ( !empty( $redirect_url ) ) {
					wp_redirect( $redirect_url );
					exit;
				}
			}
		}
	}

}

Affiliates_Auto_Login::init();
