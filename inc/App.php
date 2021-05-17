<?php

/**
 * The theme setup.
 *
 * @package  App
 * @since    App 0.0.0
 */

namespace App;

use App\Events\WPEventDispatcher;
use Timber\Menu;
use Timber\Site;
use Twig\Extension\StringLoaderExtension;
use Twig\TwigFilter;

class App extends Site
{
  /**
   * [Summary]
   *
   * @var WPEventDispatcher
   */
  private $wpEvents;

  private $context;

  /** Add timber support. */
  public function __construct(WPEventDispatcher $wpEvents, $context)
  {
    $this->wpEvents = $wpEvents;
    $this->context = $context;
    parent::__construct();
  }

  public function load()
  {
    add_filter('timber/context', array($this, 'addToContext'));
    add_filter('timber/twig', array($this, 'addToTwig'));
    // add_action('init', array($this, 'registerPostTypes'));
    // add_action('init', array($this, 'registerTaxonomies'));
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
    // $context['foo']   = 'bar';
    // $context['stuff'] = 'I am a value set in your functions.php file';
    // $context['notes'] = 'These values are available everytime you call Timber::context();';
    $context['menu']  = new Menu();
    $context['site']  = $this;
    return $context;
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

  public function addSupport(array $features)
  {
    $support = function () use ($features) {
      foreach ($features as $feature) {
        if (is_string($feature)) {
          add_theme_support($feature);
        }

        if (is_array($feature)) {
          add_theme_support($feature[0], $feature[1]);
        }
      }
    };

    $this->wpEvents->addListener('after_setup_theme', $support);
  }

  public function addStyles($handle, $src = "", $deps = array(), $ver = false, $media = "all")
  {
    $styles = function () use ($handle, $src, $deps, $ver, $media) {
      wp_enqueue_style($handle, $src, $deps, $ver, $media);
    };

    $this->wpEvents->addListener('wp_enqueue_scripts', $styles);
  }

  public function removeBodyClass(string $class)
  {
    $newClasses = function (array $classes) use ($class) {
      if (in_array($class, $classes)) {
        unset($classes[array_search($class, $classes)]);
      }

      return $classes;
    };

    $this->wpEvents->addListener('body_class', $newClasses);
  }
}
