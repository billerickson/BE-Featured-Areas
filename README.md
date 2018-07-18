# BE Like Content #
![Release](https://img.shields.io/github/release/billerickson/be-featured-post-checkbox.svg) ![License](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg?style=flat-square&maxAge=2592000)

**Contributors:** billerickson  
**Requires at least:** 4.1  
**Tested up to:** 4.9  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

BE Featured Post Checkbox adds a checkbox to the Edit Post screen to mark the current post as featured. It uses a taxonomy to store this data because tax queries are more performant than meta queries.

## Usage ##

To query for featured posts:

```
$loop = new WP_Query( array( 'tag' => 'featured' ) );
```

## Customization ##

You can customize it with the following filters:

* `be_featured_post_checkbox_taxonomy` - taxonomy used for marking featured
* `be_featured_post_checkbox_term` - term used for marking featured
* `be_featured_post_checkbox_post_types` - post types for which the metabox appears
