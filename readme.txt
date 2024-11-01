=== ThumbSniper ===
Contributors: CupRacer
Donate link: http://www.mynakedgirlfriend.de/donate-spenden/
Tags: screenshot, image, plugin, preview, tooltip, thumbnail, hyperlink, link, url, fade, 3D, reflection
Requires at least: 3.2
Tested up to: 4.7
Stable tag: 2.9.7

This plugin dynamically shows preview screenshots of hyperlinks as tooltips on your WordPress site.



== Description ==

This plugin dynamically shows preview screenshots of hyperlinks as tooltips on your WordPress site.

For the tooltip effect a jQuery plugin called "qTip2" is used internally.
Find more about this great project here: http://qtip2.com/
The thumbnails are provided by http://thumbsniper.com and its API.


== Installation ==

1. Upload the directory 'thumbsniper' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make your settings through the 'ThumbSniper' menu in WordPress



== Frequently Asked Questions ==

= Why does it take a couple of seconds until the preview image is shown? =

It's not a bug - it's a mechanism.
The plugin doesn't generate the thumbnails directly. The script sends a request for the thumbnail to the central ThumbSniper server. If the URL is requested for the first time, the thumbnail has to be created first. After this is finished, it's cached on the ThumbSniper server and will be delivered much faster next time.

= How exactly does this work? =

The shown tooltips contain a HTML "img" tag. The source URL of this tag points to the ThumbSniper service. The chosen image size and the URL of the current hyperlink are sent as parameters. The ThumbSniper service stores this data in a database. Then a thumbnail generator gets the order to generate a thumbnail for the stored URL. After successfully generating the image, the ThumbSniper service gets the image. The process of generating the image should take about 3 to 10 seconds. After this, the ThumbSniper service redirects to the correct thumbnail url and - here it is!

= Is the TumbSniper service free? =
The ThumbSniper service is free of cost for you (not for me :-( ).

= Who's the owner the ThumbSniper service? =
It is written, operated and maintained by me, Thomas Schulte.


== Screenshots ==

1. This screenshot shows a tooltip preview which is shown while holding the mouse cursor over the WordPress.org hyperlink.



== Changelog ==

= 2.9.7 =
* Use minified JavaScript for qTip2

= 2.9.6 =
* re-release

= 2.9.5 =
* removed version string from wp_enqueue_script includes

= 2.9.4 =
* NEW: Optionally show original link title when displaying a tooltip (please report issues!).

= 2.9.3 =
* Update ThumbSniper tooltip via Composer (fixed brackets, use absolute URL)

= 2.9.2 =
* Fixed version info.

= 2.9.1 =
* Fixed loading of external JavaScripts.

= 2.9.0 =
* Plugin was rewritten completely.
* Added tooltip library via Composer.
* Switched to new option names.
* Temporarily removed i18n (coming back soon).

= 2.0.5 =
* Added support for SSL websites (https).

= 2.0.4 =
* Converted all strings to i18n.
* Added locale de_DE.

= 2.0.3 =
* Re-added the option "include pages" to explicitly define on which pages the plugin should be active.
* Added "Premium thumbnails": A new feature which extends the bubble tips by custom HTML code.

= 2.0.2 =
* Fixed a problem regarding missing parameters (mostly in backend).

= 2.0.1 =
* A (nearly) complete rewrite of the plugin code has been done.
  The migration process from old version should be painless.
* You can now choose between 3 different thumbnail variants.
* Conflicts with other plugins or libraries can be solved by using some compatibility switches.

= 0.9.92 =
* Switched CSS styles from extra file to inline code for general performance reasons
* Disabled usage of extra jQuery lib. ThumbSniper now uses the internal library.
---> optional machen!!
* The waiting circle now loops every second on mouse-over until a thumbnail is available. There's no need for repeating mouse-overs anymore.
---> auf URL achten!

= 0.9.91 =
* Successfully tested with WordPress 3.3.1
* Disabled "opacity" option. Was never used.
* Disabled "code placement" option. Code is placed in footer from now on.
* qTip2 is loaded in header now.
* jQuery was updated to version "1.7.1"
* qTip2 was updated to "nightly-121c0fe8a1f4991cbfc4af6fc414ac0a1325949979"
  Please keep in mind that the qTip2 site describes this version as not suitable for production sites. I still don't really care about that. :-)

= 0.9.9 =
* jQuery update to version 1.6.4
* qTip2 update to version "nightly-daba4a5790f9d1f19a87f95ddbf6a7411317572541"
  Please keep in mind that the qTip2 site describes this version as not suitable for production sites. I don't really care about that. :-)
* I use ajax to get the thumbnail urls as jsonp results now. That's cool because we get a nice gui-wait feedback while the thumbnails are loaded.
* added some more styles - the default from now on is "jtools", you should give it a try!
* minor code changes

= 0.9.7 =
* Fixed a bug which might have caused some problems with Firefox/Mozilla.

= 0.9.6 =
* Changed the method to load the required jQuery library.
* The plugin uses plugins_url() instead of a hard-coded path now.
* Changed jQuery namespace to ThumbSniper to avoid conflicts.

= 0.9.5 =
* removed redundant code in the admin menu

= 0.9.4 =
* Changed the way jQuery() is called to avoid conflicts with other site scripts.

= 0.9.3 =
* Excluded urls were ignored. The missing code should do it's work now.

= 0.9.2 =
* Added CSS attributes for the tooltip image to avoid disturbances with site-wide img-styles that might exist on a site.

= 0.9.1 =
* This is the first released version.



== Upgrade Notice ==

= 0.9.6 =
* None.

= 0.9.5 =
* None.

= 0.9.4 =
* None.

= 0.9.3 =
* None.

= 0.9.2 =
* None.

= 0.9.1 =
* None.

