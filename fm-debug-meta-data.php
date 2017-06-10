<?php

/*
  Plugin Name: Debug User/Post Meta Data
  Plugin URI: http://google.com/
  Description: Display User and Post Meta data in Admin panel to easyly debug data
  Version: 1.0.0
  Author: Francisco Mauri
  Author URI: http://fran-mauri/
  License: GPLv2 or later
 */

define('FM_DEBUG_META_VERSION', '1.0.0');

define('FM_DEBUG_META_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FM_DEBUG_META_PLUGIN_DIR', plugin_dir_path(__FILE__));

class Fm_Debug_Meta_data {

    var $item_count = 0;
    var $has_folder = false;

    function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('wp_ajax_get_meta_data', [$this, 'wp_ajax_get_meta_data']);
    }

    /**
     * 
     * Build HTML tree folders structure for arrays meta data
     *
     * @param array $data  the user or post unserialized meta data
     * @return string
     */
    function build_tree_data($data) {
        if (!is_array($data))
            return $data;
        $tree = '';

        foreach ($data as $key => $val) {
            $this->item_count++;
            if (is_array($val)) {
                $tree.='<li><input type="checkbox" id="item-' . $this->item_count . '" /><label for="item-' . $this->item_count . '" class="folder-tree">' . $key . '</label><ul>' . $this->build_tree_data($val) . '</ul>';
                $this->has_folder = true;
            } else {
                $tree.='<li><span class="meta-data-value"><span class="key-tree">' . $key . '</span> = <span class="val-tree">' . $val . '</span></span></li>';
            }
        }
        return $tree;
    }

    /**
     * 
     * Build HTML table in meta data list
     *
     * @param array $data  the user or post meta data
     * @return string
     */
    function build_data($data) {
        $html = '';
        if ($data) {
            foreach ($data as $key => $val) {
                $this->has_folder = false;
                $expand = '';
                if (is_array($val))
                    $val = $val[0];
                if (!$val)
                    $val = '&nbsp;';

                $unserialized_data = @unserialize($val);
                if (($val === 'b:0;' || $unserialized_data !== false) && (is_array($unserialized_data) && $count = count($unserialized_data))) {
                    $expand = '<h4>Serialized:</h4>';
                    $val = '<div class="css-treeview">
                            <ul>' . $this->build_tree_data($unserialized_data) . '</ul>
                        </div>';
                } else {
                    
                }

                if ($this->has_folder)
                    $expand = '<h4>Serialized:</h4><span class="expand-group"><label for="expand">Expand All <input type="checkbox" id="expand" class="expand" /></span>';



                $html.='<tr><td>' . $key . '</td>
                            <td>' . $expand . $val . '</td>
                                </tr>';
            }
        } else {
            $html.='<tr><td colspan="2">No Meta Data Found</td></tr>';
        }
        return $html;
    }
    
    /**
     * 
     * Manage ajax request for returning meta data
     *
     */
    function wp_ajax_get_meta_data() {

        if ($_POST['type'] == 'select-user') {
            $data = get_user_meta($_POST['id']);
        }
        if ($_POST['type'] == 'select-post') {
            $data = get_post_meta($_POST['id']);
        }

        wp_die($this->build_data($data));
    }

    
    /**
     * 
     * Create admin menu item under tools
     *
     */
    function admin_menu() {
        add_submenu_page('tools.php', 'FM Debug Meta Data', 'FM Debug Meta Data', 'manage_options', 'Fm_debug_meta_data', [$this, 'display_debug_meta_data']);
    }

    
    /**
     * 
     * Display Debug User/Post Meta Data screen
     *
     */
    function display_debug_meta_data() {
        $users = get_users();
        $user_select = '';
        foreach ($users as $user) {

            $user_select.='<option value="' . $user->data->ID . '">' . $user->data->user_login . '</option>';
        }

        $posts = get_posts();
        $post_select = '';
        foreach ($posts as $post) {

            $post_select.='<option value="' . $post->ID . '">' . $post->post_type . ' / ' . $post->post_title . '</option>';
        }




        require_once(FM_DEBUG_META_PLUGIN_DIR . '/view-debug-meta-data.php');
    }

}

if (is_admin())
    new Fm_Debug_Meta_data ();


