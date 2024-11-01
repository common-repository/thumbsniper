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

namespace ThumbSniper\common;


abstract class WordPressTooltipSettings {
	/** @var array */
	static private $includePostIds = array();
	/** @var boolean */
	static private $showFooterLink = 'no';

	/**
	 * @return array
	 */
	public static function getIncludePostIds() {
		return self::$includePostIds;
	}

	/**
	 * @param array $includePostIds
	 */
	public static function setIncludePostIds( $includePostIds ) {
		self::$includePostIds = $includePostIds;
	}

	/**
	 * @return boolean
	 */
	public static function isShowFooterLink() {
		return self::$showFooterLink;
	}

	/**
	 * @param boolean $showFooterLink
	 */
	public static function setShowFooterLink( $showFooterLink ) {
		self::$showFooterLink = $showFooterLink;
	}
}