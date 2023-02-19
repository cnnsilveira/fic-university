<?php 
/*
    Plugin Name: Are You Paying Attention Quiz
    Description: Give your readers a multiple choice question.
    Version: 1.0.0
    Author: Caio Nunes
    Author URI: https://github.com/cnnsilveira
    Text Domain: aypadomain
    Domain Path: /lang
*/

class aypaPlugin 
{
    function __construct() {
        add_action('init', array($this, 'adminAssets'));
        add_action('init', array($this, 'languages'));
    }

    function adminAssets() {
        register_block_type(__DIR__, array(
            'render_callback' => array($this, 'theHTML')
        ));
    }

    function theHTML($attributes) {
        ob_start(); ?>
        <div class="paying-attention-update-me">
            <pre style="display: none"><?php echo wp_json_encode($attributes); ?></pre>
        </div>
    <?php return ob_get_clean();
    }

    function languages() {
        load_plugin_textdomain('aypadomain', false, dirname(plugin_basename(__FILE__)).'/lang');
    }
}

$aypaPlugin = new aypaPlugin();
?>