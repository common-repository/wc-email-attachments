<?php
/**
 * Plugin Name:          Email Attachments for WooCommerce
 * Plugin URI:           https://pluginever.com/wc-email-attachments/
 * Description:          Email Attachments for WooCommerce enables the attachment of single or multiple files to any WooCommerce email template.
 * Version:              1.0.1
 * Author:               PluginEver
 * Author URI:           https://pluginever.com/
 * License:              GPL-2.0-or-later
 * License URI:          https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:          wc-email-attachments
 * Domain Path:          /languages
 * Requires at least:    5.0
 * Requires PHP:         7.4
 * Tested up to:         6.6
 * WC requires at least: 3.0.0
 * WC tested up to:      9.3
 * Requires Plugins:     woocommerce
 *
 * @package EAFW
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

use EAFW\Plugin;

// Don't call the file directly.
defined( 'ABSPATH' ) || exit();

// Optimized autoload class.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Plugin compatibility with WooCommerce HPOS.
 *
 * @since 1.0.0
 * @return void
 */
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}
);

/**
 * Get the plugin instance.
 *
 * @since 1.0.0
 * @return Plugin
 */
function eafw_email_attachments() {
	return Plugin::create( __FILE__, '1.0.1' );
}

// Initialize the plugin.
eafw_email_attachments();
