<?php

/**
 * Interface for WordPress event subscribers.
 *
 * @package  App
 * @since    App 0.0.0
 */

namespace App\Events;

interface WPEventSubscriberInterface
{
  public static function getSubscribedEvents();
}
