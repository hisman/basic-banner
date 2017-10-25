# Basic Banner

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
