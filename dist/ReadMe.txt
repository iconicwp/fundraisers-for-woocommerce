////////////////////////////////////////
//                                    //
//    Thanks for purchasing this      //
//    plugin! Much appreciated!       //
//                                    //
////////////////////////////////////////

Detailed instructions of how to install and use this plugin can be found here:

http://www.jckemp.com/plugins/woocommerce-product-configurator/

=====Requirements=====

1.	PHP 5.3+
2.	Tested on WooCommerce 2.1+
3.	PHP GD Library

=====Important Notes=====

1.	Your images must be PNGs.
2.	Layers run from top (highest layer) to bottom (lowest layer) in the configurator tab. This order is defined in the product’s Attributes tab.
3.	You should set a featured image for the product, which will appear in product listings and as a fallback.
4.	Product galleries are not yet available.
5.	The configurator tab will only show once a variable product with attributes is saved.

=====Installation=====

To install the plugin:

1. 	Navigate to Plugins > Add New > Upload.
2.	Click Choose File, and choose the file jck_woo_product_configurator.zip from your CodeCanyon download zip.
3.	Once uploaded, click activate plugin.
4.	The plugin is now installed and activated.
5.	Once installed, you will be prompted to install the required Redux Framework plugin.

=====Settings - General Settings=====

A settings page can be found under WooCommerce > Configurator.

*	Enable Image Cache
	By enabling the image cache, your customers final product image will be cached for 24 hours. The PHP image compilation process can be quite slow, so this is a good method of allowing the rendered image to be saved in your uploads folder, and served instead of the dynamic image generator.

=====Settings - Image Container=====

*	Image Container Width
	The width of your image container. This is often 48%.
*	Image Container Alignment
	Set the alignment of your image container
*	Image Container Margin
	Set the outside margin for your image container.
*	Image Container Padding
	Set the inside padding for you image container.
*	Image Container Background
	Set the background colour of your image container.
*	Enable Breakpoint
	When checked, the options below will become available, allowing you to change the image container settings after a certain browser width.
*	Breakpoint
	The width at which the breakpoint settings become active. The breakpoint is active from 0px to this setting.
*	Image Container Width After Breakpoint
	Set the width of your image container within the breakpoint parameters.
*	Image Container Alignment After Breakpoint
	Set the width of your image container within the breakpoint parameters.
*	Image Container Margin After Breakpoint
	Set the margin of your image container within the breakpoint parameters.

=====Settings - Thumbnails=====

*	Show Thumbnails
	When checked, thumbnails will be displayed below the configurable product image.
*	Thumbnail Columns
	Enter a whole number to define the number of thumbnails in a column.
*	Thumbnail Spacing (px)
	Enter a pixel value for the spacing between your thumbnail images.

=====Settings - Loader Styling=====

*	Loading Overlay Colour
	When an image is loading, a loading overlay is shown. This sets the colour of that overlay.
*	Loading Overlay Opacity
	This sets the opacity of the loading overlay. 0 is transparent, 1 is opaque, 0.5 would be 50% transparent.
*	Loading Icon
	Choose he icon to display while images are loading.
*	Loading Icon Colour
	Set the colour of your loading icon.


=====Configuration=====

Once installed, you can begin setting up your product configurator. We’ll assume you’ve already created a variable product as per the WooCommerce Documentation.

1.	Click to edit your variable product.
2.	Because your product is variable, and you’ve created attributes with the “Used for variations” checkbox selected, you will now be able to see the Configurator tab.
3.	When you open the Configurator tab, you will see a checkbox to enable the Product Configurator. Check that.
4.	Below this, you will see a list of all the variable attributes you created in the Attributes tab. These are ordered from top (highest layer) to bottom (lowest layer), ending with a custom option Background Image. To change this order, drag and drop your attributes from the Attributes tab.
5.	Each attribute in the Configurator tab has a row for each value belonging to that attribute. Here you can upload images.
6.	Click Add Image beside the attribute value you want to add an image to. You will then see the normal media library.
7.	Select or upload a transparent PNG to act as the image for that layer and that attribute value.
8.	Continue this process for all attribute values.
9.	Once your images are uploaded, you can then choose default attribute values. This will define what the image looks like to the customer on page load. If you leave a default unselected, that layer will not be used in the default image.
10.	Save your product.

You’re now ready to start using the Product Configurator on the frontend! It couldn’t be simpler; simply choose your product options and watch as the image changes based on your selection.

=====Changelog=====

v1.0.4 (06/08/14)

[Fix] Fixed bug where TGM didn't notify that Redux was required

v1.0.3 (27/07/14)

[Update] Added "Default" image for attributes
[Fix] Fixed bug where configurator was displayed on frontend even though it wasn't enabled
[Update] Now works with WooCommerce Variation Swatches and Photos by Lucas Stark
[Update] Added ability to order configurator options independantly, via drag/drop

v1.0.2

[Fix] Removed tgmpa_load_bulk_installer error

v1.0.1

[Fix] Configurator Enabled returned yes, not true. Added check for this.

v1.0.0

Initial Release

----------------------------------------

If you get stuck, please vist the support forum:

http://jamesckemp.ticksy.com

----------------------------------------

Thanks again,
James