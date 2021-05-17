<?php

return array(
  'menus',
  // Add default posts and comments RSS feed links to head.
  'automatic-feed-links',
  /**
   * Switch default core markup for search form, comment form, and comments
   * to output valid HTML5.
   */
  array('html5', array('comment-form', 'comment-list', 'gallery', 'caption')),
  /**
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  'title-tag',
  /**
   * Enable support for Post Thumbnails on posts and pages.
   *
   * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
   */
  'post-thumbnails',
  /**
   * Enable support for Post Formats.
   *
   * @link https://codex.wordpress.org/Post_Formats
   */
  array('post-formats', array('aside', 'image', 'video', 'quote', 'link', 'gallery', 'audio'))
);
