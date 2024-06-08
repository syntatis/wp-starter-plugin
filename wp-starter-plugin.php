<?php

declare(strict_types=1);

/**
 * Plugin bootstrap file.
 *
 * This file is read by WordPress to display the plugin's information in the admin area.
 *
 * @wordpress-plugin
 * Plugin Name:       WP Starter Plugin
 * Plugin URI:        https://github.com/syntatis/wp-starter-plugin
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Thoriq Firdaus
 * Author URI:        https://github.com/tfirdaus
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-starter-plugin
 * Domain Path:       /languages
 */

use WPStarterPlugin\Plugin;

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

/**
 * Define the current version of the plugin.
 *
 * Following Semantic Versioning ({@link https://semver.org}) is encouraged.
 * It provides a clear understanding of the impact of changes between
 * versions.
 */
define('WP_STARTER_PLUGIN_VERSION', '1.0.0');

/**
 * Define the name of the plugin.
 *
 * The plugin name serves as a unique identifier. It can be used in various
 * contexts, such as enqueueing scripts and styles, registering settings,
 * and more.
 */
define('WP_STARTER_PLUGIN_NAME', 'wp-starter-plugin');

/**
 * Define the directory path to the plugin file.
 *
 * This constant provides a convenient reference to the plugin's directory path,
 * useful for including or requiring files relative to this directory.
 */
define('WP_STARTER_PLUGIN__DIR__', __DIR__);

/**
 * Define the path to the plugin file.
 *
 * This path can be used in various contexts, such as managing the activation
 * and deactivation processes, loading the plugin text domain, adding action
 * links, and more.
 */
define('WP_STARTER_PLUGIN__FILE__', __FILE__);

/**
 * Load dependencies using the Composer autoloader.
 *
 * This allows us to leverage third-party libraries and packages without manually
 * including or requiring the files.
 *
 * This file is generated by PHP-Scoper ({@link https://github.com/humbug/php-scoper}),
 * a Composer plugin that scopes the namespaces of third-party libraries. This
 * prevents conflicts with other plugins or themes that might use the same
 * libraries or packages with the same namespaces or class names.
 *
 * @see https://getcomposer.org/doc/01-basic-usage.md#autoloading
 * @see https://deliciousbrains.com/php-scoper-namespace-composer-dependencies/
 */
require WP_STARTER_PLUGIN__DIR__ . '/dist-autoload/vendor/scoper-autoload.php';

$plugin = new Plugin();
$plugin->init();

load_plugin_textdomain(WP_STARTER_PLUGIN_NAME, false, dirname($plugin->getBasename()) . '/languages/');
