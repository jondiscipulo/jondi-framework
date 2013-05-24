<?php

/**
 * Backpack | PHP 5
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

/** Backpack Class Synopsis
 *
 * isRunningLocally - boolean
 * isHttps - boolean
 * getUrlScheme - string
 * getEpoch - string
 * getMicroEpoch - string
 * getNow - string
 * getNowUtc - string (utc)
 * getCacheBuster - string (md5(time()))
 * getHostUrl - string
 * getServerIp - string
 * getServerPort - string
 * getClientIp - string
 * getClientPort - string
 * getQueryString - string
 * stringToArray - array
 * stringToJson - string
 * lzwd - string
 * lzwc - binary
 * entitize - string
 * 
**/
class Backpack {

	/** class construct **/
	public function __construct() {}
	
	/** is server running locally? **/
	public function isRunningLocally() {
		return ((($this->getServerIp())=='localhost') || (($this->getServerIp())=='127.0.0.1')) ? true : false;
	}
	
	/** get unix epoch **/
	public function getEpoch() {
		return time();
	}

	/** Get micro unix epoch **/
	public function getMicroEpoch() {
		return microtime();
	}

	/** get current local server date and time **/
	public function getNow( $format='Y-m-d H:i:s' ) {
		return date($format, strtotime('now'));
	}

	/** Get current universal coordinated (UTC) date and time **/
	public function getNowUtc( $format='Y-m-d H:i:s' ) {
		return gmdate($format, strtotime('now'));
	}

	/** Get md5 of local unix time for cache busting **/
	public function getCacheBuster() {
		return md5(time());
	}
	
	/** get url scheme used: http or https **/
	public function getUrlScheme() {
		return ($this->isHttps()) ? 'https' : 'http';
	}

