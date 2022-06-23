=== AnyWhere Elementor Pro ===
Contributors: webtechstreet
Tags: page-builder, elementor
Requires at least: 4.4
Tested up to: 5.3
Stable tag: 5.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Global layouts to use with shortcodes, global post layouts for single and archive pages. Supports CPT and ACF

== Description ==

Global layouts to use with shortcodes, global post layouts for single and archive pages. Supports CPT and ACF


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Changelog ==

= 2.13.2 =
* Fixed conflict with Autoptimize plugin.
* Fixed issue in Post Image widget.


= 2.13.1 =
* Fixed: Issue with missing images in AE - Woo Gallery widget.
* Fixed: Issue in Post Blocks and Portfolio widget. Section/Column click was not working on Ajax pagination and carousel.
* Fixed: Repeater Block Layouts not saving Repeater Field when there are more than one available.
* Fixed: Issue with AE - Title widget alignment when used within Carousel mode.
* Fixed: Issue with pagination not working in Post Block widget for ACF Relationship Field source.
* Fixed: Added compatibility for EAE Modal Popup widget in Post Blocks widget Ajax Pagination/Infinite Scroll
* And few other minor fixes.


= 2.13 =
* New Widget: AE - Pods. A Dedicated widget to display complex pods fields. Supports Post, Term, User and Option fields.
* Enhancement: Added support in AE - ACF widget to display data from User and Options (Settings) fields
* Enhancement: Added option in Post Blocks widget infinite scroll to disable url history update.
* Enhancement: Revamped template settings metabox. Removed Butterbean library that was causing conflict with other plugin and themes.
* Fixed: Issue with wrong url structure in Post Block widget's navigation.
* And some other UI changes and minor bug fixes.


= 2.12.1 =
Fixed: Compatibility issues in Post Blocks and Portfolio widget after Elementor 2.6
Fixed: PHP Warnings appearing after regenerating css.
Fixed: Blank space in widget area of Elementor editor.

= 2.12 =
New Widget: AE - ACF Fields. Added support for ACF Select, Checkbox, Radio, Button Group fields.
New Widget: AE - Taxonomy Blocks. It allows you to display terms from a taxonomy or child terms on taxonomy archive layouts.
Enhancement: Removed swiper library and use the one available within Elementor to reduce chances of conflict.
Enhancement: Now maintain sort order of ACF Relationship with the new order by option in Post Blocks - Manual Order.
Enhancement: Updated various js libraries.
Fixed: WPML compatibility for Date type field in AE - Custom Field widget.

= 2.11.4 =
* Fixed: Fatal error in some cases while editing Elementor Library Templates.

= 2.11.3 =
* Fixed issue with repeater fields not populating in repeater widget.
* Fixed issue with Sales badges not appearing for Variable Products.
* Fixed issue in Post Blocks widget. Images not loading with Infinite Ajax scroll in Mac Safari.
* Added typography and box shadow controls for pagination in Post Blocks & Portfolio widget.

= 2.11.2 =
* Fix:  Hide activated license key from admin settings page.
* Fixed issue with Post Blocks and Repeater Carousel throwing js error where there is no data to display.
* Fixed compatibility issue with ACF Pro that was generation PHP warnings in some cases.
* Fixed issue with oEmbed field content in ACF Repeater widget.
* Fixed issue with Slider navigation arrow for RTL Languages
* Enhancement: Added option in Post Block to disabled scroll to top feature on Ajax Pagination]
* Enhancement: Added option to control navigation arrow size in ACF Gallery widget.

= 2.11.1 =
* Fixed compatibility issues with Elementor 2.4
* Fixed issues with carousel and slider not working on mobile and tablet devices in various widgets.


= 2.11 =
* Enhancement: Smart Grid & Checkerboard Layouts in Post Blocks
* Enhancement: ACF Gallery Support for Background Slider
* Bug Fix: Fixed issue with wrong related posts.
* Bug Fix: Custom field links not working in AE ACF Repeater Widget
* Bug Fix: Wrong upsell products in Woo Products widget.
* Bug Fix: Infinite Scroll not working in some specific cases.

= 2.10.4 =
* Fixed compatibility issues with Elementor 2.3.3

= 2.10.3 =
* Fixed issue in Post Block causing html mess up in some cases.
* Fixed Product Sales Ribbons conflicting with links under it.
* Fixed wrong select in border control of Post Meta widget.


= 2.10.2 =
* Fixed issue with custom field type link not working with Repeater Blocks
* Fixed issue causing broken image in Post Image wiget.
* Added option in Post Block widget to limit number of pages in pagination.

= 2.10.1 =
* Fixed ACF Dependency bug.

