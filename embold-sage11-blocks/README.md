# emBold Enhanced ACF/Block Composer

## What does it do?

This plugin utilizes the acf-composer and acf-builder packages and allows us to include our own default
Block and Field classes in the plugin files to jumpstart theme development.

If for some reason you need to tweak or modify what comes with the plugin you can copy the file into your theme and tweak it.
Need the Statistics block to do something slightly different? Copy its app/Blocks/Statistics.php from the plugin to your theme and
the theme files take priority. You can do this with the Padding field, or any views as well.

It includes the following by default:

### Blocks

- Statistics: A list of statistics with subfields for number and description, in 4 styles of featurd, grid, list, and full width.

### Fields

- Padding: A padding field option added to every block where the user can check whether they want padding to apply to all sides of just individual sides of a block.

### Options

The plugin creates a parent "Theme Options" category in the sidebar, this way when you're creating any options pages you
can set their parent slugs to `theme-options`.

### Modifiers

Modifiers can be created in the app/Modifiers directory of the Sage theme, and are used to add style options to blocks from
either the core or any blocks added via other extensions. This plugin has a started example file that can be copied in
app/Modifiers/CoreGroup.php.example

You'll simply set the name to one of the blocks, in our example "core/group" and then you can add as many style options
as you see fit.

## Sage 10 setup

After the plugin is installed and activated, you'll want to configure both your tailwind.config.js and bud.config.js to
watch the plugins files so that purge and browsersync work with the plugin.

bud.config.js

```js
.watch([
            'resources/views',
            'app',
            '../../plugins/embold-tailwind-blocks',
        ])
```

tailwind.config.cjs

```js
content: [
        './index.php',
        './app/**/*.php',
        './resources/**/*.{php,vue,js}',
        '../../plugins/embold-tailwind-blocks/**/*.php',
    ],
```

Now classes only used in the plugin files will not be purged, and the page can be automatically reloaded when files change.

## Commands

Generate a custom block with our template.
```shell
wp acorn embold:block Example
```

### Publishable Files

You can copy some or all files from the plugin into the appropriate places in your app for easy override. If the files already exist in the destination, they will be skipped.

Publish all tags from our service provider.
```shell
wp acorn vendor:publish --provider='App\Providers\EmboldServiceProvider'
```

Publish a specific tag/tags.
```shell
wp acorn vendor:publish --tag=embold-blocks
```

Available tags:
- `embold-config` will copy the plugin config file to `config/emblocks.php` where you can toggle plugin features, blocks, fields, etc.
- `embold-blocks` will copy the block definition files, views, scripts, and styles.
- `embold-fields` will copy the fields.
- `embold-modifiers` will copy over the core modifiers.
- `embold-stubs` will copy over the stubs which provide the templates when creating the new block files.

## Customizing a Block view in your Sage10 theme

Often you'll find you need to tweak how a block is rendered per theme to match each design.

Block views are stored inside of `resources/views/blocks/{block-name}.blade.php` - to customize the views for your theme you can
copy this file directly over to your themes `resources/views/blocks` directory and start modifying it. The theme version will
always override the version found in the plugin.

## Block JavaScript

