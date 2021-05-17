<?php

/**
 * Functions and definitions.
 *
 * @link https://github.com/timber/starter-theme
 *
 * @package  App
 * @since    App 0.0.0
 */

use Timber\Timber;
use App\Features;
use App\Events\WPEventDispatcher;
use Timber\{Menu, Site};
use Twig\Extension\StringLoaderExtension;

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
  require_once $autoloader;
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */

$timber = new Timber();

if (!class_exists('Timber')) {
  add_action(
    'admin_notices',
    function () {
      echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="'
      . esc_url(admin_url('plugins.php#timber'))
      . '">'
      . esc_url(admin_url('plugins.php'))
      . '</a></p></div>';
    }
  );

  add_filter(
    'template_include',
    function ($template) {
      return get_stylesheet_directory() . '/public/no-timber.html';
    }
  );

  return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


WPEventDispatcher::addListener(
  'timber/context',
  function ($context) {
    $context['menu']  = new Menu();
    // $context['site']  = $site;
    return $context;
  },
);

WPEventDispatcher::addListener(
  'timber/twig',
  function ($twig) {
    $twig->addExtension(new StringLoaderExtension());
    // $twig->addFilter(new TwigFilter('myfoo', ));
    return $twig;
  },
);

$supportFeatures = require_once __DIR__ . '/inc/config/support.php';

WPEventDispatcher::addListener('after_setup_theme', Features::addSupport($supportFeatures));

new Site();
