<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use WP_Upgrader;
use WPStarterPlugin\Vendor\Syntatis\WPHelpers\Enqueue\Enqueue;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Contract\WithHook;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Hook;

use function trim;
use function WPStarterPlugin\Vendor\Syntatis\Utils\is_blank;
use function WPStarterPlugin\Vendor\Syntatis\WPHelpers\is_plugin_updated;

use const DIRECTORY_SEPARATOR;

/**
 * The Plugin.
 *
 * Serves as the main entry point for plugin, handling the initialization
 * of core functionalities such as the settings, blocks, and hooks, and
 * manages activation, deactivation, and update processes.
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
	}

	/**
	 * Initialize the plugin's features and components.
	 *
	 * @return iterable<WithHook|object>
	 */
	private function getInstances(): iterable
	{
		yield new Blocks($this);
		yield new Settings($this);
	}

	public function init(): void
	{
		register_activation_hook(WP_STARTER_PLUGIN__FILE__, fn () => $this->activate());
		register_deactivation_hook(WP_STARTER_PLUGIN__FILE__, fn () => $this->deactivate());

		foreach ($this->getInstances() as $instance) {
			if (! ($instance instanceof WithHook)) {
				continue;
			}

			$instance->hook($this->hook);
		}

		$this->hook->addAction(
			'upgrader_process_complete',
			fn (WP_Upgrader $upgrader, array $hookExtra) => $this->update($upgrader, $hookExtra),
		);
		$this->hook->register();

		/**
		 * Fires after the plugin is fully initialized.
		 *
		 * Use this action to perform tasks that need to be done after the plugin
		 * is loaded.
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
		$dirUrl = plugin_dir_url(WP_STARTER_PLUGIN__FILE__);

		if (is_blank($path)) {
			return untrailingslashit($dirUrl);
		}

		return untrailingslashit($dirUrl . $path);
	}

	/**
	 * Factory method to create a new instance of `Enqueue` for enqueuing the
	 * scripts and stylessheet files.
	 */
	public function createEnqueue(): Enqueue
	{
		$enqueue = new Enqueue(
			$this->getDirectoryPath('dist'),
			$this->getDirectoryURL('dist'),
		);
		$enqueue->setPrefix(WP_STARTER_PLUGIN_NAME);
		$enqueue->setTranslations(WP_STARTER_PLUGIN_NAME, $this->getDirectoryPath('languages'));

		return $enqueue;
	}

	/**
	 * Handle actions required when the plugin is updated.
	 *
	 * Use this method performs tasks such as database updates, compatibility
	 * checks, and cache clearing when the plugin is updated.
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
