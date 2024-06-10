<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use WPStarterPlugin\Vendor\Syntatis\WPHelpers\Enqueue\Enqueue;

use function trim;
use function WPStarterPlugin\Vendor\Syntatis\Utils\is_blank;

use const DIRECTORY_SEPARATOR;

function plugin_basename(): string
{
	return plugin_basename(WP_STARTER_PLUGIN__FILE__);
}

/**
 * Retrieve the path to a file or directory within the plugin.
 *
 * @param string|null $path The path to a file or directory within the plugin e.g. 'dist', 'languages'.
 * @return string The full path to the file or directory, withtout the trailingslash e.g. '/wp-content/plugins/wp-starter-plugin/dist'.
 */
function plugin_dir_path(?string $path = null): string
{
	$path = trim($path, DIRECTORY_SEPARATOR);

	if (! is_blank($path)) {
		$path = WP_STARTER_PLUGIN__DIR__ . DIRECTORY_SEPARATOR . $path;
	}

	return untrailingslashit($path);
}

/**
 * Retrieve the URL to a file or directory within the plugin.
 *
 * @param string|null $path The path to a file or directory within the plugin e.g. 'dist', 'languages'.
 * @return string The full URL to the file or directory, withtout the trailingslash e.g. 'https://example.com/wp-content/plugins/wp-starter-plugin/dist'.
 */
function plugin_dir_url(?string $path = null): string
{
	$dirUrl = plugin_dir_url(WP_STARTER_PLUGIN__FILE__);

	if (! is_blank($path)) {
		$dirUrl .= $path;
	}

	return untrailingslashit($dirUrl);
}

/**
 * Factory function to create a new `Enqueue` instance used for enqueuing
 * scripts and stylessheet.
 */
function enqueue(): Enqueue
{
	$enqueue = new Enqueue(
		plugin_dir_path('dist'),
		plugin_dir_url('dist'),
	);
	$enqueue->setPrefix(WP_STARTER_PLUGIN_NAME);
	$enqueue->setTranslations(WP_STARTER_PLUGIN_NAME, plugin_dir_path('inc/languages'));

	return $enqueue;
}
