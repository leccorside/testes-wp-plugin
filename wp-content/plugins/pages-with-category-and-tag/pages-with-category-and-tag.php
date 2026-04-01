<?php
/*
Plugin Name: Pages with category and tag
Plugin URI: https://dev.back2nature.jp/en/pages-with-category-and-tag/
Description: Add Categories and Tags to Pages.
Version: 0.9.0
Author: YAHMAN
Author URI: https://back2nature.jp/
License: GNU General Public License v3 or later
Text Domain: pages-with-category-and-tag
Domain Path: /languages/
*/

/*
    Pages with category and tag
    Copyright (C) 2018 YAHMAN

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
defined('ABSPATH') or die("Please don't run this script.");


function pages_with_category_and_tag_register(){
  /*add categories and tags to pages*/
  register_taxonomy_for_object_type('category', 'page');
  register_taxonomy_for_object_type('post_tag', 'page');
}
add_action( 'init', 'pages_with_category_and_tag_register');

function pages_with_category_and_tag_register_pre_get( $query ) {

  if ( is_admin() || ! $query->is_main_query() ) {
    return;
  }
  /*view categories and tags archive pages */
  if($query->is_category && $query->is_main_query()){
    $query->set('post_type', array( 'post', 'page'));
  }
  if($query->is_tag && $query->is_main_query()){
    $query->set('post_type', array( 'post', 'page'));
  }
}
add_action( 'pre_get_posts', 'pages_with_category_and_tag_register_pre_get' );
