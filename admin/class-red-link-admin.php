<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://kibru.me
 * @since      1.0.0
 *
 * @package    Red_Link
 * @subpackage Red_Link/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Red_Link
 * @subpackage Red_Link/admin
 * @author     Kibru Demeke <contact@kibru.me>
 */
class Red_Link_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . "css/red-link-admin.css",
            [],
            $this->version,
            "all"
        );
    }

    public function red_link_block_enqueue()
    {
        wp_enqueue_script(
            "kibru/red-link",
            plugin_dir_url(__FILE__) . "js/build/index.js",
            ["wp-blocks", "wp-element", "wp-editor"],
            $this->version,
            false
        );
    }

    function save_custom_metadata($post_id, $post, $update)
    {
        // Check if this is an autosave or revision
        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can("edit_post", $post_id)) {
            return;
        }

        $red_link_id = $_GET["red_link_id"];
        $meta_key = "red_link_id";
        $meta_value = $red_link_id;

        add_post_meta($post_id, $meta_key, $meta_value, true);
    }
}
