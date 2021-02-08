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
class AsyncCssVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.asyncCss.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.asyncCss.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function load($path = false, $async = true, $cached = true) {
        if(!$path || !file_exists(CRAFT_BASE_PATH . '/web/' . $path)) {
            return TEMPLATE::raw("<!-- WARNING: AsyncCss File not found ($path) -->");
        }

        $attributes = ['rel' => 'stylesheet'];
        $lines = [
            'file' => '',
            'noscript' => ''
        ];

        $path = ltrim($path, '/');

        if($cached) {
            if(file_exists(CRAFT_BASE_PATH . '/web/' . $path)) {
                $path = $path . '?v=' .filemtime(CRAFT_BASE_PATH . '/web/' . $path);
            }
        }

        if($async) {
            $lines['noscript'] = "<noscript>" . HTML::cssFile($path, $attributes) . "</noscript>";
            $attributes = array_merge([
                'media' => 'print',
                'onload' => "this.media='all'"
            ], $attributes);
        }

        $lines['file'] = HTML::cssFile($path, $attributes);

        return TEMPLATE::raw(implode("\r\n", $lines));
    }

    public function addClass($class) {
        $options = ['class' => ['persistent' => 'initial']];
        return Html::addCssClass($options, ['persistent' => 'override']);
    }
}


// may 31st or june
// Segment with canadian teams with individual
// Us teams with other thing