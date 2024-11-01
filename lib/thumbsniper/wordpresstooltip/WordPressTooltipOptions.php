<?php

/*
 * Copyright (C) 2016  Thomas Schulte <thomas@cupracer.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace ThumbSniper\wordpresstooltip;

use ThumbSniper\common\TooltipSettings;
use ThumbSniper\common\WordPressTooltipSettings;


class WordPressTooltipOptions
{
    const THUMBSNIPER_VERSION = "3000";


    function __construct()
    {
        self::loadOptions();

        $this->pluginUpdate();

        add_action(
            $tag = 'admin_menu',
            $function_to_add = array(&$this, 'addAdminMenu')
        );

        add_action(
            $tag = 'admin_init',
            $function_to_add = array(&$this, 'initAdmin')
        );

        add_action(
            $tag = 'save_post',
            $function_to_add = array( &$this, 'hide_save' ),
            $accepted_args = 1
        );
    }


    public static function loadOptions()
    {
        TooltipSettings::setSiteUrl(get_option("siteurl"));

        $tempoptions = get_option("thumbsniper_options");

        if(is_array($tempoptions)) {
            foreach ($tempoptions as $key => $value) {
                switch ($key) {
                    case "preview_type":
                        TooltipSettings::setPreview($value);
                        break;
                    case "width":
                        TooltipSettings::setWidth($value);
                        break;
                    case "effect":
                        TooltipSettings::setEffect($value);
                        break;
                    case "position":
                        TooltipSettings::setPosition($value);
                        break;
                    case "style":
                        TooltipSettings::setStyle($value);
                        break;
                    case "exclude_urls":
                        TooltipSettings::setExcludes($value);
                        break;
                    case "show_link_title":
                        TooltipSettings::setShowTitle($value == "yes");
                        break;
                }
            }
        }
    }


    private function pluginUpdate()
    {
        $version = get_option('thumbsniper');

        if($version != false && $version == self::THUMBSNIPER_VERSION) {
            return true;
        }

        //general options
	    $options = get_option('thumbsniper_options');

	    if(!is_array($options)) {
		    $options = array();
	    }

	    // preview - very old
        $preview = get_option('thumbsniper_preview');
        if(!empty($preview) && $preview != TooltipSettings::getPreview())
        {
	        $options['preview_type'] = $preview;
        }

	    // exclude urls - very old
        $exclude_urls = get_option('thumbsniper_excluded_urls');
        if(!empty($exclude_urls))
        {
            $oldurls = preg_split("/;/", $exclude_urls);
            $newurls = array();

            foreach($oldurls as $key => $value)
            {
                $newurls[] = $value . ".*";
            }

	        $options['exclude_urls'] = $newurls;
        }

	    //general options - old

	   $oldOptions = get_option('thumbsniper_general_settings');

	    if(is_array($oldOptions))
	    {
		    if(isset($oldOptions['thumbsniper_preview'])) {
			    $preview = $oldOptions['thumbsniper_preview'];
			    if ( ! empty( $preview ) && $preview != TooltipSettings::getPreview() ) {
				    $options['preview_type'] = $preview;
			    }
		    }

		    if(isset($oldOptions['thumbsniper_exclude_urls'])) {
			    $exclude_urls = $oldOptions['thumbsniper_exclude_urls'];
			    if ( ! empty( $exclude_urls ) ) {
				    $oldurls = preg_split( "/;/", $exclude_urls );
				    $newurls = array();

				    foreach ( $oldurls as $key => $value ) {
					    $newurls[] = $value . ".*";
				    }

				    $options['exclude_urls'] = $newurls;
			    }
		    }

		    if(isset($oldOptions['thumbsniper_background'])) {
			    $style = $oldOptions['thumbsniper_background'];
			    if ( ! empty( $style ) && $style != TooltipSettings::getStyle() ) {
				    $options['style'] = $style;
			    }
		    }

		    if(isset($oldOptions['thumbsniper_position'])) {
			    $position = $oldOptions['thumbsniper_position'];
			    if ( ! empty( $position ) ) {
				    $newPosition = null;
				    switch ( $position ) {
					    case "above":
						    $newPosition = 'top';
						    break;
					    case "below":
						    $newPosition = 'bottom';
						    break;
					    case "left":
						    $newPosition = 'left';
						    break;
					    case "right":
						    $newPosition = 'right';
						    break;
				    }
				    if ( $newPosition && $newPosition != TooltipSettings::getPosition() ) {
					    $options['position'] = $newPosition;
				    }
			    }
		    }

		    if(isset($oldOptions['thumbsniper_variant'])) {
			    $effect = $oldOptions['thumbsniper_variant'];
			    if ( $effect != null ) {
				    $newEffect = null;
				    switch ( $effect ) {
					    case "0":
						    $newEffect = 'plain';
						    break;
					    case "1":
						    $newEffect = 'fade1';
						    break;
					    case "2":
						    $newEffect = 'fade2';
						    break;
				    }
				    if ( $newEffect && $newEffect != TooltipSettings::getEffect() ) {
					    $options['effect'] = $newEffect;
				    }
			    }
		    }

		    if(isset($oldOptions['thumbsniper_include_pages'])) {
			    $includePosts = $oldOptions['thumbsniper_include_pages'];
			    if ( ! empty( $includePosts ) ) {
				    $options['include_posts'] = $includePosts;
			    }
		    }

		    if(isset($oldOptions['thumbsniper_showfooter'])) {
			    $showFooterLink = $oldOptions['thumbsniper_showfooter'];
			    if ( ! empty( $showFooterLink ) && $showFooterLink != 'no' ) {
				    $options['show_footer_link'] = $showFooterLink;
			    }
		    }
	    }

        if(!empty($options)) {
	        if(update_option('thumbsniper_options', $options)) {
		        delete_option('thumbsniper_version');
		        delete_option('thumbsniper_preview');
		        delete_option('thumbsniper_showfooter');
		        delete_option('thumbsniper_scaling');
		        delete_option('thumbsniper_opacity');
		        delete_option('thumbsniper_code_placement');
		        delete_option('thumbsniper_excluded_urls');
		        delete_option('thumbsniper_bgcolor');
		        delete_option('thumbsniper_include_pages');
		        delete_option('thumbsniper_general_settings');
		        update_option('thumbsniper', self::THUMBSNIPER_VERSION);
	        }
        }

        return true;
    }


    public function addAdminMenu()
    {
        //ensure, that the needed javascripts been loaded to allow drag/drop, expand/collapse and hide/show of boxes
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');

        add_options_page(
            'ThumbSniper Options',
            'ThumbSniper',
            'manage_options',
            'thumbsniper',
            array(&$this, 'printOptionsPage')
        );

        $current_user_role = $this->getCurrentUserRole();

        if($current_user_role && ($current_user_role == "Administrator" || $current_user_role == "Editor")) {
            add_meta_box(
                $id = 'thumbsniper_hide',
                $title = 'ThumbSniper',
                $callback = array(&$this, 'hide'),
                $post_type = 'post',
                $context = 'advanced',
                $priority = 'default'
            );

            add_meta_box(
                $id = 'thumbsniper_hide',
                $title = 'ThumbSniper',
                $callback = array(&$this, 'hide'),
                $post_type = 'page',
                $context = 'advanced',
                $priority = 'default'
            );
        }
    }


    public function initAdmin()
    {
        register_setting(
            'thumbsniper_general', // Option group
            'thumbsniper_options', // Option name
            array(&$this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'thumbsniper_section_general', // Section ID
            'General Settings', // Section Title
            array(&$this, 'settingsSectionCallback'), // Callback
            'thumbsniper' // What Page?  This makes the section show up on the General Settings Page
        );

        add_settings_field( // Option
            'preview_type', // Option ID
            'preview type', // Label
            array(&$this, 'settingsFieldCallbackPreview'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );

        add_settings_field( // Option
            'width', // Option ID
            'width', // Label
            array(&$this, 'settingsFieldCallbackWidth'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );

        add_settings_field( // Option
            'effect', // Option ID
            'effect', // Label
            array(&$this, 'settingsFieldCallbackEffect'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );

        add_settings_field( // Option
            'position', // Option ID
            'position', // Label
            array(&$this, 'settingsFieldCallbackPosition'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );

        add_settings_field( // Option
            'style', // Option ID
            'style', // Label
            array(&$this, 'settingsFieldCallbackStyle'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );

        add_settings_field( // Option
            "exclude_urls", // Option ID
            "exclude URL's", // Label
            array(&$this, 'settingsFieldCallbackExcludeUrls'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );

	    add_settings_field( // Option
		    "include_posts", // Option ID
		    "include in posts only", // Label
		    array(&$this, 'settingsFieldCallbackIncludePosts'), // !important - This is where the args go!
		    'thumbsniper', // Page it will be displayed (General Settings)
		    'thumbsniper_section_general' // Name of our section
	    );

        add_settings_field( // Option
            "show_link_title", // Option ID
            "show link title", // Label
            array(&$this, 'settingsFieldCallbackShowLinkTitle'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );

        add_settings_field( // Option
            "show_footer_link", // Option ID
            "admit link in footer", // Label
            array(&$this, 'settingsFieldCallbackShowFooterLink'), // !important - This is where the args go!
            'thumbsniper', // Page it will be displayed (General Settings)
            'thumbsniper_section_general' // Name of our section
        );
    }


    public function printOptionsPage() {
        printf('<div class="wrap">
                <form method="post" action="options.php">');

        settings_fields('thumbsniper_general');
        do_settings_sections('thumbsniper');
        submit_button();

        printf('</form></div>');
    }


    public function settingsSectionCallback() {
	    $out = '<p><strong>Hints</strong></p>';
	    $out.= '<ol>';
	    $out.= '<li>You may use the CSS class &quot;nothumbsniper&quot; to explicitly disable
			the preview tooltip for single hyperlinks when using the preview types
			&quot;all&quot; or &quot;external&quot;.</li>';
	    $out.= '<li>While editing posts or pages you can use the &quot;Disable ThumbSniper on this page&quot;
			checkbox to exclude internal single posts or pages.</li>';
	    $out.= '</ol>';

	    printf($out);
    }


    public function settingsFieldCallbackPreview() {
	    $out = '<select name="thumbsniper_options[preview_type]">';
        $out.= '<option value="all"' . (TooltipSettings::getPreview() == 'all' ? ' selected' : '') . '>all</option>';
        $out.= '<option value="external"' . (TooltipSettings::getPreview() == 'external' ? ' selected' : '') . '>external</option>';
        $out.= '<option value="marked"' . (TooltipSettings::getPreview() == 'marked' ? ' selected' : '') . '>marked</option>';
        $out.= '</select>';
	    $out.= '<p>Tooltips can be used for three types of hyperlinks.
			&quot;All&quot; just means all links that exist on a page and &quot;external&quot; hides the thumbshots
			for internal links. Using the option value &quot;marked&quot; means, that the tooltip-thumbshots are only
			shown if a link has a style class named &quot;thumbsniper&quot;.</p>';

        printf($out);
    }


    public function settingsFieldCallbackWidth() {
        $out = '<select name="thumbsniper_options[width]">';
        $out.= '<option value="91"' . (TooltipSettings::getWidth() == '91' ? ' selected' : '') . '>91</option>';
        $out.= '<option value="104"' . (TooltipSettings::getWidth() == '104' ? ' selected' : '') . '>104</option>';
        $out.= '<option value="121"' . (TooltipSettings::getWidth() == '121' ? ' selected' : '') . '>121</option>';
        $out.= '<option value="146"' . (TooltipSettings::getWidth() == '146' ? ' selected' : '') . '>146</option>';
        $out.= '<option value="182"' . (TooltipSettings::getWidth() == '182' ? ' selected' : '') . '>182</option>';
        $out.= '<option value="242"' . (TooltipSettings::getWidth() == '242' ? ' selected' : '') . '>242</option>';
        $out.= '<option value="365"' . (TooltipSettings::getWidth() == '365' ? ' selected' : '') . '>365</option>';
        $out.= '</select>';
	    $out.= '<p>The corresponding height is set automatically.</p>';

        printf($out);
    }


    public function settingsFieldCallbackEffect() {
        $out = '<select name="thumbsniper_options[effect]">';
        $out.= '<option value="plain"' . (TooltipSettings::getEffect() == 'plain' ? ' selected' : '') . '>plain</option>';
        $out.= '<option value="blur1"' . (TooltipSettings::getEffect() == 'blur1' ? ' selected' : '') . '>blur1</option>';
        $out.= '<option value="blur2"' . (TooltipSettings::getEffect() == 'blur2' ? ' selected' : '') . '>blur2</option>';
        $out.= '<option value="button1"' . (TooltipSettings::getEffect() == 'button1' ? ' selected' : '') . '>button1</option>';
        $out.= '<option value="curly"' . (TooltipSettings::getEffect() == 'curly' ? ' selected' : '') . '>curly</option>';
        $out.= '<option value="fade1"' . (TooltipSettings::getEffect() == 'fade1' ? ' selected' : '') . '>fade1</option>';
        $out.= '<option value="fade2"' . (TooltipSettings::getEffect() == 'fade2' ? ' selected' : '') . '>fade2</option>';
        $out.= '<option value="polaroid1"' . (TooltipSettings::getEffect() == 'polaroid1' ? ' selected' : '') . '>polaroid1</option>';
        $out.= '<option value="tornpaper1"' . (TooltipSettings::getEffect() == 'tornpaper1' ? ' selected' : '') . '>tornpaper1</option>';
        $out.= '</select>';

        printf($out);
    }


    public function settingsFieldCallbackPosition() {
        $out = '<select name="thumbsniper_options[position]">';
        $out.= '<option value="bottom"' . (TooltipSettings::getPosition() == 'bottom' ? ' selected' : '') . '>bottom</option>';
        $out.= '<option value="left"' . (TooltipSettings::getPosition() == 'left' ? ' selected' : '') . '>left</option>';
        $out.= '<option value="right"' . (TooltipSettings::getPosition() == 'right' ? ' selected' : '') . '>right</option>';
        $out.= '<option value="top"' . (TooltipSettings::getPosition() == 'top' ? ' selected' : '') . '>top</option>';
        $out.= '</select>';
		$out.= '<p>The position is set relatively to the hyperlink.</p>';
        printf($out);
    }


    public function settingsFieldCallbackStyle() {
        $out = '<select name="thumbsniper_options[style]">';
        $out.= '<option value="blue"' . (TooltipSettings::getStyle() == 'blue' ? ' selected' : '') . '>blue</option>';
        $out.= '<option value="bootstrap"' . (TooltipSettings::getStyle() == 'bootstrap' ? ' selected' : '') . '>bootstrap</option>';
        $out.= '<option value="dark"' . (TooltipSettings::getStyle() == 'dark' ? ' selected' : '') . '>dark</option>';
        $out.= '<option value="green"' . (TooltipSettings::getStyle() == 'green' ? ' selected' : '') . '>green</option>';
        $out.= '<option value="jtools"' . (TooltipSettings::getStyle() == 'jtools' ? ' selected' : '') . '>jtools</option>';
        $out.= '<option value="light"' . (TooltipSettings::getStyle() == 'light' ? ' selected' : '') . '>light</option>';
        $out.= '<option value="red"' . (TooltipSettings::getStyle() == 'red' ? ' selected' : '') . '>red</option>';
        $out.= '<option value="tipped"' . (TooltipSettings::getStyle() == 'tipped' ? ' selected' : '') . '>tipped</option>';
        $out.= '<option value="tipsy"' . (TooltipSettings::getStyle() == 'tipsy' ? ' selected' : '') . '>tipsy</option>';
        $out.= '<option value="youtube"' . (TooltipSettings::getStyle() == 'youtube' ? ' selected' : '') . '>youtube</option>';
        $out.= '</select>';

        printf($out);
    }


	public function settingsFieldCallbackIncludePosts() {
		$includePostIds = WordPressTooltipSettings::getIncludePostIds();
		$includePosts = "";

		$includePostCounter = 1;
		foreach($includePostIds as $includePostId) {
			$includePosts.= $includePostId;
			if($includePostCounter < count($includePostIds)) {
				$includePosts .= ";";
			}
			$includePostCounter++;
		}

		$out = '<input type="text" name="thumbsniper_options[include_posts]" placeholder="12;28;104" value="' . ($includePosts ? $includePosts : '') . '">';
		$out.= '<p>While in &quot;external&quot; or &quot;all&quot; mode, you can limit the plugin to explicitly defined
			numeric page id&#39;s (semicolon-separated). Just leave it empty to ignore this feature.</p>';

		printf($out);
	}


    public function settingsFieldCallbackExcludeUrls() {
        $out = '<textarea name="thumbsniper_options[exclude_urls]" rows="6" placeholder="http://www.foobar.com/somepath/.*" class="large-text code">';

        foreach(TooltipSettings::getExcludes() as $exclude) {
            $out.= esc_textarea($exclude) . "\n";
        }

        $out.= '</textarea>';
	    $out.= '<p>Use the &quot;excluded URLs&quot; option to define (yes, you guessed it already) excluded URLs.
			These URLs have to be entered as one per line. It works only with the preview types &quot;all&quot;
			or &quot;external&quot; and follows the basic rules for JavaScript regular expressions. Like this:<br>
			<pre>http://www.foobar.com/somepath/.*<br>
http://www.foobar.com/somepath.*.pdf<br>
http://.*.foobar.com/</pre></p>';

        printf($out);
    }


	public function settingsFieldCallbackShowFooterLink() {
		$out = '<select name="thumbsniper_options[show_footer_link]">';
		$out.= '<option value="yes"' . (WordPressTooltipSettings::isShowFooterLink() ? ' selected' : '') . '>yes</option>';
		$out.= '<option value="no"' . (WordPressTooltipSettings::isShowFooterLink() ? '' : ' selected') . '>no</option>';
		$out.= '</select>';

		$out.= '<p>Providing you and your visitors with the freshest thumbnails takes a lot of server resources which
 			isn&#39;t free of charge for me. I&#39;m providing these resources at no cost, because that&#39;s my hobby.</p>
			<p><strong>Instead of demanding a fee, I would be glad if you would spend me a backlink on your site.</strong>
			You may either use the automatic footer link or place a hyperlink somewhere else on your site which should be like that:<br>
			<pre><code>&lt;a href="http://thumbsniper.com" title="ThumbSniper" target="_blank"&gt;ThumbSniper&lt;/a&gt;</code></pre></p>';

		printf($out);
	}


    public function settingsFieldCallbackShowLinkTitle() {
        $out = '<select name="thumbsniper_options[show_link_title]">';
        $out.= '<option value="yes"' . (TooltipSettings::isShowTitle() ? ' selected' : '') . '>yes</option>';
        $out.= '<option value="no"' . (TooltipSettings::isShowTitle() ? '' : ' selected') . '>no</option>';
        $out.= '</select>';

        $out.= '<p>Show original title attribute of a hyperlink in the tooltip. Example code:<br>
			<pre><code>&lt;a href="http://thumbsniper.com" title="This is ThumbSniper.com"&gt;ThumbSniper&lt;/a&gt;</code></pre></p>';

        printf($out);
    }


    public function sanitize($input)
    {
        $new_input = array();

        if(isset($input['preview_type'])) {
            $new_input['preview_type'] = sanitize_text_field($input['preview_type']);
        }

        if(isset($input['width'])) {
            $new_input['width'] = sanitize_text_field($input['width']);
        }

        if(isset($input['effect'])) {
            $new_input['effect'] = sanitize_text_field($input['effect']);
        }

        if(isset($input['position'])) {
            $new_input['position'] = sanitize_text_field($input['position']);
        }

        if(isset($input['style'])) {
            $new_input['style'] = sanitize_text_field($input['style']);
        }

        // exclude_urls
        $urls = $this->convertLineBreaks( $input['exclude_urls'], ";" );
        $urls = preg_split( "/;/", $urls );
        $newurls = array();

        foreach($urls as $url) {
            if(empty($url)) {
                continue;
            }
            $newurls[] = $url;
        }

        $new_input['exclude_urls'] = $newurls;

	    if(isset($input['include_posts'])) {
		    $includePosts = sanitize_text_field($input['include_posts']);

		    $new_input['include_posts'] = trim($includePosts, ";");
	    }

	    if(isset($input['show_footer_link'])) {
		    $new_input['show_footer_link'] = sanitize_text_field($input['show_footer_link']);
	    }

        if(isset($input['show_link_title'])) {
            $new_input['show_link_title'] = sanitize_text_field($input['show_link_title']);
        }

        return $new_input;
    }


    private function convertLineBreaks( $string, $line_break = PHP_EOL )
    {
        $patterns = array( "/(<br>|<br \/>|<br\/>)\s*/i", "/(\r\n|\r|\n)/" );
        $replacements = array( PHP_EOL, $line_break );
        $string = preg_replace( $patterns, $replacements, $string );

        return $string;
    }


    function hide_save($post_id) {
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if($_POST['post_type'] == "post" || $_POST['post_type'] == "page") {
            if(current_user_can('edit_post', $post_id)) {
                if(isset($_POST['thumbsniper_hide'])) {
                    update_post_meta($post_id, 'thumbsniper_hide', 1);
                }
                else {
                    delete_post_meta($post_id, 'thumbsniper_hide');
                }
            }
        }

        return $post_id;
    }



    private function getCurrentUserRole() {
        global $wp_roles;

	    if(isset($wp_roles)) {
		    $current_user = wp_get_current_user();

		    if ( $current_user ) {
			    $roles = $current_user->roles;
			    if ( is_array( $roles ) ) {
				    $role = array_shift( $roles );

				    return isset( $wp_roles->role_names[ $role ] ) ? preg_replace( "/\|User role$/", "", $wp_roles->role_names[ $role ] ) : false;
			    }
		    } else {
			    return null;
		    }
	    }else {
		    return null;
	    }
    }


    public function hide()
    {
        global $post;
        $thumbsniper_hide = get_post_meta($post->ID, 'thumbsniper_hide', true);

        if(isset($thumbsniper_hide) && $thumbsniper_hide == 1) {
            $thumbsniper_hide = ' checked="checked"';
        }
        else {
            $thumbsniper_hide = '';
        }

        $out = '<p><label for="thumbsniper_hide">';
        $out.= '<input name="thumbsniper_hide" value="1"' . $thumbsniper_hide . ' type="checkbox">';
        $out.= 'Disable ThumbSniper on this page.';
        $out.= '</label></p>';

        printf($out);
    }
}
