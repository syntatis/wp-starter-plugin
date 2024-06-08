<?php

declare(strict_types=1);

namespace WPStarterPlugin;

use RecursiveDirectoryIterator;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Contract\WithHook;
use WPStarterPlugin\Vendor\Syntatis\WPHook\Hook;

use const DIRECTORY_SEPARATOR;

class Blocks implements WithHook
{
	private RecursiveDirectoryIterator $blocks;

	public function __construct(Plugin $plugin)
	{
		$this->blocks = new RecursiveDirectoryIterator(
			$plugin->getDirectoryPath('dist' . DIRECTORY_SEPARATOR . 'blocks'),
			RecursiveDirectoryIterator::SKIP_DOTS,
		);
	}

	public function hook(Hook $hook): void
	{
		$hook->addAction('init', fn () => $this->registerBlocks());
	}

	private function registerBlocks(): void
	{
		foreach ($this->blocks as $block) {
			if (! $block->isDir()) {
				continue;
			}

			register_block_type($block->getRealPath());
		}
	}
}
