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
		add_action( 'affiliates_after_register_affiliate', array( __CLASS__, 'affiliates_after_register_affiliate' ) );
	}

	/**
	 * Automatic login after registration
	 */
	public static function affiliates_after_register_affiliate( $userdata ) {
		if ( isset( $userdata['user_login'] ) ) {
			$user_login = sanitize_user( $userdata['user_login'] );
			$user = get_user_by( 'login', $user_login );
			if ( $user instanceof WP_User ) {
				if ( !empty( $user->ID ) ) {
					wp_set_current_user( $user->ID );
					wp_set_auth_cookie( $user->ID, true );
				}
			}
		}
	}

}

Affiliates_Auto_Login::init();
