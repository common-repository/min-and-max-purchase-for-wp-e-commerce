=== Min and Max Purchase for WP E-Commerce ===
Contributors: vark
Donate link: http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/
Tags: e-commerce, WP e-Commerce, shop, store, admin, price, pricing, minandmax, purchase, limits, checkout
Requires at least: 3.3
Tested up to: 4.1
Stable tag: 1.06
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin allows you to set up minandmax purchase rules for products in your store.  Purchases must meet these rules to proceed to checkout payment.



== Description ==

The Min and Max Purchase plugin for WP E-Commerce gives you the ability to set up minandmax purchase rules for products in your WP E-commerce 3.8+ store.  Customer purchases must then meet these rules, to proceed to checkout payment.

If a purchase in your store fails a minimum or maximum purchase rule, an error message appears at the top of the checkout page, identifying the error situation and rule requirements.  The customer must resolve the error, before the purchase can be completed.   

Works with WP E-Commerce 3.9+ 


= Introductory Video =
[youtube http://www.youtube.com/watch?v=Og2_mTueCjQ]


[Tutorials](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=tutorial) | 
[Documentation](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=documentation) | 
[Videos](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=video) | 
[Shameless E-Commerce](http://www.varktech.com/wp-e-commerce/minandmax-purchase-pro-for-wp-e-commerce/)


= Plugin Summary =
With this plugin, you can establish rules to to set threshholds or limit purchases made in your store. If the customer purchases in your cart exceed the rule limits, a checkout error message appears.  The customer can't pay for the goods, until the purchases are corrected to fall below the error threshhold.  

= Pro Plugin Summary =
The Pro Plugin allows you to set your min or max purchase rules for a single product/variations, by product or custom categories, and allows you to set maximum customer lifetime purchase limits. [Min and Max Purchase Pro Plugin](http://www.varktech.com/wp-e-commerce/minandmax-purchase-pro-for-wp-e-commerce/) 

= How does the rule choose to examine the cart? [Search Criteria]  =

*   By  Role / Membership / logged in status, for cart contents  (allows different pricing rules for customer types / logged-in role, such as wholesalers or preferred customers).


= How does the rule choose to examine the cart? [Search Criteria - Pro Plugin]  =

*   By cart contents
*   For a single product
*   For a single product's variations
*   By Product Category or Maximum Purchase Category, and/or By Role/Membership/logged in status


= How is the rule applied to the cart search results? [Rule applied to] =
*   All : work with the total of the units/prices
*   Each : apply the rule to each product in the Rule Population
*   Any : Same as each, but limits the rule testing to the first X number of products.


= Rule Applies To Either: =
*   Units Quantity Amount
*   Price Amount


= A sample of a minandmax purchase rule: =
*   If the purchaser is a Subscriber - [search criteria:Subscriber]
*   The minandmax total for all purchases - [rule applied to:  all]
*   Must be less than $20. - [price amount: $20]


= Checkout Error Messaging =
At checkout, the rules are tested against the cart contents.  If products are found in error, an error message (in two possible locations) will be displayed.  The error situation must be resolved, before the customer is allowed to leave the checkout and proceed to payment. 

Error messaging css can be customized using the custom css option on the Rule Options Settings screen.  There are also currency sign options, and a comprehensive debugging mode option.


= Checkout Error Message Formats =
*   Text-based descriptive format
*   Table-based format


= More Info =
[Tutorials](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=tutorial) | 
[Documentation](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=documentation) | 
[Videos](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=video) | 
[Shameless E-Commerce](http://www.varktech.com/wp-e-commerce/minandmax-purchase-pro-for-wp-e-commerce/)


= Additional Plugins by VarkTech.com =
1. [Minimum Purchase for WP E-Commerce](http://wordpress.org/extend/plugins/minimum-purchase-for-wp-e-commerce)
1. [Min or Max Purchase for WP E-Commerce](http://wordpress.org/extend/plugins/min-or-max-purchase-for-wp-e-commerce) 
1. [Pricing Deals Pro for WP E-Commerce](http://www.varktech.com/wp-e-commerce/pricing-deals-pro-for-wp-e-commerce/) 


= Pricing Deals Pro offers you complete flexibility creating pricing deals =
*   Buy two of these, get 10% off of both
*   Buy two of these, get 10% off another purchase
*   Buy two of these, get one of those free
*   Pricing Deals of any sort, by Role/Membership
*   etc....

=> [Pretty much any deal you can think of, you"ll be able to do!](http://www.varktech.com/wp-e-commerce/pricing-deals-pro-for-wp-e-commerce/) 



= Role/Membership/logged in status,  for Search Criteria - More Info =

*Role/Membership is used within Wordpress to control access and capabilities, when a role is given to a user.  Wordpress assigns certain roles by default such as Subscriber for new users or Administrator for the site's owner. Roles can also be used to associate a user with a pricing level.  Use a role management plugin like [User Role Editor](http://wordpress.org/extend/plugins/user-role-editor/) to establish custom roles, which you can give to a user or class of users.  Then you can associate that role with a Maximum Purchase Rule.  So when the user logs into your site, their Role interacts with the appropriate Rule.  A rule can also check against 'not logged in' status, making the rule apply to 'all in cart'.*




== Installation ==

= Minimum Requirements =

*   WP E-Commerce 3.8+
*   WordPress 3.3+
*   PHP 5+

= Install Instructions =

1. Upload the folder `minandmax-purchase-for-wp-e-commerce` to the `/wp-content/plugins/` directory of your site
1. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

Please review the following printed and video documentation.

[Tutorials](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=tutorial) | 
[Documentation](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=documentation) | 
[Videos](http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=video) | 
[Shameless E-Commerce](http://www.varktech.com/wp-e-commerce/minandmax-purchase-pro-for-wp-e-commerce/)

 Please post questions at the [Support](http://www.varktech.com/support/) page at varktech.com.


== Screenshots ==

1. Maximum Purchase Rule Screen
2. Group Search Criteria
3. Rule application method - Any
4. Rule application method - Each
5. Rule application method - All
6. Quantity or Price Maximum Amount
7. Error Message at Checkout




== Changelog ==

= 1.06 - 2013-02-23 =
* Bug Fix - "unexpected T_CLASS/T_PUBLIC" - File admin/vtmXX-rules-ui.php was corrupted, but the corruption only showed up on some hosts (?!).  Huge thanks to Don for allowing full access to his installation to debug.   

= 1.05 - 2013-02-13 =
* Bug Fix - Rule Add screen was being overwritten by some other plugins' global metaboxes - thanks to Dagofee for debug help
* Bug Fix - PHP version check not being executed correctly on activation hook (minimum PHP version 5 required)
* Bug Fix - Nuke and Repair buttons on Options screen were also affecting main Options settings, now fixed

= 1.03 - 2012-12-17 =
* Lifetime purchase limit option support changes added, 
* Fix to 'nuke' function on options page - repairs the situation where the nuke rules button is hit, and the options record goes away.

= 1.02 - 2012-10-24 =
* Upgrade reporting wording, 
* currency symbol, 
* moved activation hook to is-admin

= 1.01 - 2012-10-08 =
* Fix to Table Format Error Reporting in 'Each' and 'Any' mode

= 1.0 - 2012-09-12 =
* Initial Public Release

== Upgrade Notice ==

= 1.02 - 2012-10-24 =
* Upgrade reporting wording, 
* currency symbol, 
* moved activation hook to is-admin

= 1.0 - 2012-09-12 =
* Initial Public Release