= 2.10 =
* New Feature: Added support for ACF Repeater Fields (See tutorial here https://goo.gl/GEa44B)
* New Feature: 'AE - Taxonomy Custom Field' widget for taxonomy custom fields.
* Enhancement: Enhanced Woo Gallery widget for editor product archives. Flip and Swipe images on hover.
* Enhancement: Add Type - Date in AE - Custom Fields widget.
* Fixed issue in custom field widget with mail and tel links.
* Fixed issue in ACF Carousel with mobile view.



= 2.9.3 =
* Fixed issue with custom fields links. Link and Text fields are used in reversed order.
* Fixed RTL compatibility in Swiper library used in Post Blocks and ACF Gallery widget.
* Fixed issue with Dynamic links not working in Post Blocks widget after first page.
* Few other minor css and typo fixes.

= 2.9.2 =
* Fixed issue with background slider.

= 2.9.1 =
* Fixed autoplay issue with Post Slider/Carousel
* Compatibility fixes for upcoming update of Elementor
* Fixed issue with infinite scroll

= 2.9 =
* New Feature: Post Blocks - Custom Query Features
* New Feature: Post Blocks - Sales Badges for WooCommerce Products
* New Feature: Make whole Section/Column clickable with Dynamic link
* New Feature: Post Content - Content unfold
* Enhancement: ACF Gallery - Added support for free plugin for gallery field "ACF Photo Gallery Field"
* Bug Fixes: Updated Swiper library to fix issues with Carousel in ACF Gallery and Post Blocks widget.
* Bug Fixes: Fixed issues with Add to Cart widget not supporting some third party plugins.
* Bug Fixed: Fixed notices in various areas.


= 2.8.2 =
* Fixed issue with plugin's folder renamed after last update.
* Fixed notices on frontend.
* Fixed missing pagination issue in Post Blocks and Portfolio Widget.

= 2.8.1 =
* Fixed issue with fatal error in v2.8

= 2.8 =
New Features
* Post Slider & Carousel (Post Blocks)
* Infinite Scroll (Post Blocks)

Enhancements
* Dynamic background from post and term custom fields (for ACF only,  Pods support coming soon)
* Added option to strip title with Words and Letters in AE - Title widget.
* Added option in Post Blocks to filter posts by Author.
* Added lightbox option for ACF Gallery widget in Carousel mode.
* Added labels in Post Meta widget for Author, Date.
* Added functionality to define max length for Post Title. It will be helpful in grid & carousel structures when post titles are too long to fit.
* Added option in the custom field to link with the post.
* Added option in admin settings to enable compatibility for themes (that are not on officially preferred themes). This option will allow using our plugin on most of the themes (that supports Elementor Full Width & Elementor Canvas templates)

Bug Fixes
* Post Blocks pagination not working with WordPress default permalink structure.
* Woocommerce products not working with Elementor Canvas template.
* Post Image widget adding unnecessary HTML when there is not feature image available.
* Fixed issue with Post Block animations not working with ajax pagination.
* Layout placement using Hook Position not working for home pages.
* Fixed issue in Post Block layout with WPML Compatibility.

= 2.7 =
* New Widget: "AE Portfolio" Grid/List with filter bar. With same control over design as you have with Post Blocks widget.
* Added support for Twenty Seventeen Theme
* Elementor 2.0 compatibility issues fixed.
* Added support for "Elementor Full Width" template. (Just like previous support for Elementor Canvas)
* Enhancement in Content widget to show Term Description only on first page.
* Bug Fixes
    - Javascript conflict issue in ACF Gallery widget
    - Fixed fatal error with WPML installed but String Translation plugin not found.
    - Fixed issue in Post Block with Related posts not working for CPT's
    - Fixed issue with ajax add to cart in WooCommerce.

= 2.6.1 =
* Fixed issue in previous release with editing existing search template

= 2.6 =
* Enhancement: Carousel skin added in ACF Gallery
* Enhancement: Ordering by custom field in Post Blocks Widget
* Enhancement: WPML compatibility. All widgets are not completely translatable through WPML
* Enhancement: Custom Field widget - Dynamic link text from custom field (Type: Link)
* Enhancement: Custom Field Widget - Link image to current post/full image (Type: Image)
* Bug Fix: Woo Content widget not rendering shortcodes.
* Bug fix: Post Image widget - overlay not working in Elementor editor
* Along with some other minor bug fixes.

= 2.5.1 =
* Fixed issue with fatal error in existing some of the old AE Templates

= 2.5 =
* New Feature: Added support for a theme – Page Builder Framework
* New Feature: Added option in Post Blocks widget to show Related Posts.
* New Feature: Added option in Post Blocks widget to show posts from Relationship Field (ACF and Pods)
* Enhancement: Added option in Post Meta widget to disable links. Now you can disable links from post meta items like category, tag, author, and date.
* Enhancement: Added option to disable links in Taxonomy widget.
* Enhancement: Added “Enable Canvas” option for Single Post AE Templates.
  Now you won’t have to set canvas template on individual posts. Just check “Enable Canvas” option for AE Template and all your single post will work with Elementor Canvas.
* Enhancement: AE Template frontend preview won’t be accessible for non logged in users.
* Bug Fix: Fixed bug in ACF Gallery widget when there is no image available.
* And many other minor fixes and enhancements.

= 2.4.2 =
* Fixed issue with namespace.
* Fixed select2 library conflict with other plugins
* WooCommerce gallery issue fix (Lightbox was not closing).
* Strip shortcodes from Post Excerpt
* Fixed issue with hiding blank custom fields in Post Block widget.
* Fixed issue with Post Block widget - Pagination not working in some cases.
* Custom Field widget: added support for mailto & tel link.


= 2.4.1 =
* Fixed issue with Custom Field Map widget.
* Fixed issue with Global Shortcode.

= 2.4 =
New Features

* Ability to design Author Archives
* A new Render mode “Author Archive” has been added to allow creating layouts for Author Archive Pages
* Ability to design Date Archives
* A new Render mode “Date Archive” has been added to create the layout for your date archives.
* Added support for Hestia Theme (Free version)
* Map widget to render map using custom field data. It also allows styling map using Snazzy Maps.

Enhancements  & Tweaks

* Added option in AE – Content widget to show Category/Term description on Taxonomy Archive Pages.
* Added option to trigger the_content hooks for Post content.
* This will allow third-party plugins that automatically add some content before or after the post content. Eg. Social Media sharing plugins that added share buttons at the end of the post.
* Added support for shortcodes in custom field widget. Default and HTML mode now supports shortcodes.
* Custom Field widget – Hide area if no content is available in the custom field.
* Background Slider – Added option to disable Kenburns effect.

Bug Fixes

* Fixed issue with embeding Form in AE Templates


= 2.3.2 =
* Fixed: Elementor editor hanging in some cases.
* Tweak: BG Slider - Added option to disable Kendburns effect.

= 2.3.1 =
* Fixed bug causing issue with php 5.x
* Fixed conflict with admin script.


= 2.3 =
* Option to create global taxonomy archive layout.
* Design search page layout.
* Now support Astra Theme
* New Widget: Search Form
* New Widget: Breadcrumb (Required Yoast SEO installed)
* Bug fixes
    - Author widget: border radius not working when no link is selected
    - CSS issue in Post Navigation widget
    - Fixed issues in license activation.


= 2.2.1 =
* Fixed issue with OceanWP single template which got broken after last update.
* Removed alert message from ACF gallery widget.

= 2.2 =
* New: Background Slider (Add Background Slider to Sections & Columns)
* New Widget: ACF Gallery
* New Widget: Woo Products (Show related and upsell products on Woo Single Product Layouts)
* New Widget: Woo Notices (Show WooCommerce message section on top of Woo Product Page)
* New Widget: Post Comments (allows you to place your theme's comments section into AE Templates. More enhanced layout with customization options will come soon)
* Tweaks
    - Post Image widget - Added option to disable or change link type(Full Image/Post Link)
    - Now allows you to edit post content with Elementor even if AE Post layout is applied over it.
* Bug Fixes
    - AE Template for Pages were not working properly after last update.
    - AE template were not working if a custom post type is created with slug ‘product’
    - WooCommerce Scheme Structured Data was missing when using Ae Template for Single Product layout
    - Woo Add to Cart - Styling controls were not working for Variable products.

= 2.1 =
* New: Post Blocks Widget (Show posts in grid/list with layout of your choice)
* New: Author Widget (Display & Design author data like avatar,author name, author meta, author bio etc.)
* New: Full control over taxonomy archive layout with elementor canvas support.
* New: Full control over blog page/ CPT Archive layout with elementor canvas support.
* New: Ability to design 404 Template along with option to choose canvas template.
* Tweak: Added ACF Pro formatting support for date field.
* Tweak: Overlay option for Post Image widget.
* Fix: Issues with AE Template export.
* Fix: Compatibility with Elementor 1.5
* And lot of other minor enhancements, fixes and code improvements.

= 2.0 =
* New: WooCommerce Layout Designer
* New: Custom Post Type Archive Supported now.
* New: Support for oEmbed in custom field widget
* Tweak : Custom field link mode - option to open in new tab

= 1.3 =
* Fixed conflict with WooCommerce
* Corrected typo in author uri
* Fixed warning on Post Type Archive pages. (Post Type Archive support will be there in next release)


= 1.2 =
* Fixed issues in Post Navigation widget
* Post Meta Widget: Now supports modified date and published date
* Post Custom Field Widget: Now allows link, image and video from custom fields

= 1.1 =
* Fixed some issues with OceanWP single post layout.

= 1.0 =
* Plugin initial release