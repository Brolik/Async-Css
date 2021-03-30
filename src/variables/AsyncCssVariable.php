<?php
/**
 * Async Css plugin for Craft CMS 3.x
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
    // Alert in the source if theres no file
    if(!$path || !file_exists(CRAFT_BASE_PATH . '/web/' . $path))
      return TEMPLATE::raw("<!-- WARNING: AsyncCss File not fouit lnd ($path) -->");

    // Base attributes
    $rel = ['rel' => 'stylesheet'];
    $media = [];

    $lines = [];

    // Clean up the path if necessary
    $path = ltrim($path, '/');

    // Add a version query string.
    if($cached) {
      if(file_exists(CRAFT_BASE_PATH . '/web/' . $path)) {
        $path = $path . '?v=' .filemtime(CRAFT_BASE_PATH . '/web/' . $path);
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
    $lines[] = HTML::cssFile($path, array_merge($rel, $media));

    // <noscript> fallback
    if($async) {
      $lines[] = HTML::cssFile($path, array_merge($rel, ['noscript' => true]));
    }

    return TEMPLATE::raw(HTML::decode(implode("\r\n", $lines)));
  }
}
