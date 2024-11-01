<?php
/*
Plugin Name: ThumbSniper
Plugin URI: http://thumbsniper.com
Description: This plugin dynamically shows preview screenshots of hyperlinks as tooltips on your WordPress site.
Author: Thomas Schulte
Version: 2.9.7
Author URI: http://www.mynakedgirlfriend.de
Text Domain: thumbsniper

Copyright (C) 2016  Thomas Schulte <thomas@cupracer.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require (dirname(__FILE__) . '/vendor/autoload.php');


use ThumbSniper\common\WordPressTooltipSettings;
use ThumbSniper\wordpresstooltip\WordPressTooltip;
use ThumbSniper\wordpresstooltip\WordPressTooltipOptions;

// plugin-specific settings
$options = get_option('thumbsniper_options');

if(is_array($options)) {
	$includePostIds = isset( $options['include_posts'] ) ? $options['include_posts'] : null;
	if ( ! empty( $includePostIds ) ) {
		WordPressTooltipSettings::setIncludePostIds( preg_split( "/;/", $includePostIds ) );
	}

	$showFooterLink = isset( $options['show_footer_link'] ) ? $options['show_footer_link'] : null;
	if ( ! empty( $showFooterLink ) ) {
		WordPressTooltipSettings::setShowFooterLink( $showFooterLink == "yes" ? true : false );
	}
}

if(is_admin()) {
    $thumbsniper_admin = new WordPressTooltipOptions();
}else {
    $thumbsniper = new WordPressTooltip();
    $thumbsniper->addPlugin();
}
