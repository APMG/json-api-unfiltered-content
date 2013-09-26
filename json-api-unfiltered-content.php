<?php
/*
Plugin Name: JSON API Expose Unfiltered Content
Plugin URI: http://blogs.mpr.org/developer
Description: Extends <a href="http://wordpress.org/plugins/json-api/">JSON API</a> plugin to expose unfiltered post content as a new <a href="http://wordpress.org/plugins/json-api/other_notes/#4.-Response-objects">response object</a>.
Version: 0.1
Author: Paul Wenzel
Author Email: pwenzel@mpr.org
License:

  Copyright 2013 Paul Wenzel (pwenzel@mpr.org)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

class JSONAPIUnfilteredContent {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'JSON API Unfiltered Content';
	const slug = 'json_api_unfiltered_content';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_json_api_raw_content' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_json_api_raw_content' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_json_api_raw_content() {
		// placeholder, do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_json_api_raw_content() {

		add_filter( 'json_api_encode',  array( &$this, 'json_api_raw_content' ));

	}

	function json_api_raw_content($response) {
		if (isset($response['posts'])) {
			foreach ($response['posts'] as $post) {
				$this->add_json_api_raw_content($post); // Add content_unfiltered to each post
			}
		} else if (isset($response['post'])) {
			$this->add_json_api_raw_content($response['post']); // Add a content_unfiltered property
		}
		return $response;
	}

	function add_json_api_raw_content(&$post) {
		if(isset($post->id)) {
			$post->content_unfiltered = get_post_field('post_content', $post->id);
		}
	}
	
} // end class
new JSONAPIUnfilteredContent();
