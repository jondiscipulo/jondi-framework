<?php

/**
 *	Session Class | PHP 5
 *	Build 2010.05.16
 *
 *	Copyright (c) 2010
 *	Jonathan Discipulo <me@jondiscipulo.com>
 *	http://jondiscipulo.com/
 *
 *	This library is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU Lesser General Public
 *	License as published by the Free Software Foundation; either
 *	version 2.1 of the License, or (at your option) any later version.
 *
 *	This library is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *	Lesser General Public License for more details.
 *
 *	You should have received a copy of the GNU Lesser General Public
 *	License along with this library; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 *  http://www.gnu.org/copyleft/lesser.html
 *
**/

// }}
// {{ Session
class Session {

	// subtract this value from current epoch to force-expire a cookie
	const day = 86400;
	const minute = 60;
	
	public $name;

	// }}
	// {{ Constructor
	public function __construct( $name = 'my_session' ) {
		
		$this->name = $name;
		return true;
	
	}

	// }}
	// {{ Redirect
	public function redirect( $url = 'index.php', $js = false ) {
		
		// redirect to assigned URL after making changes to session and/or cookies
		if ( $js ) {
			echo "<script type=\"text/javascript\">document.location = '{$url}';</script>";
		} else {
			@header('Location: ' . $url);
		}
		
	}	

	// }}
	// {{ Start
	public function start() {
		
		@session_name($this->name);
		@session_start();
		
	}	
	
	// }}
	// {{ Clear
	public function clear() {
		
		if ( isset($_COOKIE[$this->name]) ) {

			//$this->start();
			
			$_SESSION = array();
			
			if ( isset($_COOKIE[$this->name]) ) {
				@setcookie($this->name, '', time() - self::day, '/');
			}
			
		}
		
		@session_destroy();
	
	}	
	
	// }}
	// {{ Update/Create Session Variable
	function updateSession( $name, $value ) {
		
		$_SESSION[$name] = $value;
		
	}
	
	// }}
	// {{ Destroy Session Variable
	function destroySession( $name ) {
		
		$_SESSION[$name] = null;
		
	}
	
	// }}
	// {{ Update/Create Cookie Variable
	function updateCookie( $name, $value, $span, $path='/' ) {
		
		if ($span) {
			@setcookie($name, $value, time()+$span, $path);
		} else {
			@setcookie($name, $value, time()-self::minute, $path);
		}
		
	}

	// }}
	// {{ Destroy Cookie Variable
	function destroyCookie( $name ) {
		
		@setcookie($name, $value, time()-self::day, '/');
		
	}

	// }}
	// {{ Magic Method: Sleep
    public function __sleep() {
        // sleep methods should be placed here
    }
    
	// }}
	// {{ Magic Method: Wake Up
    public function __wakeup() {
        // wake up methods should be placed here
    }	

	function __destruct() {
		// reserved for codes to run when this object is destructed
	}

}

?>
