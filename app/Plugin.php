<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use WP_Upgrader;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Contract\WithHook;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Hook;

use function WPStarterPlugin\Vendor\Syntatis\WPHelpers\is_plugin_updated;

/**
 * The Plugin.
 *
 * Serves as the main entry point for plugin, handling the initialization
 * of core functionalities such as the settings, blocks, and hooks, and
 * manages activation, deactivation, and update processes.
 */
class Plugin implements WithHook
{
	/**
	 * Initialize the plugin's features and components.
	 *
	 * @return iterable<WithHook|object>
	 */
	private function getInstances(): iterable
	{
		yield new Blocks();
		yield new Settings();
	}

	public function hook(Hook $hook): void
	{
		foreach ($this->getInstances() as $instance) {
			if (! ($instance instanceof WithHook)) {
				continue;
			}

			$instance->hook($hook);
		}

		$update = fn (WP_Upgrader $upgrader, array $hookExtra) => $this->update($upgrader, $hookExtra);
		$hook->addAction('upgrader_process_complete', $update);
		$hook->register();

		register_activation_hook(WP_STARTER_PLUGIN__FILE__, fn () => $this->activate());
		register_deactivation_hook(WP_STARTER_PLUGIN__FILE__, fn () => $this->deactivate());

		/**
		 * Fires after the plugin is fully initialized.
		 *
		 * Use this action to perform tasks that need to be done after the plugin
		 * is loaded.
		 */
		do_action('wp_starter_plugin/init', $this);
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
		if (! is_plugin_updated(get_plugin_basename(), $hookExtra)) {
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
