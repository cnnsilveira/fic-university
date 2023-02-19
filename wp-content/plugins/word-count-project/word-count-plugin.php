<?php 

/*
    Plugin Name: Word Count Plugin
    Description: All I do is count the words and show the results to the reader. Includes the word count itself, the character count and the read time.
    Version: 1.0.0
    Author: Caio Nunes
    Author URI: https://github.com/cnnsilveira
    Text Domain: wcpdomain
    Domain Path: /lang
*/

class WordCountAndTimePlugin
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
        add_action('init', array($this, 'languages'));
    }

    function languages() {
        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)).'/lang');
    }

    function ifWrap($content) {
        if (is_main_query() && is_single() && 
            (
                get_option('wcp_wordcount', '1') || 
                get_option('wcp_charactercount', '1') || 
                get_option('wcp_readtime', '1')
            )
        ) {
            return $this->createHTML($content);
        }
        return $content;
    }

    function createHTML($content) {
        $html = '<h3>'.esc_html(get_option('wcp_headline', 'Post Statistics')).'</h3><p>';
        
        // 'wordcounter' for both wordcount and readtime
        if (get_option('wcp_wordcount', '1') || get_option('wcp_readtime', '1')) {
            $wordCount = str_word_count(strip_tags($content));
        }

        if (get_option('wcp_wordcount', '1')) {
            $html .= esc_html__('This post has', 'wcpdomain').' '.$wordCount.' '.esc_html__('words', 'wcpdomain').'.<br>';
        }
        // esc_html__('', 'wcpdomain')
        if (get_option('wcp_charactercount', '1')) {
            $html .= esc_html__('This post has', 'wcpdomain').' '.strlen(strip_tags($content)).' '.esc_html__('characters', 'wcpdomain').'.<br>';
        }

        // just a little costum fix for the time;
        $timeToRead = round($wordCount/225);

        if (get_option('wcp_readtime', '1')) {
            if ($timeToRead < 1) {
                $html .= esc_html__('This post will take less than a minute to read', 'wcpdomain').'.<br>';
            } elseif ($timeToRead == 1) {
                $html .= esc_html__('This post will take about a minute to read', 'wcpdomain').'.<br>';
            } else {
                $html .= esc_html__('This post will take about', 'wcpdomain').' '.$timeToRead.' '.esc_html__('minutes to read', 'wcpdomain').'.<br>';
            }
        }

        if (get_option('wcp_location', '0') == '0') {
            return $html.$content;
        }
        return $content.$html;
    }

    function settings() {
        add_settings_section(
            'wcp_first_section', // name of the section
            null, // subtitle
            null, // content
            'word-count-settings-page' // slug of the page it is going to appear ??
        );

        // Display Location Field
        add_settings_field('wcp_location', esc_html__('Display Location', 'wcpdomain'), array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));

        // Headline Text
        add_settings_field('wcp_headline', esc_html__('Headline Text', 'wcpdomain'), array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        // Word Count
        add_settings_field('wcp_wordcount', esc_html__('Word Count', 'wcpdomain'), array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
        register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        // Character count
        add_settings_field('wcp_charactercount', esc_html__('Character Count', 'wcpdomain'), array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
        register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        // Read Time
        add_settings_field('wcp_readtime', esc_html__('Read Time', 'wcpdomain'), array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
        register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    function sanitizeLocation($input) {
        if ($input != '0' && $input != '1') {
            add_settings_error(
                'wcp_location', // setting the error is related to
                'wcp_location_error', // slug of the error
                esc_html__('Display location must be either beginning or end', 'wcpdomain').'.' // message that will appear to the user
            );
            return get_option('wcp_location');
        }
        return $input;
    }

    function checkboxHTML($args) { ?>
        <input type="checkbox" name="<?php echo $args['theName']?>" value="1" <?php checked(get_option($args['theName']), '1')?>>
    <?php }

    function headlineHTML() { ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline'));?>" placeholder="<?php echo esc_html__('Name the counter', 'wcpdomain')?>">
    <?php }

    function locationHTML() { ?>
        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location'), '0');?>><?php echo esc_html__('Beginning of post', 'wcpdomain')?></option>
            <option value="1" <?php selected(get_option('wcp_location'), '1');?>><?php echo esc_html__('End of post', 'wcpdomain')?></option>
        </select>
    <?php }

    function adminPage() {
        add_options_page(
            esc_html__('Word Count Settings', 'wcpdomain'), // tab title
            esc_html__('Word Count', 'wcpdomain'), // title inside "Settings" menu
            'manage_options', // permission necessary to see it
            'word-count-settings-page', // slug
            array($this, 'ourHTML') // function that show the content
        );
    }
    
    function ourHTML() { ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Word Count Settings', 'wcpdomain')?></h1>
            <form action="options.php" method="POST">
                <?php
                    settings_fields('wordcountplugin');
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        </div>
    <?php }

}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();

?>