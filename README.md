# BE Featured Areas #
![Release](https://img.shields.io/github/release/billerickson/be-featured-post-checkbox.svg) ![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg?style=flat-square&maxAge=2592000)

**Contributors:** billerickson  
**Requires at least:** 4.1  
**Tested up to:** 4.9  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

BE Featured Areas adds a checkbox to the Edit Post screen to mark the current post as featured. It uses a hidden taxonomy to store this data because tax queries are more performant than meta queries.

You can use the filters to add additional featured areas.

## Usage ##

To query for featured posts:

```
$loop = new WP_Query( array(
	'tax_query' => array(
		array(
			'taxonomy' => 'featured_area',
			'field' = 'slug',
			'terms' => 'featured',
		)
	)
) );
```

## Customization ##

You can customize it with the following filters:

* `be_featured_areas` - Modify which featured areas are available ([example](https://gist.github.com/billerickson/60de534fb4424818a9c55af6be5917f3))
* `be_featured_areas_post_types` - Which post types can be featured. Default is `array( 'post' )`
* `be_featured_areas_taxonomy` - Taxonomy used for featured areas. Default is `featured_area`
* `be_featured_areas_register_taxonomy` - Whether to create the taxonomy. You can disable registration if the taxonomy is registered elsewhere ([example](https://gist.github.com/billerickson/819f70e593781a87eff018486a3946f4))
