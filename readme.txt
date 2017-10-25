=== Basic Banner ===
Contributors: hisman
Donate Link: https://www.paypal.me/hismansaputra
Tags: banner
Requires at least: 4.5
Tested up to: 4.8.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to create and display banners in WordPress.

== Description ==

Basic Banner is a plugin that allows you to create and display banners in WordPress. This plugin creates custom post type called banner to store all the banners.

= Displaying the Banner =

You can display the banner anywhere in your theme by calling this function :

`<?php basic_banner_show( $name ); ?>`

Please note that `$name` is the banner slug. To get the banner object, you can use this function :

`<?php $banner = basic_banner_get( $name ); ?>`

= Override Banner Template =

1. Locate folder **template** in Basic Banner plugin folder (wp-content/plugins/basic-banner/template).
2. Copy **banner.php** file to your theme folder under the folder called **basic-banner** (wp-content/themes/your-theme/basic-banner/banner.php).
3. Modify **banner.php** file as you like.

= Contribute on GitHub =

You can contribute to this plugin via the [GitHub repository](https://github.com/hisman/basic-banner).

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself.

1. Log in to your WordPress dashboard.
2. Go to **Plugins > Add New** and search **"Basic Banner"**.
3. Once you’ve found our plugin, you can install it by simply clicking **"Install Now"**.
4. Activate the plugin.

= Manual installation =

1. Download the plugin via WordPress.org.
2. Upload the ZIP file through the **'Plugins > Add New > Upload'**.
3. Activate the plugin.
4. For more detail about manual installation [you can read it here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. Create a new banner

== Changelog ==

= 1.0.0 =
* Initial Version.
