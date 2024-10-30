<?php
/*
WPSC-specific functions
Parent Plugin Integration
*/


class VTMAM_Parent_Definitions {
	
	public function __construct(){
    
    define('VTMAM_PARENT_PLUGIN_NAME',                      'WP E-Commerce');
    define('VTMAM_EARLIEST_ALLOWED_PARENT_VERSION',         '3.8');
    define('VTMAM_TESTED_UP_TO_PARENT_VERSION',             '3.8.8.5');
    define('VTMAM_DOCUMENTATION_PATH_PRO_BY_PARENT',        'http://www.varktech.com/wp-e-commerce/minandmax-purchase-pro-for-wp-e-commerce/?active_tab=tutorial');                                                                                                     //***
    define('VTMAM_DOCUMENTATION_PATH_FREE_BY_PARENT',       'http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=tutorial');      
    define('VTMAM_INSTALLATION_INSTRUCTIONS_BY_PARENT',     'http://www.varktech.com/wp-e-commerce/minandmax-purchase-for-wp-e-commerce/?active_tab=instructions');
    define('VTMAM_PRO_INSTALLATION_INSTRUCTIONS_BY_PARENT', 'http://www.varktech.com/wp-e-commerce/minandmax-purchase-pro-for-wp-e-commerce/?active_tab=instructions');
    define('VTMAM_PURCHASE_PRO_VERSION_BY_PARENT',          'http://www.varktech.com/wp-e-commerce/minandmax-purchase-pro-for-wp-e-commerce/');
    define('VTMAM_DOWNLOAD_FREE_VERSION_BY_PARENT',         'http://wordpress.org/extend/plugins/minandmax-purchase-for-wp-e-commerce/');
    
    //html selector locations in checkout where error message will display before.
    define('VTMAM_CHECKOUT_PRODUCTS_SELECTOR_BY_PARENT',    '.checkout_cart');
    define('VTMAM_CHECKOUT_ADDRESS_SELECTOR_BY_PARENT',     '.wpsc_checkout_forms');

    global $vtmam_info;      
    $vtmam_info = array(                                                                    
      	'parent_plugin' => 'wpsc',
      	'parent_plugin_taxonomy' => 'wpsc_product_category',
        'parent_plugin_taxonomy_name' => 'Product Category',
        'parent_plugin_cpt' => 'wpsc_product',
        'applies_to_post_types' => 'wpsc-product', //rule cat only needs to be registered to product, not rule as well...
        'rulecat_taxonomy' => 'vtmam_rule_category',
        'rulecat_taxonomy_name' => 'Min and Max Purchase Rules',
        
        /* *************************************************** */
        /*
                        THE FOLLOWING ELEMENTS                      
          are used as temporary iterative processing storage
          in vtmam-apply-rules.php
                                                               */
        /* *************************************************** */
        //elements used at the ruleset level
        'error_message_needed' => 'no',
        'cart_grp_info' => '',
          /*  cart_grp_info will contain the following:
            array(
              'qty'    => '',
              'price'    => ''
            )
          */
        'cart_color_cnt' => '',
        'rule_id_list' => '',
        'line_cnt' => 0,
        'action_cnt'  => 0,
        'bold_the_error_amt_on_detail_line'  => 'no',
        
        //elements used at the ruleset/product level 
        'purch_hist_product_row_id'  => '',              
        'purch_hist_product_price_total'  => '',      
        'purch_hist_product_qty_total'  => '',          
        'get_purchaser_info' => '',          
        'purch_hist_done' => ''          
      );

	}
	
  
} //end class
$vtmam_parent_definitions = new VTMAM_Parent_Definitions;