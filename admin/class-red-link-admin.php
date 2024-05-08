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

        wp_enqueue_script(
            "kibru/red-link-formatter", // Handle
            plugin_dir_url(__FILE__) . "/js/admin-script.js",
            [
                "kibru/red-link",
                "wp-blocks",
                "wp-element",
                "wp-editor",
                "wp-components",
                "wp-dom-ready",
            ], // Dependencies, if any
            $this->version, // Version number
            [
                "strategy" => "defer",
            ]
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

        $red_link_id = $_GET["red_link_id"] ?? "";

        if ($red_link_id !== "") {
            $meta_key = "red_link_id";
            $meta_value = $red_link_id;

            add_post_meta($post_id, $meta_key, $meta_value, true);
        }
    }

    function register_custom_rest_routes()
    {
        register_rest_route("red-link/v1", "/check-page-exists", [
            "methods" => "GET",
            "callback" => [$this, "check_page_exists_callback"],
            "permission_callback" => function () {
                return true;
                return current_user_can("manage_options");
            },
            "args" => [
                "red_link_id" => [
                    "required" => true,
                    "validate_callback" => function ($param, $request, $key) {
                        return is_string($param);
                    },
                    // "sanitize_callback" => "absint",
                ],
            ],
        ]);
    }

    function check_page_exists_callback($request)
    {
        // Get the post ID from the request
        $red_link_id = $request->get_param("red_link_id");

        $post_type = "post";
        if (is_plugin_active("betterdocs/betterdocs.php")) {
            $post_type = "docs";
        }

        $args = [
            "post_type" => [$post_type, "page"], // Specify the post types to search
            "meta_query" => [
                [
                    "key" => "red_link_id", // Replace 'your_meta_key' with the actual meta key
                    "value" => $red_link_id, // Value to search for
                    "compare" => "=", // Exact match
                ],
            ],
            "posts_per_page" => 1, // Limit to 1 post
        ];

        $posts = get_posts($args);

        $post_exists = $posts ? true : false;

        // Return the result
        return rest_ensure_response(["exists" => $post_exists]);
    }

    function format_custom_block($block_content, $block)
    {
        return $block_content;
    }
}
