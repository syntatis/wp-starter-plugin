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

		/**
		 * Register actions to run when the plugin is activated or deactivated.
		 *
		 * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
		 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/
		 * @see https://developer.wordpress.org/reference/functions/register_deactivation_hook/
		 */
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
	 * Handle actions when the plugin is updated.
	 *
	 * @param WP_Upgrader                                            $upgrader  The WP_Upgrader instance.
	 * @param array{action:string,type:string,plugins:array<string>} $hookExtra Additional information about the update process.
	 */
	private function update(WP_Upgrader $upgrader, array $hookExtra): void
	{
		if (! is_plugin_updated(plugin_basename(), $hookExtra)) {
			return;
		}

		/**
		 * Perform routine after the plugin is updated such, as updating database
		 * tables, checking compatibility, and flushing caches.
		 */
	}

	/**
	 * Handle actions when the plugin is activated.
	 */
	private function activate(): void
	{
		/**
		 * Perform routine after the plugin is activated, such as setting default
		 * option values, adding custom rewrite rules, add custom database
		 * tables.
		 */
	}

	/**
	 * Handle actions when the plugin is deactivated.
	 */
	private function deactivate(): void
	{
		/**
		 * Perform routine after the plugin is deactivated, such as removing temp
		 * data and files, removing custom database tables and options, and
		 * flushing cache.
		 */
	}
}
