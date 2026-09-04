<?php
/**
 * @wordpress-plugin
 * Plugin Name:        emBold Sage11 Blocks
 * Plugin URI:         https://github.com/emboldagency/embold-sage11-blocks
 * Description:        A collection of Tailwind Blocks for Sage 11 based themes. Requires Advanced Custom Fields PRO plugin to be enabled and activated.
 * Version:            0.1.3
 * Author:             emBold
 * Author URI:         https://embold.com/
 * Primary Branch:     master
 */

// Prevent direct access to this file
if (! defined('ABSPATH')) {
    exit;
}

// Load the autoloader
require_once plugin_dir_path(__FILE__).'vendor/autoload.php';

// Include the main plugin class
require_once plugin_dir_path(__FILE__).'includes/EmboldSage11Blocks.php';

include_once ABSPATH.'wp-admin/includes/plugin.php';

function acorn_version_warning_notice()
{
    ?>
    <div class="notice notice-warning">
        <p><?php _e('This plugin requires Acorn v5, bundled with Sage v11.', 'text-domain'); ?></p>
    </div>
    <?php
}

function acf_composer_version_warning_notice()
{
    ?>
    <div class="notice notice-warning">
        <p><?php _e('This plugin requires Log1x ACF Composer v3.', 'text-domain'); ?></p>
    </div>
    <?php
}

function sage_theme_warning_notice()
{
    ?>
    <div class="notice notice-warning">
        <p><?php _e('This plugin requires a Sage 11 based theme. The active theme does not require roots/acorn, so the blocks have not been loaded.', 'text-domain'); ?></p>
    </div>
    <?php
}

function acf_pro_warning_notice()
{
    ?>
    <div class="notice notice-warning">
        <p><?php _e('This plugin requires ACF Pro to be enabled. Please install and activate ACF Pro to use this plugin.', 'text-domain'); ?></p>
    </div>
    <?php
}

// Check if ACF or ACF Pro is active, deactivate our plugin if not
if (! is_plugin_active('advanced-custom-fields/acf.php') && ! is_plugin_active('advanced-custom-fields-pro/acf.php')) {
    // Deactivate the plugin
    deactivate_plugins(plugin_basename(__FILE__));

    add_action('admin_notices', 'acf_pro_warning_notice');

    return;
}

function interpretComposerVersion($version)
{
    // Check if the version starts with ^ or ~, which are common in composer.json, and remove them
    // This is a simplistic approach and does not fully replicate Composer's version resolution
    $version = ltrim($version, '^~');

    if (substr_count($version, '.') === 1) {
        // Append the missing patch level
        $version .= '.0';
    }

    return $version;
}

/**
 * Read the active theme's composer.json.
 *
 * Uses get_theme_file_path() so child themes resolve the same way Acorn's own
 * base path inference does.
 */
function embold_sage11_blocks_theme_composer_data()
{
    static $data;

    if (isset($data)) {
        return $data ?: null;
    }

    $data = false;

    $composer_json_path = get_theme_file_path('composer.json');

    if (! is_file($composer_json_path)) {
        return null;
    }

    $decoded = json_decode(file_get_contents($composer_json_path), true);

    if (is_array($decoded)) {
        $data = $decoded;
    }

    return $data ?: null;
}

/**
 * Determine whether the active theme is Sage/Acorn based.
 *
 * The blocks are registered against an Acorn container whose base path is
 * inferred from the theme's composer.json. Without one requiring roots/acorn
 * that inference falls back to this plugin's own directory, which puts the
 * cache and view paths somewhere they don't belong.
 */
function embold_sage11_blocks_theme_is_sage()
{
    $composer_data = embold_sage11_blocks_theme_composer_data();

    return isset($composer_data['require']['roots/acorn']);
}

function check_theme_acorn_version()
{
    $composer_data = embold_sage11_blocks_theme_composer_data();

    // The active theme isn't Sage based, so the plugin has nothing to attach to
    if (! embold_sage11_blocks_theme_is_sage()) {
        deactivate_plugins(plugin_basename(__FILE__));

        add_action('admin_notices', 'sage_theme_warning_notice');

        return;
    }

    $acorn_version = interpretComposerVersion($composer_data['require']['roots/acorn']);

    // Check if the Acorn version is v3 or v4
    if (version_compare($acorn_version, '4.0.0', '<')) {
        // Acorn version is v3, deactivate the plugin
        deactivate_plugins(plugin_basename(__FILE__));

        // Display a warning message
        add_action('admin_notices', 'acorn_version_warning_notice');
    }

    if (isset($composer_data['require']['log1x/acf-composer'])) {
        $acf_composer_version = interpretComposerVersion($composer_data['require']['log1x/acf-composer']);

        // Check if the ACF Composer version is v2 or lower
        if (version_compare($acf_composer_version, '3.0.0', '<')) {
            // Deactivate the plugin
            deactivate_plugins(plugin_basename(__FILE__));

            // Display a warning message
            add_action('admin_notices', 'acf_composer_version_warning_notice');
        }
    }
}

add_action('admin_init', 'check_theme_acorn_version');

require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$embold_update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/emboldagency/embold-sage11-blocks/',
    __FILE__,
    'embold-tailwind-blocks'
);

// Set to use GitHub Releases
$embold_update_checker->getVcsApi()->enableReleaseAssets();

// Plugin initialization
function embold_sage11_blocks_init()
{
    // admin_init runs too late to stop this, and never runs on the front end,
    // so the Sage check has to happen here as well
    if (! embold_sage11_blocks_theme_is_sage()) {
        return;
    }

    // Create an instance of your plugin class
    $plugin = new \App\EmboldSage11Blocks();

    // Insert the block category
    $plugin->insertBlockCategory();

    // Add compatibility with Laraish Themes
    $plugin->formatSubfieldsToArrays();

    $plugin->registerModifiers();
}

add_action('plugins_loaded', 'embold_sage11_blocks_init');
