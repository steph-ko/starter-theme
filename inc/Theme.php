<?php

/**
 * Sets up the App theme.
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

namespace App;

use Timber\Menu;
use Timber\Site;
use Twig\Extension\StringLoaderExtension;
use Twig\TwigFilter;

class Theme extends Site
{
  /** Add timber support. */
  public function __construct()
  {
    add_action('after_setup_theme', array($this, 'addThemeSupports'));
    add_filter('timber/context', array($this, 'addToContext'));
    add_filter('timber/twig', array($this, 'addToTwig'));
    add_action('init', array($this, 'registerPostTypes'));
    add_action('init', array($this, 'registerTaxonomies'));
    parent::__construct();
  }

  /** This is where you can register custom post types. */
  public function registerPostTypes()
  {
  }

  /** This is where you can register custom taxonomies. */
  public function registerTaxonomies()
  {
  }

  /** This is where you add some context
   *
   * @param string $context context['this'] Being the Twig's {{ this }}.
   */
  public function addToContext($context)
  {
    $context['foo']   = 'bar';
    $context['stuff'] = 'I am a value set in your functions.php file';
    $context['notes'] = 'These values are available everytime you call Timber::context();';
    $context['menu']  = new Menu();
    $context['site']  = $this;
    return $context;
  }

  public function addThemeSupports()
  {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
      * Let WordPress manage the document title.
      * By adding theme support, we declare that this theme does not use a
      * hard-coded <title> tag in the document head, and expect WordPress to
      * provide it for us.
      */
    add_theme_support('title-tag');

    /*
      * Enable support for Post Thumbnails on posts and pages.
      *
      * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
      */
    add_theme_support('post-thumbnails');

    /*
      * Switch default core markup for search form, comment form, and comments
      * to output valid HTML5.
      */
    add_theme_support(
      'html5',
      array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
      )
    );

    /*
      * Enable support for Post Formats.
      *
      * See: https://codex.wordpress.org/Post_Formats
      */
    add_theme_support(
      'post-formats',
      array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'audio',
      )
    );

    add_theme_support('menus');
  }

  /** This is where you can add your own functions to twig.
   *
   * @param string $twig get extension.
   */
  public function addToTwig($twig)
  {
    $twig->addExtension(new StringLoaderExtension());
    $twig->addFilter(new TwigFilter('myfoo', array($this, 'myfoo')));
    return $twig;
  }
}
