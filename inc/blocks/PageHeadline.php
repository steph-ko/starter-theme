<?php

/**
 * [Summary]
 *
 * @package  App
 * @since    App 0.0.0
 */

namespace App\Blocks;

use function Symfony\Component\String\u;

class PageHeadline implements BlockInterface
{
  private $name = 'headline';
  private $title = 'Page Headline';
  private $description = '';
  private $category = 'content';
  private $icon = 'admin-comments';
  private $keywords = array('content');

  public function getAllSettings(): array
  {
    $props = get_object_vars($this);
    $propKeys = array_keys($props);

    return array_reduce(
      $propKeys,
      function ($formattedProps, $key) use ($props) {
        $formattedKey = u($key)->snake()->toString();
        $formattedProps[$formattedKey] = $props[$key];
        return $formattedProps;
      },
      [],
    );
  }
}
