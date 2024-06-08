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

use function WPStarterPlugin\Vendor\Syntatis\Utils\snakecased;

/**
 * This class manages the plugin's settings, including their registration,
 * loading, and rendering within the WordPress admin interface.
 *
 * It handles options initialization, enqueuing scripts and styles,
 * and integrating with the WordPress REST API.
 */
class Settings implements WithHook, InlineScript
{
	private Enqueue $enqueue;

	private OptionRegistry $options;

	/**
	 * The filename of the distribution files (JavaScript and Stylesheet) for the
	 * settings page.
	 */
	private string $distFile = 'components-settings';

	public function __construct(Plugin $plugin)
	{

		/**
		 * Define the plugin options and their default values in the registry.
		 *
		 * This ensures options are correctly stored, retrieved, has a
		 * default value, and necessary validation.
		 */
		$this->options = new OptionRegistry([
			(new Option('greeting', 'string'))
				->setDefault('Hello World!')
				->apiEnabled(true)
		]);
		$this->options->setPrefix(snakecased(WP_STARTER_PLUGIN_NAME) . '_');

		/**
		 * Initialize the Enqueue class to manage scripts and styles for the
		 * settings page.
		 */
		$this->enqueue = new Enqueue(
			$plugin->getDirectoryPath('dist'),
			$plugin->getDirectoryURL('dist'),
		);
		$this->enqueue->setPrefix(WP_STARTER_PLUGIN_NAME);
		$this->enqueue->setTranslations(WP_STARTER_PLUGIN_NAME, $plugin->getDirectoryPath('languages'));
	}

	/**
	 * @inheritDoc
	 */
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
		return 'window.__wpStarterPluginSettings = ' . json_encode($this->options);
	}
}
