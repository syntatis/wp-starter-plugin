<?php

// phpcs:disable -- Generic.Files.InlineHTML.Found

declare(strict_types=1);

namespace WPStarterPlugin;

use WPStarterPlugin\Vendor\Syntatis\WPHelpers\Contracts\InlineScript;
use WPStarterPlugin\Vendor\Syntatis\WPHelpers\Enqueue\Enqueue;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Contract\WithHook;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Hook;
use WPStarterPlugin\Vendor\Syntatis\WPOption\Option;
use WPStarterPlugin\Vendor\Syntatis\WPOption\Registry as OptionRegistry;

/**
 * This class manages the plugin's settings, including their registration,
 * loading, and rendering within the WordPress admin interface. It
 * handles options initialization, enqueuing scripts and styles,
 * and integrating with the WordPress REST API.
 */
class Settings implements WithHook, InlineScript
{
	private Enqueue $enqueue;

	private OptionRegistry $optionRegistry;

	/**
     * The option name prefix used to ensure unique and consistent option names.
     */
	private string $optionPrefix = 'wp_starter_plugin_';

	/**
	 * The prefix used on the handle for enqueuing scripts and styles.
	 */
	private string $enqueuePrefix = WP_STARTER_PLUGIN_NAME;

	/**
     * The group name for registering plugin settings.
     *
     * @see https://developer.wordpress.org/reference/functions/register_setting/
     */
	private string $group = 'wp_starter_plugin';


	/**
     * The filename of the distribution files (JavaScript and Stylesheet) for the
	 * settings page.
     */
	private string $distFile = 'components-settings';

	/**
     * The initial settings values loaded on page load. Defaults are used if not
	 * set in the database.
     */
	private ?string $values = null;

	public function __construct()
	{
		/**
         * Define the plugin options and their default values in the registry.
         * This ensures options are correctly stored, retrieved, and defaulted.
         */
		$this->optionRegistry = new OptionRegistry([
			(new Option('greeting', 'string'))
				->setDefault('Hello World!')
				->apiEnabled(true)
		]);

		$this->enqueue = new Enqueue(
			WP_STARTER_PLUGIN__DIR__ . '/dist',
			plugin_dir_url(WP_STARTER_PLUGIN__FILE__) . 'dist',
		);

		$this->optionRegistry->setPrefix($this->optionPrefix);
		$this->enqueue->setPrefix($this->enqueuePrefix);
		$this->enqueue->setTranslations(WP_STARTER_PLUGIN_NAME, WP_STARTER_PLUGIN__DIR__ . '/languages');
	}

	/**
	 * @inheritDoc
	 */
	public function hook(Hook $hook): void
	{
		$hook->addAction('rest_api_init', fn () => $this->optionRegistry->register($this->group));
		$hook->addAction('admin_init', fn () => $this->optionRegistry->register($this->group));
		$hook->addAction('admin_menu', fn () => $this->addMenu());
		$hook->addAction('admin_enqueue_scripts', fn (string $hook) => $this->enqueueScripts($hook));

		$this->optionRegistry->hook($hook);
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
		if (
			$adminPage === 'settings_page_' . WP_STARTER_PLUGIN_NAME ||
			$adminPage === 'post.php' ||
			$adminPage === 'post-new.php'
		) {
			$this->enqueue->addStyle($this->distFile);
			$this->enqueue->styles();
			$this->enqueue->addScript($this->distFile, ['localized' => true])->withInlineScripts($this);
			$this->enqueue->scripts();
		}
	}

	private function renderPage(): void
	{
		if (! current_user_can('manage_options')) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<div id="wp-starter-plugin-settings"></div>
		</div>
		<?php
	}

	public function getInlineScriptPosition(): string
	{
		return 'before';
	}

	public function getInlineScriptContent(): string
	{
		return 'window.__wpStarterPluginSettings = ' . json_encode($this->optionRegistry);
	}
}
