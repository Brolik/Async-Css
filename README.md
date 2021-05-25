# Async Css plugin for Craft CMS 3.x

Asynchronously load css

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require brolik/async-css

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Async Css.

## Async Css Overview

Includes, and adds a version to css files for async loading with a no script fall back. Css files will only be included once per page.

## Using Async Css

### Including a css file

`{{ craft.asyncCss.load('path/to/file', $async, $cached) }}`

#### Params
|Param|Type|Default|Description|
|----|----|----|----|
|Path|String|`null`|The path to your css file within the web folder|
|Async|Boolean|`true`|If the file should be loaded via async|
|Cached|Boolean|`true`|Include a `?v=mtime` query string on the end of the href. Based off the files last modified time.|

### To Class Filter
Converts a string to a css friendly class name by converting to lower case, stripping, white space, and converting to kebab case.

`{{ "some string" | toClass }}`


Brought to you by [Jassok](http://brolik.com)
