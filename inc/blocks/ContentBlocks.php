<?php

/**
 * [Summary]
 *
 * @package  App
 * @since    App 0.0.0
 */

namespace App\Blocks;

use Closure;
use Timber\Timber;

use function acf_register_block;
use function get_fields;

class ContentBlocks
{
  public function addBlock(BlockInterface $block): Closure
  {
    if (function_exists('acf_register_block')) {
      $config = $block->getAllSettings();

      $configWithRenderer = function ($cb) use ($config) {
        $config['render_callback'] = $cb;
        return $config;
      };

      return fn(callable $render) => function () use ($configWithRenderer, $render) {
        acf_register_block($configWithRenderer($render));
      };
    }
  }

  public function renderBlock($siteContext, $templatePath, $isPreview = false): Closure
  {
    // $context = \Timber\Timber::context();
    return function ($block) use ($siteContext, $templatePath, $isPreview) {
      // Store block values.
      $siteContext['block'] = $block;
      // Store field values.
      $siteContext['fields'] = get_fields();
      // Store $is_preview value.
      $siteContext['isPreview'] = $isPreview;
      // Render the block.
      Timber::render($templatePath, $siteContext);
    };
  }
}
