<?php

/**
 * Session | PHP 5
 * Build 2013.05.20
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

/** Session Class **/
class Session {

	private $name = 'default';

	/** Constructor **/
	public function __construct( $name=null ) {
		if ($name !== null) $this->name = $name;
	}

	/** Session Set Parameters **/
	public function setup( $lifetime=0, $path='/', $domain='', $secure=false, $httponly=true) {
		session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
		ini_set('session.session.use_only_cookies', 1); // prevents session fixation; should be 1
		ini_set('session.entropy_file',  '/dev/urandom'); // better entropy source
		ini_set('session.cookie_lifetime', $lifetime); // smaller exploitation window for xss/csrf/clickjacking
		ini_set('session.cookie_secure', 0); // owasp a9 violations; should be 1
		ini_set('session.cookie_httponly', 1); // helps mitigate xss; should be 1
	}
	
	/** Session Start **/
	public function start() {
		try {
			session_name($this->name);
			session_start();
			session_regenerate_id(true); // delete old session files = true
		} catch (Exception $e) {
			throw new Exception('ERROR STARTING SESSION');
		}
	}

	/** Session ID **/
	public function id() {
		return session_id();
	}

	/** Redirect **/
	public function redirect( $url ) {
		header('Location: ' . $url);
		exit();
	}	

	/** Set Session **/
	public function update( $key, $value ) {
		$_SESSION[$key] = $value;
	}
	
	/** Get Session **/
	public function get( $key ) {
		return $_SESSION[$key];
	}
	
	/** Check if Session exists **/
	public function exists( $key ) {
		if (isset($_SESSION[$key])) {
			return true;
		} else {
			return false;
		}
	}
	
	/** Session Save Path **/
	public function path() {
		return session_save_path();
	}
	
	/** Delete Session **/
	public function delete( $key ) {
		unset($_SESSION[$key]);
	}
	
	/** Destroy Session **/
	public function destroy() {
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$p = session_get_cookie_params();
			setcookie(session_name(), '', time()-99999,	$p['path'], $p['domain'], $p['secure'], $p['httponly']);
		}
		session_destroy();
	}	
	
	/** Destructor **/
	public function __destruct() {}
	
}

?>
