<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use WPStarterPlugin\Vendor\Syntatis\WPHelpers\Contracts\InlineScript;
use WPStarterPlugin\Vendor\Syntatis\WPHelpers\Enqueue\Enqueue;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Contract\WithHook;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Hook;
use WPStarterPlugin\Vendor\Syntatis\WPOption\Option;
use WPStarterPlugin\Vendor\Syntatis\WPOption\Registry as OptionRegistry;

use function in_array;
use function json_encode;

/**
 * The Plugin Settings.
 *
 * This class manages the plugin's settings including registering the
 * setting options, enqueuing the scripts and the styles, and
 * rendering the settings page.
 */
class Settings implements WithHook, InlineScript
{
	private Enqueue $enqueue;

	private OptionRegistry $options;

	public function __construct()
	{
		/**
		 * Defines the scripts and styles to be enqueued on the settings page.
		 */
		$this->enqueue = enqueue();
		$this->enqueue->addStyle('settings');
		$this->enqueue->addScript('settings', ['localized' => true])->withInlineScripts($this);

		/**
		 * Defines the plugin options to ensures options are handled, stored, and
		 * has a default value, and necessary validation.
		 */
		$this->options = new OptionRegistry(
			[
				(new Option('greeting', 'string'))
					->setDefault('Hello World!')
					->apiEnabled(true),
			],
		);
		$this->options->setPrefix('wp_starter_plugin_');
	}

	public function hook(Hook $hook): void
	{
		$hook->addAction('rest_api_init', fn () => $this->options->register(WP_STARTER_PLUGIN_NAME));
		$hook->addAction('admin_init', fn () => $this->options->register(WP_STARTER_PLUGIN_NAME));
		$hook->addAction('admin_menu', fn () => $this->addMenu());
		$hook->addAction('admin_enqueue_scripts', fn (string $hook) => $this->enqueueScripts($hook));

		$this->options->hook($hook);
	}

	/**
	 * Add the settings menu to the WordPress admin interface.
	 */
	private function addMenu(): void
	{
		add_submenu_page(
			'options-general.php', // Parent slug.
			__('Starter Plugin Settings', 'wp-starter-plugin'),
			__('Starter Plugin', 'wp-starter-plugin'),
			'manage_options',
			WP_STARTER_PLUGIN_NAME,
			fn () => $this->renderPage(),
		);
	}

	 /**
	  * Enqueue scripts and styles for the settings page.
	  *
	  * @param string $adminPage The current admin page.
	  */
	private function enqueueScripts(string $adminPage): void
	{
		/**
		 * List of admin pages where the plugin scripts and stylesheet should load.
		 */
		$adminPages = [
			'settings_page_' . WP_STARTER_PLUGIN_NAME,
			'post.php',
			'post-new.php',
		];

		if (! in_array($adminPage, $adminPages, true)) {
			return;
		}

		$this->enqueue->scripts();
		$this->enqueue->styles();
	}

	/**
	 * Render the plugin settings page.
	 *
	 * Called when user navigates to the plugin settings page. It will render
	 * the page only with base HTML. The settings form, inputs, buttons
	 * will be rendered through React components.
	 *
	 * @see ./src/settings
	 */
	private function renderPage(): void
	{
		if (! current_user_can('manage_options')) {
			return;
		}

		// phpcs:disable Generic.Files.InlineHTML.Found
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<div id="<?php echo esc_attr(WP_STARTER_PLUGIN_NAME) ?>-settings"></div>
		</div>
		<?php
		// phpcs:enable
	}

	public function getInlineScriptPosition(): string
	{
		return 'before';
	}

	public function getInlineScriptContent(): string
	{
		return 'window.__wpStarterPluginSettings = ' . json_encode($this->options) . ';';
	}
}