Our blocks use our own custom class [App\CustomBlock](https://github.com/emboldagency/embold-tailwind-blocks/blob/master/includes/CustomBlock.php) that extends the default [Log1x\AcfComposer\Block](https://github.com/emboldagency/embold-tailwind-blocks/blob/master/vendor/log1x/acf-composer/src/Block.php) class that Sage typically uses.
This allows us to do a few different things that we normally wouldn't be able to do.

First, ensure that the blocks class declaration file (example: /resources/app/Blocks/ExampleBlock.php) uses our custom class and not the default.

#### :heavy_check_mark: Correct

```php
use App\CustomBlock;
use Log1x\AcfComposer\Builder;

class Accordion extends CustomBlock
```

#### :x: Incorrect

```php
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Accordion extends Block
```

### Automatic JavaScript File Loading

Now that we're using our `CustomBlock` class, we have our extension of the `assets($block)` method which will automatically load JavaScript files based on their "slug" format naming.
For example a HeroSlider.php block would automatically look for and try to load a `/resources/scripts/blocks/hero-slider.js` - but only if the file exists.

One of the benefits of this is that you do not need to manually import each .js file in your app.js. Loading your JavaScript files in app.js includes the code for your block on every page,
regardless of your block being called on the page or not. By using our automatic file loading method, the JavaScript file is only loaded on pages that call your block,
and only once per page.

If you have a JavaScript file for your block you can add it to the Sage `bud.config.js` array of entrypoints. The example below shows how to add both single word files,
as well as files that may contain a hyphen. This will run your files through the same process as the app.js but output into their own compiled versions.

```js
app.entry({
  app: ["@scripts/app", "@styles/app"],
  editor: ["@scripts/editor", "@styles/editor"],
  slider: ["@scripts/blocks/slider"],
  "hero-slider": ["@scripts/blocks/hero-slider"],
});
```

Our [custom assets($block) method](https://github.com/emboldagency/embold-tailwind-blocks/blob/master/includes/CustomBlock.php#L12) will actually look for your files key in the manifest.json and
load it based on the value pair so that it will always load the correct version while in development or production.

### Already Included JavaScript Files

Some of our plugins blocks already come with a JavaScript file in the plugins `/resources/scripts/blocks` directory that you can copy over to your themes `/resources/scripts/blocks` and override.
We have tried to leave the default functionality in these files barebones so they can be easily tweaked and extended for each themes use case.

As always, the theme version of JavaScript files will always override the version found in the plugin.

## Customizing a Block class in your Sage10 theme

You may need to modify what ACF fields are in a block, or how the data is being manipulated on its way to the frontend.

> **Important:** If you copy over your blocks class you must also copy over the view

The class file for each block is stored in `app/Blocks/{BlockName}.php` and can be copied over directly to your theme
in the same directory and file name.

There are many things you can customize in these files, most of which are self explanatory, while some could use a little explaining.

- `$icon` this is the shortcode for any [dashicon](https://developer.wordpress.org/resource/dashicons/)
- `$category` should always be "embold" so that all of our custom blocks are grouped together in the editor.
- The `$post_types` and `$parent` arrays let you specify what post types and what types of other blocks your block can be embedded in.
- The `public $styles` array is used to add different options for styles to use when building out the template. By default it has a light and dark mode.
- The `public $example` array is used to provide example data that the client can see when they first add the block to a template.
- The `public function fields()` method is for building out ACF fields used just in this block. Reference this [cheat sheet](https://github.com/Log1x/acf-builder-cheatsheet) for help with how to do the different fields.
- The `public function items()` method is example of how you can manipulate properties before sending them to the frontend. You'll notice in the `with()` method we're referencing items as `$this➝items()` which is a direct call to this method. The example one each block comes with is showing you that you can use a ternary to send either the ACF value if it exists, or the example value if it is blank. You can use these methods to transform your data before passing it to the frontend. If you wanted to manipulate a `date` field for example before rendering it on the frontend you could make a `public function date()` method.

## Bundled Styling for blocks

In the plugins `/resources/styles/blocks` directory you'll find some .css files that match up with the "slug" naming of blocks. These
files are not loaded into the site by default but are bundled in so you can copy/paste them over to your themes `/resources/styles/blocks` to have a good starting
point.

## Adding new Blocks to the plugin

If you're adding a new block to the plugin itself, start off by duplicaing one of the existing class files from `app/Blocks` and view files from `resources/views/blocks`.

Make sure your block for the plugin inherits our custom block class and not the default sage class, check the top of the block class file.

#### :heavy_check_mark: Correct

```php
use App\CustomBlock;
use Log1x\AcfComposer\Builder;

class Accordion extends CustomBlock
```

#### :x: Incorrect

```php
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Accordion extends Block
```

Configure the class and the view in a customizable format, ideally something that can be re-used. For example, if your block requires the use
of images, add an image field where applicable so this can be changed per theme.

**Registering your block** is done inside of `includes/InitBlocks.php` by adding additional blocks to the `$block_classes` array in the `initialize` method.
