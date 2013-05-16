<?php

/**
 *	Page Control Class | PHP 5
 *	Build 2010.06.05
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
// {{ Page Control Class
class PageControl {

	protected $unit_total, $unit_per_page, $link_per_page, $current_page, $page_query_string;
	public $page_total, $link_set, $config;
	private $link_first, $link_prev, $link_pages, $link_next, $link_last;

	// }}
	// {{ Constructor
	public function __construct() {
		return true;
	}
	
	// }}
	// {{ Setup required pagination variables
	public function setup( $unit_total, $unit_per_page, $link_per_page, $current_page, $page_query_string='p' ) {
	
		$this->unit_total = intval( $unit_total );
		$this->unit_per_page = intval( $unit_per_page );
		$this->link_per_page = intval( $link_per_page );
		$this->current_page = ((intval( $current_page ) <= 0) ? 1 : intval( $current_page ));
		$this->page_query_string = trim( $page_query_string );
		
		$this->setDefaultConfig();
		
	}
	
	// }}
	// {{ Set default configuration
	public function setDefaultConfig() {
		
		$this->config['url'] = '';
		$this->config['tag'] = 'div';
		$this->config['class'] = 'pages';
		
		$this->config['lang_first'] = 'First';
		$this->config['lang_last'] = 'Last';
		$this->config['lang_prev'] = 'Previous';
		$this->config['lang_next'] = 'Next';
		
		$this->config['show_first_last'] = true;
		$this->config['show_prev_next'] = true;
		$this->config['show_pages'] = true;
		
	}

	// }}
	// {{ Display linkset
	public function displayLinks() {
	
		$this->buildLinks();
		$this->wrapLinks();
		
		echo $this->link_set;
		
	}
	
	// }}
	// {{ Get total number of pages
	private function getPageTotal( $unit_total, $unit_per_page ) {
	
		$this->page_total = ceil($unit_total / $unit_per_page);
		return( $this->page_total );
		
	}

	// }}
	// {{ Wrap variable with specified tag
	private function wrap( $var, $tag='span', $class='' ) {
	
		$tag = strtolower( $tag );
		
		if ( strlen( $class ) >= 1 ) {
			$wrapped = "<{$tag} class=\"{$class}\">{$var}</{$tag}>";
		} else {
			$wrapped = "<{$tag}>{$var}</{$tag}>";
		}
		
		return $wrapped;
		
	}
	
	// }}
	// {{ Force-fix current page based on boundaries
	private function forceCurrentPage( $page_total ) {
	
		if ($this->current_page < 1) {
			// set $current_page based on minimum boundary by force
			$this->current_page = 1;
		} elseif ($this->current_page > $page_total) {
			// set $current_page based on maximum boundary by force
			$this->current_page = $page_total;
		} else {
			// else do nothing
			$this->current_page = $this->current_page;
		}
		
	}

	// }}
	// {{ Create control links: First, Previous, Next, Last
	private function createControlLinks() {
	
		$cp = $this->current_page;
		$pqs = $this->page_query_string;
		$pt = $this->page_total;
	
		// make first link equal to 1 and last link equal to $page_total
		$this->link_first = ( $cp <= 1 ) ? $this->wrap( $this->config['lang_first'] ) : "<a href=\"{$this->config['url']}?{$pqs}=1\">{$this->config['lang_first']}</a>";
		$this->link_last = ( $cp >= $pt ) ? $this->wrap( $this->config['lang_last'] ) : "<a href=\"{$this->config['url']}?{$pqs}={$pt}\">{$this->config['lang_last']}</a>";

		// make previous and next link
		$this->link_prev = (( $cp - 1 ) <= 0) ? $this->wrap( $this->config['lang_prev'] ) : "<a href=\"{$this->config['url']}?{$pqs}=" . ( $cp-1 ) . "\">{$this->config['lang_prev']}</a>";
		$this->link_next = ( $cp >= $pt ) ? $this->wrap( $this->config['lang_next'] ) : "<a href=\"{$this->config['url']}?{$pqs}=" . ( $cp+1 ) . "\">{$this->config['lang_next']}</a>";
	
	}
	
	// }}
	// {{ Build linkset
	private function buildLinks() {
	
		// get page total
		$pt = $this->getPageTotal( $this->unit_total, $this->unit_per_page );
		
		// force current page
		$this->forceCurrentPage( $pt );

		// initialize variables
		$cp = $this->current_page;
		$pqs = $this->page_query_string;
		$lpp = $this->link_per_page;
		
		// create control links
		$this->createControlLinks();
		
		// set display start page to 1 if current value is less than 1 | fix: showing link to page 0 when $this->link_per_page = 1
		$dsp = (( $cp - round($lpp/2) ) < 1 ) ? 1 : ( $cp - round($lpp/2) );
		
		// initialize string and counter variables
		$lpl = '';
		$a = $b = $lt = 0;
		
		// set center page link for odd and even lpp
		if (( $lpp % 2 ) == 0 ) {
			$val = floor( $lpp/2 ) - 1;
		} else {
			$val = floor( $lpp/2 );
		}
		
		// algorithm for creating page links
		for ( $a=$cp-$val; $a<=$cp+$val; $a++ ) {
		
			if ( $a == $cp ) {
				$lpl .= ' ' . $this->wrap( $a, 'span', 'current' ) . ' ';
				$lt++;
			} else {
				if (( $a >= 1 ) && ( $a <= $pt )) {
					$lpl .= " <a href=\"{$this->config['url']}?{$pqs}={$a}\">{$a}</a> ";
					$lt++;
				}
			}
		
		}
		
		// fix: complete trailing links on left most page numbers according to $lpp
		for ( $b=$a; $b<=$lpp; $b++ ) {
			// only include pages below or equal to page total: $a <= $pt
			if ( $b <= $pt ) {
				$lpl .= " <a href=\"{$this->config['url']}?{$pqs}={$b}\">{$b}</a> ";
				$lt++;
			}
		}
		
		// fix: complete links on right most page numbers according to $lpp
		if ($lt < $lpp) {
			$d = 0;
			for ( $c=$lpp-$lt; $c>0; $c-- ) {
				$d++;
				$b = ($cp - $val) - $d;
				// only include pages above 0
				if ( $b > 0 ) {
					$lpl = " <a href=\"{$this->config['url']}?{$pqs}={$b}\">{$b}</a> {$lpl}";
				}
			}
		}
		
		$this->link_pages = $lpl;
		
	}
	
	// }}
	// {{ Set configuration
	public function setConfig( $key, $value ) {
		
		switch( $key ) {
		
			case 'url':
				$this->config['url'] = $value;
				break;
			case 'tag':
				$this->config['tag'] = $value;
				break;
			case 'class':
				$this->config['class'] = $value;
				break;
			case 'lang_first':
				$this->config['lang_first'] = $value;
				break;
			case 'lang_last':
				$this->config['lang_last'] = $value;
				break;
			case 'lang_prev':
				$this->config['lang_prev'] = $value;
				break;
			case 'lang_next':
				$this->config['lang_next'] = $value;
				break;
			case 'show_first_last':
				$this->config['show_first_last'] = $value;
				break;
			case 'show_prev_next':
				$this->config['show_prev_next'] = $value;
				break;
			case 'show_pages':
				$this->config['show_pages'] = $value;
				break;
			default:
				break;
		
		}
	
	}
	
	// }}
	// {{ Wrap link set
	private function wrapLinks() {
	
		$wrap_open = "<{$this->config['tag']} class=\"{$this->config['class']}\">";
		$wrap_close = "</{$this->config['tag']}>";
		$link_set = '';
		
		if ( $this->config['show_pages'] == true) {
			$link_set = "{$this->link_pages}";
		}
		
		if( $this->config['show_prev_next'] == true ) {
			$link_set = "{$this->link_prev} {$link_set} {$this->link_next}";
		}
		
		if( $this->config['show_first_last'] == true ) {
			$link_set = "{$this->link_first} {$link_set} {$this->link_last}";
		}
		
		$this->link_set = "{$wrap_open} {$link_set} {$wrap_close}";
	
	}
	
	// }}
	// {{ Destructor
	public function __destruct() {
		// reserved for codes to run when this object is destructed
	}	
	
}

/*

DONE:

- manual assigning of div class
- configure if next and previous links are visible
- configure if first and last links are visible
- always force current page indicator to center of the page links
- wrap controls and pages with span or div tags
- manual assigning of page URL

TODO:

- auto-determine if query string is prepended with ? or &

*/

?>
