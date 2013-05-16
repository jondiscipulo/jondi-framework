<?php

/**
 *  Cloak | PHP 5
 *	Build 2013.05.16
 *
 *	Copyright (c) 2013
 *	Jonathan Discipulo <jonathan.discipulo@gmail.com>
 *	https://github.com/jondiscipulo/
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
 *	http://www.gnu.org/copyleft/lesser.html
 *
**/


/**  Cloak Class **/
class Cloak {

    private $result;

    /** constructor **/
    public function __construct() {
        return true;
    }
	
    /** Set Result **/
	public function setResult( $result ) {
		$this->result = $result;
	}
    
    /** Get Result **/
	public function getResult() {
		return $this->result;
	}
    
    /** Convert string characters into HTML entities **/
	public function convert( $string ) {

        for ($x=0; $x<=strlen($string)-1; $x++) {
            $ord = sprintf("%03u", ord(substr($string, $x, 1)));
            $this->result = $this->result . chr(38) . chr(35) . $ord . chr(59);
        }
        
        return $this->getResult();
    
    }
    
    /** Destructor **/
    public function __destruct() {
        // reserved for codes to run when this object is destructed
		if (isset($this->result)) unset($this->result);
    }    

}

?>
