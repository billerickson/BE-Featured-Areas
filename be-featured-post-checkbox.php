<?php
/**
 * Plugin Name: BE Featured Post Checkbox
 * Plugin URI:  https://github.com/billerickson/BE-Featured-Post-Checkbox
 * Description: Adds a checkbox to feature the post
 * Author:      Bill Erickson
 * Author URI:  https://www.billerickson.net
 * Version:     1.0.0
 *
 * BE Featured Post Checkbox is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * BE Featured Post Checkbox is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BE Featured Post Checkbox. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    BE_Featured_Post_Checkbox
 * @author     Bill Erickson
 * @since      1.0.0
 * @license    GPL-2.0+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main BE_Title_Toggle class
 *
 * @since 1.0.0
 * @package BE_Title_Toggle
 */
class BE_Featured_Post_Checkbox {

	/**
	 * Taxonomy used for marking featured
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy;

	/**
	 * Term used for marking featured
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $term;

	/**
	 * Primary constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0.0
	 */
	function init() {

		$this->taxonomy	= apply_filters( 'be_featured_post_checkbox_taxonomy',	'post_tag' );
		$this->term 	= apply_filters( 'be_featured_post_checkbox_term', 		'featured' );

		add_action( 'add_meta_boxes', array( $this, 'metabox_register' )         );
		add_action( 'save_post',      array( $this, 'metabox_save'     ),  1, 2  );
	}

	/**
	 * Register the metabox
	 *
	 * @since 1.6.0
	 */
	function metabox_register() {

		$post_types = apply_filters( 'be_featured_post_checkbox_post_types', array( 'post' ) );

		// Add metabox for each post type found
		foreach ( $post_types as $post_type ) {
			add_meta_box( 'be-featured-post-checkbox', __( 'Featured Post', 'be-featured-post-checkbox' ), array( $this, 'metabox_render' ), $post_type, 'side', 'high' );
		}
	}

	/**
	 * Output the metabox
	 *
	 * @since 1.6.0
	 */
	function metabox_render( $post ) {

		// Security nonce
		wp_nonce_field( 'be_featured_post_checkbox', 'be_featured_post_checkbox_nonce' );

		$featured = has_term( $this->term, $this->taxonomy, $post );

		echo '<p>';
			echo '<input type="checkbox" id="be_featured_post_checkbox" name="be_featured_post_checkbox" ' . checked( true , $featured, false ) . '>';
			printf( '<label for="be_featured_post_checkbox" style="margin-left: 4px;">%s</label>', __( 'Featured Post', 'be-featured-post-checkbox' ) );
		echo '</p>';
	}

	/**
	 * Handle metabox saves
	 *
	 * @since 1.6.0
	 */
	function metabox_save( $post_id, $post ) {

		// Security check
		if ( ! isset( $_POST['be_featured_post_checkbox_nonce'] ) || ! wp_verify_nonce( $_POST['be_featured_post_checkbox_nonce'], 'be_featured_post_checkbox' ) ) {
			return;
		}

		// Bail out if running an autosave, ajax, cron.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Bail out if the user doesn't have the correct permissions to update the slider.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$currently_featured = has_term( $this->term, $this->taxonomy, $post );
		$featured = isset( $_POST['be_featured_post_checkbox'] );

		if( $featured && ! $currently_featured ) {
			wp_set_post_terms( $post_id, $this->term, $this->taxonomy, true );
		} elseif( $currently_featured && ! $featured ) {
			wp_remove_object_terms( $post_id, $this->term, $this->taxonomy );
		}

	}
}

new BE_Featured_Post_Checkbox;
