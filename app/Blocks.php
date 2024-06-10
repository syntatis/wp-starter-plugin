<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use RecursiveDirectoryIterator;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Contract\WithHook;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Hook;

use const DIRECTORY_SEPARATOR;

class Blocks implements WithHook
{
	public function hook(Hook $hook): void
	{
		$hook->addAction('init', fn () => $this->registerBlocks());
	}

	private function registerBlocks(): void
	{
		$blocks = new RecursiveDirectoryIterator(
			plugin_dir_path('dist' . DIRECTORY_SEPARATOR . 'blocks'),
			RecursiveDirectoryIterator::SKIP_DOTS,
		);

		foreach ($blocks as $block) {
			if (! $block->isDir()) {
				continue;
			}

			register_block_type($block->getRealPath());
		}
	}
}
