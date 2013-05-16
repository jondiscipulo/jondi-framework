<?php

/**
 * Registry | PHP 5
 * Build 2013.05.16
 *
 * Copyright (c) 2013
 * Jonathan Discipulo <jonathan.discipulo@gmail.com>
 * https://github.com/jondiscipulo/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * http://www.gnu.org/copyleft/lesser.html
 *
**/

/** The registry class acts as the main registry of key-value pairs used throughout the app **/
class Registry {

	/** @type array $properties|array() This contains all the key-value properties */
	private $properties = array();

	/**
	 * This method sets the key-value pair
	 *
	 * @param string $key String to act as index representing any mixed value
	 * @param mixed $value Any mixed value represented the key parameter
	 * @return void
	**/
   public function __set($key, $value) {
		$this->properties[$key] = $value;
	}

	/**
	 * This method gets the value of a key
	 *
	 * @param string $key Index to be fetched on the registry
	 * @return mixed
	**/
	public function __get($key) {
		return $this->properties[$key];
	}

}

?>
