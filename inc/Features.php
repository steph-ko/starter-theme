<?php

/**
 * [Summary]
 *
 * @package  App
 * @since    App 0.0.0
 */

namespace App;

class Features
{
  public static function addSupport(array $features): callable
  {
    return function () use ($features) {
      foreach ($features as $feature) {
        if (is_string($feature)) {
          add_theme_support($feature);
        }

        if (is_array($feature)) {
          add_theme_support($feature[0], $feature[1]);
        }
      }
    };
  }

  public static function addStyles($handle, $src = '', $deps = array(), $ver = false, $media = 'all'): callable
  {
    return function () use ($handle, $src, $deps, $ver, $media) {
      wp_enqueue_style($handle, $src, $deps, $ver, $media);
    };
  }
}
