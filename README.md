# BE Featured Areas #
![Release](https://img.shields.io/github/release/billerickson/be-featured-post-checkbox.svg) ![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg?style=flat-square&maxAge=2592000)

**Contributors:** billerickson  
**Requires at least:** 4.1  
**Tested up to:** 4.9  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

BE Featured Areas adds a checkbox to the Edit Post screen to mark the current post as featured. It uses a hidden taxonomy to store this data because tax queries are more performant than meta queries.

By default it has a single "Featured Post" checkbox, but you can use the filters to add additional featured areas.

|<img src="https://d3vv6lp55qjaqc.cloudfront.net/items/112I103t2p1J1R1x3E1m/Screen%20Shot%202018-07-19%20at%201.35.54%20PM.png?X-CloudApp-Visitor-Id=1470414&v=210c06c0" width="302" height="115" /> |  <img src="https://d3vv6lp55qjaqc.cloudfront.net/items/3M0w3e2b0x3O1V0d0w1c/Screen%20Shot%202018-07-19%20at%208.48.09%20AM.png?X-CloudApp-Visitor-Id=1470414&v=da7a66e1" width="299" height="205" /> |
|:---:|:---:|
|Default option|Customized with different featured areas|

## Why use this? ##

You could create your own metabox using [ACF](https://www.advancedcustomfields.com/), storing the data as post meta. But if you have lots of posts (and post metadata), you can run into performance issues doing the meta query. Taxonomy queries are more scalable, and a better fit for this use case.

You could register your own taxonomy, but you don't want to expose the full backend UI that allows content editors to add, remove, and edit taxonomy terms.

This plugin lets the theme define the featured areas (taxonomy terms) since they correspond with areas within that theme.


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
