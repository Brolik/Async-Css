<?php
/**
 * Async Css plugin for Craft CMS 5.x
 *
 * Asynchronously load css
 *
 * @link      http://brolik.com
 * @copyright Copyright (c) 2021 Jassok
 */

namespace brolik\asynccss\variables;

use brolik\asynccss\AsyncCss;

use Craft;
use craft\helpers\Html;
use craft\helpers\Template;

/**
 * Async Css Variable
 *
 * Craft allows plugins to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.asyncCss }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    Jassok
 * @package   AsyncCss
 * @since     1.0.0
 */
class AsyncCssVariable {
  public $sheetsOnPage = [];

  // Public Methods
  // =========================================================================

  /**
   * Returns a <link> tag for css files. Can also include a ?v=mtime query string
   * and a <noscript> fallback.
   * @param null $path
   * @param bool $async
   * @param bool $cached
   * @return string
   */
  public function load($path = false, $async = true, $cached = true) {
    // Resolve webroot
    $webroot = Craft::getAlias('@webroot');

    // Alert in the source if there's no file
    if (!$path || !file_exists($webroot . '/' . ltrim($path, '/')))
      return Template::raw("<!-- WARNING: AsyncCss File not found ($path) -->");

    // Check if the sheet has already been included
    if (in_array($webroot . '/' . ltrim($path, '/'), $this->sheetsOnPage))
      return false;

    // Add the sheet to the list
    $this->sheetsOnPage[] = $webroot . '/' . ltrim($path, '/');


    // Base attributes
    $rel = ['rel' => 'stylesheet'];
    $media = [];

    $lines = [];

  // Clean up the path if necessary
  $path = ltrim($path, '/');

    // Add a version query string.
    if ($cached) {
      if (file_exists($webroot . '/' . $path)) {
        $path = $path . '?v=' . filemtime($webroot . '/' . $path);
      }
    }

    // Include the media attribute if async
    if($async) {
      $media = [
        'media' => 'print',
        'onload' => htmlspecialchars("this.media='all'"),
      ];
    }

  // Re-add the /
  $path = '/'.$path;

    // Add the base script file
    $lines[] = Html::cssFile($path, array_merge($rel, $media));

    // <noscript> fallback
    if ($async) {
      $lines[] = Html::cssFile($path, array_merge($rel, ['noscript' => true]));
    }

    return Template::raw(Html::decode(implode("\r\n", $lines)));
  }
}
