<?php
/**
 * Plugin Name: BE Featured Areas
 * Plugin URI:  https://github.com/billerickson/BE-Featured-Post-Checkbox
 * Description: Adds a checkbox to feature the post
 * Author:      Bill Erickson
 * Author URI:  https://www.billerickson.net
 * Version:     1.0.0
 *
 * BE Featured Areas is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * BE Featured Areas is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BE Areas Checkbox. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    BE_Featured_Areas
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
class BE_Featured_Areas {

	/**
	 * Featured areas
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $featured_areas;

	/**
	 * Taxonomy
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $taxonomy;

	/**
	 * Post Types
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $post_types;

	/**
	 * Primary constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		add_action( 'init', array( $this, 'init' ), 5 );
	}

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0.0
	 */
	function init() {

		$this->featured_areas = apply_filters( 'be_featured_areas', $this->default_areas() );
		$this->taxonomy = apply_filters( 'be_featured_areas_taxonomy', 'featured_area' );
		$this->post_types = apply_filters( 'be_featured_areas_post_types', array( 'post' ) );

		add_action( 'init', array( $this, 'register_tax' ) );
		add_action( 'init', array( $this, 'add_featured_areas' ) );
		add_action( 'add_meta_boxes', array( $this, 'metabox_register' )         );
		add_action( 'save_post',      array( $this, 'metabox_save'     ),  1, 2  );
	}

	/**
	 * Default Areas
	 *
	 */
	function default_areas() {
		return array(
			array(
				'label' => 'Featured Post',
				'slug'  => 'featured',
			),
		);
	}

	/**
	 * Register Taxonomy
	 *
	 */
	function register_tax() {

		if( ! apply_filters( 'be_featured_areas_register_taxonomy', true ) )
			return;

		$labels = array(
			'name'                       => 'Featured Area',
			'singular_name'              => 'Featured Area',
			'search_items'               => 'Search Featured Areas',
			'popular_items'              => 'Popular Featured Areas',
			'all_items'                  => 'All Featured Areas',
			'parent_item'                => 'Parent Featured Area',
			'parent_item_colon'          => 'Parent Featured Area:',
			'edit_item'                  => 'Edit Featured Area',
			'update_item'                => 'Update Featured Area',
			'add_new_item'               => 'Add New Featured Area',
			'new_item_name'              => 'New Featured Area',
			'separate_items_with_commas' => 'Separate Featured Areas with commas',
			'add_or_remove_items'        => 'Add or remove Featured Areas',
			'choose_from_most_used'      => 'Choose from most used Featured Areas',
			'menu_name'                  => 'Featured Areas',
		);
		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => false,
			'show_tagcloud'     => false,
			'hierarchical'      => false,
			'rewrite'           => false,
			'query_var'         => true,
			'show_admin_column' => false,
			'meta_box_cb'       => false,
		);

		register_taxonomy( $this->taxonomy, $this->post_types, $args );
	}

	/**
	 * Add Featured Areas
	 *
	 */
	function add_featured_areas() {

		foreach( $this->featured_areas as $featured_area ) {
			if( ! term_exists( $featured_area['slug'], $this->taxonomy ) ) {
				wp_insert_term(
					$featured_area['label'],
					$this->taxonomy,
					array(
						'slug' => $featured_area['slug']
					)
				);
			}
		}
	}

	/**
	 * Register the metabox
	 *
	 * @since 1.6.0
	 */
	function metabox_register() {

		// Add metabox for each post type found
		foreach ( $this->post_types as $post_type ) {
			add_meta_box( 'be-featured-areas', __( 'Featured Areas', 'be-featured-areas' ), array( $this, 'metabox_render' ), $post_type, 'side', 'high' );
		}
	}

	/**
	 * Output the metabox
	 *
	 * @since 1.6.0
	 */
	function metabox_render( $post ) {

		// Security nonce
		wp_nonce_field( 'be_featured_areas', 'be_featured_areas_nonce' );

		foreach( $this->featured_areas as $featured_area ) {
			$is_featured = has_term( $featured_area['slug'], $this->taxonomy, $post );
			echo '<p>';
				echo '<input type="checkbox" id="be_featured_areas_' . $featured_area['slug'] . '" name="be_featured_post_areas_' . $featured_area['slug'] . '" ' . checked( $is_featured, true, false ) . '>';
				echo '<label for="be_featured_areas_' . $featured_area['slug'] . '" style="margin-left: 4px;">' . $featured_area['label'] . '</label>';
			echo '</p>';
		}
	}

	/**
	 * Handle metabox saves
	 *
	 * @since 1.6.0
	 */
	function metabox_save( $post_id, $post ) {

		// Security check
		if ( ! isset( $_POST['be_featured_areas_nonce'] ) || ! wp_verify_nonce( $_POST['be_featured_areas_nonce'], 'be_featured_areas' ) ) {
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

		foreach( $this->featured_areas as $featured_area ) {

			$currently_featured = has_term( $featured_area['slug'], $this->taxonomy, $post );
			$featured = isset( $_POST['be_featured_areas_' . $featured_area['slug'] ] );

			if( $featured && ! $currently_featured ) {
				wp_set_post_terms( $post_id, $featured_area['slug'], $this->taxonomy, true );
			} elseif( $currently_featured && ! $featured ) {
				wp_remove_object_terms( $post_id, $featured_area['slug'], $this->taxonomy );
			}
		}

	}
}

new BE_Featured_Areas;
