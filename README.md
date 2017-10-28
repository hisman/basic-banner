# [Basic Banner](https://wordpress.org/plugins/basic-banner/)

[![Plugin version](https://img.shields.io/wordpress/plugin/v/basic-banner.svg)](https://wordpress.org/plugins/basic-banner/)
[![WordPress Compatibility](https://img.shields.io/wordpress/v/basic-banner.svg)](https://wordpress.org/plugins/basic-banner/)
[![license](https://img.shields.io/badge/license-GPL--2.0%2B-red.svg)](https://github.com/hisman/basic-banner/blob/master/LICENSE.txt)

Basic Banner is a WordPress plugin that allows you to create and display banners in WordPress. This plugin creates custom post type called banner to store all the banners.

### Displaying the Banner

You can display the banner anywhere in your theme by calling this function :

```php
<?php basic_banner_show( $name ); ?>
```

Please note that `$name` is the banner slug. To get the banner object, you can use this function :

```php
<?php $banner = basic_banner_get( $name ); ?>
```

### Override Banner Template

1. Locate folder **template** in Basic Banner plugin folder (wp-content/plugins/basic-banner/template).
2. Copy **banner.php** file to your theme folder under the folder called **basic-banner** (wp-content/themes/your-theme/basic-banner/banner.php).
3. Modify **banner.php** file as you like.
