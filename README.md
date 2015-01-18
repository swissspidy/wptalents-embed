# WP Talents Embed #
**Contributors:** swissspidy  
**Tags:** WP Talents, embed, oEmbed, API, content, i18n, translation-ready  
**Requires at least:** 2.6  
**Tested up to:** 4.1  
**Stable tag:** 1.0.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Easily embed WP Talents profiles in your blog posts.

## Description ##

Embed WP Talents profiles in your blog posts using both oEmbed and using a meta box on the post edit screen.

The plugin automatically adds schema.org markup to the WP Talents data. This data is by the way cached when a post is saved, so there are no loss of site performance.
If you want to see the plugin in action, just browse around [SpinPress](https://spinpress.com/ "SpinPress"), where the plugin is currently active.

[WP Talents](https://wptalents.com/ "WP Talents") is like the CrunchBase for WordPress, and with this plugin you can leverage its huge amount of data for free.

**Note:** This plugin requires PHP 5.3 or higher.  

## Installation ##

1. Upload the `wptalents-embed` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Write a new post, add a talent through the meta box, and publish it.
1. Visit your post on the frontend to see the plugin's output.

## Frequently Asked Questions ##

### I don't want to display the talents beneath the content. Can I display it manually? ###

Sure! Just disable the `wptalents_embed_append_content` filter and use the wptalents_the_embed_content` function to display the talents anywhere you want.

### Can I use my own CSS to style the talent entries? ###

You can use `wp_dequeue_style( 'wptalents-embed-frontend' )` and load your own stylesheet at any time.

## Screenshots ##

### 1. The meta box on post edit screen. ###
![The meta box on post edit screen.](https://raw.githubusercontent.com/swissspidy/wptalents-embed/master/screenshot-1.png)


### 2. The plugin displays an overview of the talents on your site. ###
![The plugin displays an overview of the talents on your site.](https://raw.githubusercontent.com/swissspidy/wptalents-embed/master/screenshot-2.png)


### 3. When using oEmbed, the plugin displays an IFrame with the talent's profile. ###
![When using oEmbed, the plugin displays an IFrame with the talent's profile.](https://raw.githubusercontent.com/swissspidy/wptalents-embed/master/screenshot-3.png)


## Changelog ##

### 1.0.0 ###
* Initial release.