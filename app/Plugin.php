<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use WP_Upgrader;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Hook;

use function dirname;
use function trim;
use function WPStarterPlugin\Vendor\Syntatis\Utils\is_blank;
use function WPStarterPlugin\Vendor\Syntatis\WPHelpers\is_plugin_updated;

use const DIRECTORY_SEPARATOR;

/**
 * This class serves as the main entry point for the WP Starter Plugin. It handles
 * the initialization of core functionalities such as settings, blocks, and hooks,
 * and manages activation, deactivation, and update processes.
 */
class Plugin
{
	private string $basename;

	private Hook $hook;

	private Blocks $blocks;

	private Settings $settings;

	public function __construct()
	{
		$this->basename = plugin_basename(WP_STARTER_PLUGIN__FILE__);
		$this->hook = new Hook();

		/**
		 * Initialize the plugin's core components.
		 * This includes registering settings, blocks, and other custom functionalities.
		 */
		$this->blocks = new Blocks($this);
		$this->settings = new Settings($this);
	}

	public function init(): void
	{
		load_plugin_textdomain(WP_STARTER_PLUGIN_NAME, false, dirname($this->basename) . '/languages/');
		register_activation_hook(WP_STARTER_PLUGIN__FILE__, fn () => $this->activate());
		register_deactivation_hook(WP_STARTER_PLUGIN__FILE__, fn () => $this->deactivate());

		$this->blocks->hook($this->hook);
		$this->settings->hook($this->hook);

		$this->hook->addAction(
			'upgrader_process_complete',
			fn (WP_Upgrader $upgrader, array $hookExtra) => $this->update($upgrader, $hookExtra),
		);
		$this->hook->run();

		/**
		 * Fires after the plugin is fully initialized.
		 * Use this action to perform tasks that need to be done after the plugin is loaded.
		 */
		do_action('wp_starter_plugin/init', $this);
	}

	public function getBasename(): string
	{
		return $this->basename;
	}

	/**
	 * Retrieve the path to a file or directory within the plugin.
	 *
	 * @param string|null $path The path to a file or directory within the plugin e.g. 'dist', 'languages'.
	 * @return string The full path to the file or directory, withtout the trailingslash e.g. '/wp-content/plugins/wp-starter-plugin/dist'.
	 */
	public function getDirectoryPath(?string $path = null): string
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
	public function getDirectoryURL(?string $path = null): string
	{
		$dirUrl = untrailingslashit(plugin_dir_url(WP_STARTER_PLUGIN__FILE__));

		if (is_blank($path)) {
			return $dirUrl;
		}

		return untrailingslashit($dirUrl . '/' . $path);
	}

	/**
	 * Handle actions required when the plugin is updated.
	 *
	 * This method performs tasks such as database updates, compatibility checks,
	 * and cache clearing when the plugin is updated.
	 *
	 * @param WP_Upgrader                                            $upgrader  The WP_Upgrader instance.
	 * @param array{action:string,type:string,plugins:array<string>} $hookExtra Additional information about the update process.
	 */
	private function update(WP_Upgrader $upgrader, array $hookExtra): void
	{
		if (! is_plugin_updated($this->basename, $hookExtra)) {
			return;
		}

		// Do something.
	}

	/**
	 * Perform actions required when the plugin is activated.
	 *
	 * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
	 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/
	 */
	private function activate(): void
	{
		/**
		 * Do something, such as creating database tables, performing compatibility checks,
		 * adding options, and flushing caches.
		 */
	}

	/**
	 * Perform actions required when the plugin is deactivated.
	 *
	 * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
	 * @see https://developer.wordpress.org/reference/functions/register_deactivation_hook/
	 */
	private function deactivate(): void
	{
		/**
		 * Do something, such as flushing caches and permalinks.
		 */
	}
}
