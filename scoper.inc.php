<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

return [
	/**
	 * The prefix configuration. If a non-null value is used, a random prefix
	 * will be generated instead.
	 */
	'prefix' => 'WPStarterPlugin\\Vendor',

	/**
	 * By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
	 * directory. You can however define which files should be scoped by defining a collection of Finders in the
	 * following configuration key.
	 *
	 * This configuration entry is completely ignored when using Box.
	 *
	 * @see https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#finders-and-paths
	 */
	'finders' => [
		Finder::create()
			->files()
			->ignoreVCS(true)
			->notName('/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/')
			->notPath(['bamarni', 'bin'])
			->exclude([
				'doc',
				'test',
				'test_old',
				'tests',
				'Tests',
				'vendor-bin',
			])
			->in('vendor'),
		Finder::create()->append(['composer.json']),
	],

	/**
	 * List of excluded files, i.e. files for which the content will be left untouched.
	 * Paths are relative to the configuration file unless if they are already absolute
	 */
	'exclude-files' => [],

	/**
	 * When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
	 * original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
	 * support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to
	 * your heart contents.
	 *
	 * @see https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
	 */
	'patchers' => [
		static function (string $filePath, string $prefix, string $content): string {
			if (basename($filePath) === 'composer.json') {
				$json = json_decode($content, true);
				$psr4 = $json['autoload']['psr-4'] ?? [];

				foreach ($psr4 as $key => $value) {
					$psr4[$key] = str_replace('app/', '../app/', $value);
				}

				$json['autoload']['psr-4'] = $psr4;

				return json_encode([
					'require' => $json['require'] ?? [],
					'autoload' => $json['autoload'] ?? [],
					'config' => $json['config'] ?? [],
				], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			}

			return $content;
		},
	],

	/**
	 * List of symbols to consider internal i.e. to leave untouched.
	 *
	 * @see https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#excluded-symbols
	 */
	'exclude-namespaces' => ['WPStarterPlugin'],

	/**
	 * List of symbols to expose.
	 *
	 * @see https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#exposed-symbols
	 */
	'expose-global-constants' => false,
	'expose-global-classes' => false,
	'expose-global-functions' => false,
];
