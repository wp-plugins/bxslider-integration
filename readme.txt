=== bxSlider integration for WordPress ===
Contributors: vprat, marvinlabs
Tags: wordpress, gallery, slider, bxslider, slideshow, 
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

bxSlider for WordPress integrates the cool responsive content slider "bxSlider jQuery plugin" in your very own WordPress 
site.

== Description ==

bxSlider for WordPress integrates the cool responsive content slider [bxSlider jQuery plugin](http://bxslider.com/) in 
your very own WordPress site. Galleries are turned into beautiful sliders, but you can also roll you own sliders using 
special shortcode. The best thing is that you are not limited to the number of sliders or galleries per page, you can 
have as many as you want! 

Why should you use this slider? I'll quote the bxSlider's website:

* Fully responsive - will adapt to any device
* Horizontal, vertical, and fade modes
* Slides can contain images, video, or HTML content
* Advanced touch / swipe support built-in
* Uses CSS transitions for slide animation (native hardware acceleration!)
* Full callback API and public methods
* Small file size, fully themed, simple to implement
* Browser support: Firefox, Chrome, Safari, iOS, Android, IE7+

== Features ==

* [gallery] and [bxgallery] shortcodes
* [slider] and [bxslider] shortcodes
* Template functions

= [gallery] and [bxgallery] shortcodes =

You can use the default WordPress `[gallery]` shortcode or use the additional `[bxgallery]` shortcode to create awesome 
dynamic galleries. These shortcodes take the following parameters:

- **ids** *[a comma-separated list of image IDs]*: This is usually inserted for you when you create the gallery from the 
media box. If you omit this parameter, all the images you have uploaded along with the post will be included in the 
gallery. 
- **exclude_featured** *[1 or 0]*: if set to 1 and you did not specify specific ids as above, the post featured image 
will be excluded from the gallery. If you omit this parameter, it will default to the value set in the plugin settings
page. 
- **hide_carousel** *[1 or 0]*: if set to 1, the carousel with thumbnails will not be shown. If you omit this 
parameter, it will default to the value set in the plugin settings page. 

= [slider] and [bxslider] shortcodes =

You can also build your own custom sliders, with any content you'd like in them. 

*Here is an example:*

    [slider]
        This is my first slide. I can contain any html you like.
    [next-slide]
        And the shortcode above has made this text be the second slider.
    [next-slide]
        And thus we are now having the third slide of this slider. Below we close the initial shortcode to notify the end 
        of the slider. Simple, isn't it?
    [/slider]

= Template functions =

The plugin also provides template functions to be used in your theme files. Those functions are all static methods of 
the class `BXSG_ThemeUtils`. To be safe, in case the plugin is not active, you should check that the class exists 
before calling the functions:

    <?php 
    	if ( class_exists( 'BXSG_ThemeUtils' ) ) {
    		// Do something with the BXSG_ThemeUtils class
		} 
	?>

*1. Post gallery*

    <?php BXSG_ThemeUtils::the_post_gallery( array( 
				'exclude_featured' => 1 
			) ); ?>

> Hint: you can pass the shortcode parameters as an array to customize the output 
	
== Upgrade Notice ==

Nothing worth mentionning yet. You might visit the settings page though to adjust new default settings values. 

== Installation ==

Nothing special, just upload the files, activate and you can then visit the settings page if you want. Really, it's 
just like any other simple plugin.

== Screenshots ==

1. A standard WordPress gallery turned into a slider with a thumbnail carousel.

== Frequently Asked Questions ==

= Why aren't there any questions here yet? =

Because you did not ask :p

== Changelog ==

= 1.1.1 (2013/04/08) =

* Fixed a bug on activation ([function.array-merge]: Argument #2 is not an array in bxslider-integration/includes/settings.class.php)

= 1.1.0 (2013/03/29) =

* Added a template function to output the post gallery from within a theme
* Added an option to exclude the post featured image from its gallery
* Added a generic slider shortcode
* Corrected a bug in attachment listing (images incorrectly pulled)

= 1.0.0 (2013/03/29) =

* First plugin release. 
* Replaces the default WordPress galleries with nice ones using the bxSlider jQuery plugin