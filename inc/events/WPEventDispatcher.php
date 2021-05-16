<?php

/**
 * Event dispatcher that serves as an interface for the WordPress
 * Plugin API. Hooks action and filter callbacks to WordPress events.
 *
 * @package  App
 * @since    App 0.0.0
 */

namespace App\Events;

class WPEventDispatcher
{
  /**
   * Adds the given event listener to the list of event listeners
   * that listen to the given event.
   *
   * @param string   $eventName
   * @param callable $listener
   * @param int      $priority
   * @param int      $acceptedArgs
   *
   * @return void
   */
  public function addListener($eventName, $listener, $priority = 10, $acceptedArgs = 1): void
  {
    $this->addCallback($eventName, $listener, $priority, $acceptedArgs);
  }

  /**
   * Add an event subscriber.
   *
   * The event manager registers all the hooks that the given subscriber
   * wants to register with the WordPress Plugin API.
   *
   * @return void
   */
  public function addSubscriber(WPEventSubscriberInterface $subscriber): void
  {
    foreach ($subscriber::getSubscribedEvents() as $hookName => $param) {
      $this->addSubscriberCallback($subscriber, $hookName, $param);
    }
  }

  /**
   * Removes the given event listener from the list of event listeners
   * that listen to the given event.
   *
   * @param string   $eventName
   * @param callable $listener
   * @param int      $priority
   *
   * @return void
   */
  public function removeListener($eventName, $listener, $priority = 10): void
  {
    $this->removeCallback($eventName, $listener, $priority);
  }

  /**
   * Remove an event subscriber.
   *
   * The event manager removes all the hooks that the given subscriber
   * wants to register with the WordPress Plugin API.
   */
  public function removeSubscriber(WPEventSubscriberInterface $subscriber)
  {
    foreach ($subscriber::getSubscribedEvents() as $hookName => $params) {
      $this->removeSubscriberCallback($subscriber, $hookName, $params);
    }
  }

  /**
   * Executes all the callbacks registered with the given hook.
   *
   * @uses do_action()
   *
   * @param string $hookName
   */
  public function dispatch()
  {
    $args = func_get_args();
    return call_user_func_array('do_action', $args);
  }

  /**
   * Filters the given value by applying all the changes from the callbacks
   * registered with the given hook. Returns the filtered value.
   *
   * @uses apply_filters()
   *
   * @param string $hookName
   * @param mixed  $value
   *
   * @return mixed
   */
  public function pipe()
  {
    $args = func_get_args();
    return call_user_func_array('apply_filters', $args);
  }

  /**
   * Get the name of the hook that WordPress Plugin API is executing. Returns
   * false if it isn't executing a hook.
   *
   * @uses current_filter()
   *
   * @return string|bool
   */
  public function getCurrentHook()
  {
    return current_filter();
  }

  /**
   * Checks the WordPress Plugin API to see if the given hook has
   * the given callback. The priority of the callback will be returned
   * or false. If no callback is given will return true or false if
   * there's any callbacks registered to the hook.
   *
   * @uses has_filter()
   *
   * @param string $hookName
   * @param mixed  $callback
   *
   * @return bool|int
   */
  public function hasCallback($hookName, $callback = false)
  {
    return has_filter($hookName, $callback);
  }

  /**
   * Adds a callback to a specific hook of the WordPress Plugin API.
   *
   * @uses add_filter()
   *
   * @param string   $hookName
   * @param callable $callback
   * @param int      $priority
   * @param int      $acceptedArgs
   */
  public function addCallback($hookName, $callback, $priority = 10, $acceptedArgs = 1): void
  {
    add_filter($hookName, $callback, $priority, $acceptedArgs);
  }

  /**
   * Removes the given callback from the given hook. The WordPress Plugin API only
   * removes the hook if the callback and priority match a registered hook.
   *
   * @uses remove_filter()
   *
   * @param string   $hookName
   * @param callable $callback
   * @param int      $priority
   *
   * @return bool
   */
  public function removeCallback($hookName, $callback, $priority = 10): bool
  {
    return remove_filter($hookName, $callback, $priority);
  }

  /**
   * Adds the given subscriber's callback to a specific hook
   * of the WordPress plugin API.
   */
  private function addSubscriberCallback(WPEventSubscriberInterface $subscriber, string $hookName, $params)
  {
    if (is_string($params)) {
      return $this->addCallback($hookName, [$subscriber, $params]);
    }

    if (is_array($params) && isset($params[0])) {
      return $this->addCallback(
        $hookName,
        [$subscriber, $params[0]],
        isset($params[1]) ? $params[1] : 10,
        isset($params[2]) ? $params[2] : 1,
      );
    }
  }

  /**
   * Removes the given subscriber's callback to a specific hook
   * of the WordPress Plugin API.
   */
  private function removeSubscriberCallback(WPEventSubscriberInterface $subscriber, $hookName, $params)
  {
    if (is_string($params)) {
      $this->removeCallback($hookName, [$subscriber, $params]);
    }

    if (is_array($params) && isset($params[0])) {
      $this->removeCallback($hookName, [$subscriber, $params[0]], isset($params[1]) ? $params[1] : 10);
    }
  }
}
