<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://kibru.me
 * @since      1.0.0
 *
 * @package    Red_Link
 * @subpackage Red_Link/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Red_Link
 * @subpackage Red_Link/public
 * @author     Kibru Demeke <contact@kibru.me>
 */
class Red_Link_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Red_Link_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Red_Link_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . "css/red-link-public.css",
            [],
            $this->version,
            "all"
        );
    }

    function modify_post_content($content)
    {
        // Check if the content contains <red-link> tags
        if (strpos($content, "<red-link") !== false) {
            // Replace <red-link> tags with <a> tags
            $content = preg_replace_callback(
                "/<red-link([^>]*)>(.*?)<\/red-link>/i",
                [$this, "replace_red_link_tags"],
                $content
            );
        }

        return $content;
    }

    // Callback function to replace <red-link> tags with <a> tags
    function replace_red_link_tags($matches)
    {
        // Extract data-page-exists attribute value
        preg_match('/data-page-exists="([^"]*)"/', $matches[1], $attr_matches);
        preg_match('/href="([^"]*)"/', $matches[1], $href_matches);

        // $page_exists = isset($attr_matches[1]) ? $attr_matches[1] : "";
        $href = isset($href_matches[1]) ? $href_matches[1] : "#";

        // Set color based on data-page-exists attribute value
        $color = "red";
        $link = $href;

        if (!empty($href)) {
            // Get post object by path (assuming href is a relative path)
            // $post = get_page_by_path($href, OBJECT, ["post", "page"]);
            // return $href;
            $args = [
                "post_type" => ["post", "page"], // Specify the post types to search
                "meta_query" => [
                    [
                        "key" => "red_link_id", // Replace 'your_meta_key' with the actual meta key
                        "value" => $href, // Value to search for
                        "compare" => "=", // Exact match
                    ],
                ],
                "posts_per_page" => 1, // Limit to 1 post
            ];

            $posts = get_posts($args);

            // If post exists, set color to blue
            if ($posts) {
                $color = "blue";
                $link = get_permalink($posts[0]->ID);
            }
        }

        if (current_user_can("edit_posts") && $color == "red") {
            // Update $href to a link for creating the page
            $slug = $this->slugify($matches[2]);
            $link = admin_url(
                "post-new.php?post_type=page&post_title=$slug&red_link_id=$href"
            );
        }

        // Replace <red-link> with <a> tag and set color
        return '<a href="' .
            $link .
            '" style="color: ' .
            $color .
            '"' .
            $matches[1] .
            ">" .
            $matches[2] .
            "</a>";
    }

    function slugify($text)
    {
        // Replace non letter or digits by -
        $text = preg_replace("~[^\pL\d]+~u", "-", $text);

        // Transliterate
        $text = iconv("utf-8", "us-ascii//TRANSLIT", $text);

        // Remove unwanted characters
        $text = preg_replace("~[^-\w]+~", "", $text);

        // Trim
        $text = trim($text, "-");

        // Lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return "n-a";
        }

        return $text;
    }
}
