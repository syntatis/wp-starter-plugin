<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use WPStarterPlugin\Vendor\Syntatis\WPHelpers\Enqueue\Enqueue;

use function WPStarterPlugin\Vendor\Syntatis\Utils\is_blank;

function get_plugin_basename(): string
{
	return plugin_basename(WP_STARTER_PLUGIN__FILE__);
}

/**
 * Retrieve the path to a file or directory within the plugin.
 *
 * @param string|null $path The path to a file or directory within the plugin e.g. 'dist', 'languages'.
 * @return string The full path to the file or directory, withtout the trailingslash e.g. '/wp-content/plugins/wp-starter-plugin/dist'.
 */
function get_plugin_directory_path(?string $path = null): string
{
	$path = trim($path, DIRECTORY_SEPARATOR);

	if (is_blank($path)) {
		return WP_STARTER_PLUGIN__DIR__;
	}

	return untrailingslashit(WP_STARTER_PLUGIN__DIR__ . DIRECTORY_SEPARATOR . $path);
}

/**
 * Retrieve the URL to a file or directory within the plugin.
 *
 * @param string|null $path The path to a file or directory within the plugin e.g. 'dist', 'languages'.
 * @return string The full URL to the file or directory, withtout the trailingslash e.g. 'https://example.com/wp-content/plugins/wp-starter-plugin/dist'.
 */
function get_plugin_directory_url(?string $path = null): string
{
	$dirUrl = plugin_dir_url(WP_STARTER_PLUGIN__FILE__);

	if (is_blank($path)) {
		return untrailingslashit($dirUrl);
	}

	return untrailingslashit($dirUrl . $path);
}

/**
 * Factory function to create a new `Enqueue` instance used for enqueuing
 * scripts and stylessheet.
 */
function enqueue(): Enqueue
{
	$enqueue = new Enqueue(
		get_plugin_directory_path('dist'),
		get_plugin_directory_url('dist'),
	);
	$enqueue->setPrefix(WP_STARTER_PLUGIN_NAME);
	$enqueue->setTranslations(WP_STARTER_PLUGIN_NAME, get_plugin_directory_path('languages'));

	return $enqueue;
}
