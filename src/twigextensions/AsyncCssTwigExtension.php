<?php
/**
 * Async Css plugin for Craft CMS 3.x
 *
 * Asynchronously load css
 *
 * @link      http://brolik.com
 * @copyright Copyright (c) 2021 Jassok
 */

namespace brolik\asynccss\twigextensions;

use brolik\asynccss\AsyncCss;

use Craft;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Jassok
 * @package   AsyncCss
 * @since     1.0.0
 */
class AsyncCssTwigExtension extends AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'AsyncCss';
    }

    /**
     * Converts a string to a css class friendly pattern:
     *
     *      {{ 'something' | toClass }}
     * 
     * @return array
     */
    public function getFilters() {
        return [
            new TwigFilter('toClass', [$this, 'toClass']),
        ];
    }

    /**
     * Takes a string, and converts it to a css class friendly name by
     * Stripping any wrapping whitespace, converting to lower case,
     * and converting to kabob style
     *
     * @param null $text
     *
     * @return string
     */
    public function toClass($text = null) {
        $result = rtrim(ltrim(strtolower($text)));
        $result = preg_replace("/[^A-z\\s\\d][\\\^]?/", '', $result);
        $result = preg_replace("/\\s+/", '-', $result);

        return $result;
    }
}
