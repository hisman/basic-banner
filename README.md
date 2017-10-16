# Basic Banner

Basic Banner is a WordPress plugin that allows you to create and display banners in WordPress. All Banners are stored as custom post type.

## Displaying the Banner

You can display the banner anywhere in your theme by calling this function :

`<?php basic_banner_show( $name ); ?>`

To get the banner object call this function instead :

`<?php $banner = basic_banner_get( $name ); ?>`

## Override Banner Template

1. Locate folder **template** in Basic Banner plugin folder (wp-content/plugins/basic-banner/template).
2. Copy **banner.php** file to your theme folder under the folder called **basic-banner** (wp-content/themes/your-theme/basic-banner/banner.php).
3. Modify **banner.php** file as you like.
