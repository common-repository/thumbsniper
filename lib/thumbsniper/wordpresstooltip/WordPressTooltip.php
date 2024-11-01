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
use ThumbSniper\tooltip\Tooltip;


class WordPressTooltip
{
    private $tooltip;


    function __construct()
    {
        WordPressTooltipOptions::loadOptions();
        $this->tooltip = new Tooltip();
    }


    public function enqueueScripts() {
        wp_enqueue_style('qtip', TooltipSettings::getQtipCssUrl(), null, false, false);
        wp_add_inline_style('qtip', $this->tooltip->getInlineCss());

        wp_enqueue_script('imagesloaded', TooltipSettings::getImagesLoadedUrl(), array('jquery'), null, true);
        wp_enqueue_script('qtip', TooltipSettings::getQtipUrl(), array('imagesloaded'), null, true);

    }


    public function printInlineScripts() {
        global $post;

	    $include = true;

	    if(in_array(TooltipSettings::getPreview(), array('all', 'external'))) {
		    $includePostIds = WordPressTooltipSettings::getIncludePostIds();
		    if ( is_array( $includePostIds ) && ! empty( $includePostIds ) ) {
			    if (!isset($post) || !is_single() || (isset( $post ) && ! in_array( $post->ID, WordPressTooltipSettings::getIncludePostIds() ))) {
				    $include = false;
			    }
		    }
	    }

        if($include && (!isset($post) || get_post_meta($post->ID, 'thumbsniper_hide', true) != "1")) {
            print($this->tooltip->getInlineScripts());
        }
    }


    public function addPlugin()
    {
        add_action('wp_enqueue_scripts', array(&$this, 'enqueueScripts'));

        add_action(
            $tag = 'wp_footer',
            $function_to_add = array(&$this, 'printInlineScripts')
        );

	    if(WordPressTooltipSettings::isShowFooterLink() == true) {
		    add_action(
			    $tag = 'wp_footer',
			    $function_to_add = array( &$this, 'printFooter' )
		    );
	    }
    }


	public function printFooter()
	{
		$out = "\n<!-- ThumbSniper footer - start -->\n";
		$out.= '<div style="text-align:center; font-size: xx-small; padding: 7px;">
			ThumbSniper-Plugin by <a href="http://thumbsniper.com" title="Thomas Schulte">Thomas Schulte</a>
			</div>';
		$out.= "\n<!-- ThumbSniper footer - end -->\n";

		print($out);
	}
}