	/** get server ip address **/
	public function getServerIp() {
		return (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : null;
	}

	/** get client ip address **/
	public function getClientIp() {
		return (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : null;
	}

	/** get client port **/
	public function getClientPort() {
		return (isset($_SERVER['REMOTE_PORT'])) ? $_SERVER['REMOTE_PORT'] : null;
	}

	/** get client user agent **/
	public function getClientUserAgent() {
		return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null;
	}

	/** get query string **/
	public function getQueryString() {
		return (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : null;
	}
	
	/** get server port **/
	public function getServerPort() {
		return (isset($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : null;
	}

	/** get request uri **/
	public function getRequestUri() {
		return (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : null;
	}

	/** get server name **/
	public function getServerName() {
		return (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : null;
	}

	/** get host name **/
	public function getHostName() {
		return (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : null;
	}

	/** get https value **/
	public function getHttpsValue() {
		return (isset($_SERVER['HTTPS'])) ? $_SERVER['HTTPS'] : null;
	}

	/** is https? **/
	public function isHttps() {
		//if (!empty($this->getHttpsValue())) {
			$result = ($this->getHttpsValue() == 'on') ? true : false;
		//} else {
			$result = (intval($this->getServerPort()) == 443) ? true : false;
		//}
		return $result;
	}
	
	/** get host url **/
	public function getUrlHost() {
		return $this->getUrlScheme() . '://' . $this->getHostName() . '/';
	}
	
	/** get current url **/
	function getUrl() {
		$url = null;
		if (intval($this->getServerPort()) !== 80) {
			$url .= $this->getServerName() . ':' . $this->getServerPort() . $this->getRequestUri();
		} else {
			$url .= $this->getServerName() . $this->getRequestUri();
		}
		return $url;
	}
	
	/** string to array **/
	public function stringToArray( $query ) {
		parse_str($query, $array);
		return $array;
	}
	
	/** string to json **/
	public function stringToJson( $query ) {
		return json_encode( stringToArray($query) );
	}
	
	/** lzw decompression **/
	function lzwd($binary) {
		// convert binary string to codes
		$dictionary_count = 256;
		$bits = 8; // ceil(log($dictionary_count, 2))
		$codes = array();
		$rest = 0;
		$rest_length = 0;
		for ($i=0; $i < strlen($binary); $i++) {
			$rest = ($rest << 8) + ord($binary[$i]);
			$rest_length += 8;
			if ($rest_length >= $bits) {
				$rest_length -= $bits;
				$codes[] = $rest >> $rest_length;
				$rest &= (1 << $rest_length) - 1;
				$dictionary_count++;
				if ($dictionary_count > (1 << $bits)) {
					$bits++;
				}
			}
		}
		
		// decompression
		$dictionary = range("\0", "\xFF");
		$return = "";
		foreach ($codes as $i => $code) {
			$element = $dictionary[$code];
			if (!isset($element)) {
				$element = $word . $word[0];
			}
			$return .= $element;
			if ($i) {
				$dictionary[] = $word . $element[0];
			}
			$word = $element;
		}
		return $return;
	}

	/** lzw compression **/
	public function lzwc($string) {
		// compression
		$dictionary = array_flip(range("\0", "\xFF"));
		$word = "";
		$codes = array();
		for ($i=0; $i <= strlen($string); $i++) {
			$x = $string[$i];
			if (strlen($x) && isset($dictionary[$word . $x])) {
				$word .= $x;
			} elseif ($i) {
				$codes[] = $dictionary[$word];
				$dictionary[$word . $x] = count($dictionary);
				$word = $x;
			}
		}
		
		// convert codes to binary string
		$dictionary_count = 256;
		$bits = 8; // ceil(log($dictionary_count, 2))
		$return = '';
		$rest = 0;
		$rest_length = 0;
		foreach ($codes as $code) {
			$rest = ($rest << $bits) + $code;
			$rest_length += $bits;
			$dictionary_count++;
			if ($dictionary_count > (1 << $bits)) {
				$bits++;
			}
			while ($rest_length > 7) {
				$rest_length -= 8;
				$return .= chr($rest >> $rest_length);
				$rest &= (1 << $rest_length) - 1;
			}
		}
		return $return . ($rest_length ? chr($rest << (8 - $rest_length)) : '');
	}
	
	/** convert string into html entities **/
	public function entitize( $string ) {
        for ($x=0; $x<=strlen($string)-1; $x++) {
            $ord = sprintf("%03u", ord(substr($string, $x, 1)));
            $result = $result . chr(38) . chr(35) . $ord . chr(59);
        }
        return $result;
    }	

	/** convert string into html entities **/
	public function displayObject( $object ) {
		if ($object !== null) return "<xmp>" . print_r($object, true) . "</xmp>";
		
	}
	
	/** convert backslashes to forward slashes **/
	public function backToForward( $string ) {
		return str_replace('\\', '/', $string);
	}
	
	/** display link **/
	public function displayLink( $url=null, $title=null ) {
		if ($url !== null) {
			if ($title !== null) {
				return "<a href=\"{$url}\">" . $title . "</a>";
			} else {
				return "<a href=\"{$url}\">" . $url . "</a>";
			}
		}
	}
	
	/** display breadcrumbs **/
	function displayBreadcrumbs( $home='home', $separator='&raquo;' ) {
		$result = null;
		$uri = null;
		$x = 0;
		$crumbs = explode('/', $this->getRequestUri(), -1);
		foreach ($crumbs as $crumb) {
			if ($crumb != '') {
				$uri .= $crumb . '/';
				$breadcrumbs[$crumb] = $this->getUrlHost() . $uri;
				$x++;
			} else {
				$breadcrumbs[$home] = $this->getUrlHost();
			}
		}
		$y = count($breadcrumbs)-1;
		foreach ($breadcrumbs as $key => $value) {
			$result .= '<a href="' . $value . '">' . $key . '</a> ' . (($y <= 0) ? '' : $separator . ' ');
			$y--;
		}
		return $result;
	}
	
	/** compare if equal **/
	public function isEqual( $first, $second ) {
		return ($first === $second) ? true : false;
	}	
	
	/** magic method: sleep **/
    public function __sleep() {}
    
	/** magic method: wake up **/
    public function __wakeup() {}

	/** destructor **/
	public function __destruct() {}

}

?>
