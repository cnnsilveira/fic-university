<?php

/*
    Plugin Name: Word Filter
    Description: Replaces filtered words.
    Version: 1.0.0
    Author: Caio Nunes
    Author URI: https://github.com/cnnsilveira
    Text Domain: wfdomain
    Domain Path: /lang
*/

// if (!defined(ABSPATH)) exit;

class WordFilterPlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_init', array($this, 'ourSettings'));
        if (get_option('plugin_words_to_filter')) add_filter('the_content', array($this, 'filterLogic'));
        add_action('init', array($this, 'languages'));
    }

    function languages() {
        load_plugin_textdomain('wfdomain', false, dirname(plugin_basename(__FILE__)).'/lang');
    }

    function ourSettings() {
        add_settings_section('replacement-text-section', null, null, 'word-filter-options');
        register_setting('replacementFields', 'replacementText');
        add_settings_field('replacement-text', esc_html__('Filtered Text', 'wfdomain'), array($this, 'replacementFieldHTML'), 'word-filter-options', 'replacement-text-section');
    }

    function replacementFieldHTML() { ?>
        <input type="text" name="replacementText" value="<?php echo esc_attr(get_option('replacementText', '#$%&!'));?>">
        <p class="description"><?php echo esc_html__('Leave blank to simply remove filtered words', 'wfdomain');?>.</p>
    <?php }

    function filterLogic($content) {
        $badWords = explode(',', get_option('plugin_words_to_filter'));
        $badWordsTrimmed = array_map('trim', $badWords);
        return str_ireplace($badWordsTrimmed, esc_html(get_option('replacementText', '#$%&!')), $content);
    }

    function menu() {
        $mainPageHook = add_menu_page(esc_html__('Words to filter', 'wfdomain'), esc_html__('Word Filter', 'wfdomain'), 'manage_options', 'word-filter', array($this, 'wordFilterPage'), 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+', 100);
        add_submenu_page('word-filter', esc_html__('Words to filter', 'wfdomain'), esc_html__('Home', 'wfdomain'), 'manage_options', 'word-filter', array($this, 'wordFilterPage'));
        add_submenu_page('word-filter', esc_html__('Word Filter Options', 'wfdomain'), esc_html__('Options', 'wfdomain'), 'manage_options', 'word-filter-options', array($this, 'optionsSubPage'));
        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets() {
        wp_enqueue_style('filterAdminCSS', plugin_dir_url(__FILE__).'style.css');
    }

    function handleForm() {
        if (wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') && current_user_can('manage_options')) {
            update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter'])); ?>
            <div class="updated">
                <p><?php echo esc_html__('Your filter words were saved', 'wfdomain');?>.</p>
            </div>
        <?php } else { ?>
            <div class="error">
                <p><?php echo esc_html__('Sorry, you do not have permission to perform that action', 'wfdomain');?>.</p>
            </div>
        <?php }
    }

    function wordFilterPage() { ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Word Filter', 'wfdomain');?></h1>
            <?php if (isset($_POST['justsubmitted']) && $_POST['justsubmitted'] == true) $this->handleForm(); ?>
            <form method="POST">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveFilterWords', 'ourNonce')?>
                <label for="plugin_words_to_filter"><?php echo esc_html__('Enter a', 'wfdomain');?> <strong><?php echo esc_html__('comma-separated', 'wfdomain');?></strong> <?php echo esc_html__('list of words to filter from your site\'s content', 'wfdomain');?></label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter" placeholder="<?php echo esc_html__('bad, mean, awful, horrible', 'wfdomain');?>"><?php echo esc_textarea(get_option('plugin_words_to_filter'));?></textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html__('Save changes', 'wfdomain');?>">
            </form>
        </div>
    <?php }

    function optionsSubPage() { ?>
        <div class="wrap">
            <h2><?php echo esc_html__('Word Filter Options', 'wfdomain');?></h2>
            <form action="options.php" method="POST">
                <?php 
                    settings_errors();
                    settings_fields('replacementFields');
                    do_settings_sections('word-filter-options');
                    submit_button();
                ?>
            </form>
        </div>
    <?php }
}

$wordFilterPlugin = new WordFilterPlugin();

?>