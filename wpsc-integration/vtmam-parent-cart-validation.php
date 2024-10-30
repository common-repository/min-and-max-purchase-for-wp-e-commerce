<?php
/*
VarkTech Min and Max Purchase for WP E-Commerce
WPSC-specific functions
Parent Plugin Integration
*/


class VTMAM_Parent_Cart_Validation {
	
	public function __construct(){
    
     /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++   
     *        Apply Min and Max Amount Rules to ecommerce activity
     *                                                          
     *          WPSC-Specific Checkout Logic and triggers 
     *                                               
     *  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++   */
     
          
    //the form validation filter executes ONLY at click-to-pay time to invalidate purchase if rule criteria not met                                                                           
    add_filter( 'wpsc_checkout_form_validation', array(&$this, 'vtmam_wpsc_checkout_form_validation'), 1);   
    
   
   /*  =============+
        // the form validation filter executes ONLY at click-to-pay time.  the '99' is to make sure this is last in line.                                                                          
        add_filter( 'wpsc_checkout_form_validation', array(&$this, 'vtmam_store_max_purchaser_info'), 99);
   
    Instead of using the form_validation filter above, which is pre-payment, use the post-payment action below,
    from  wp-e-commerce\wpsc-theme\functions\wpsc-transaction_results_functions.php   
   */      
    //action wpsc_confirm_checkout in wpsc-transaction_results_functions.php  is in a 'for' loop, and we only want to do this once.  Control via a 'done' switch.       
    add_action('wpsc_confirm_checkout', array( &$this, 'vtmam_post_purchase_save_info' ));
    /*  =============+ */
    
    
    
    /*  =============+                                    
    *  add action for entry into the shopping cart page -
    *    if we are on the shopping cart page, use the init action to run the apply rule function
    *    and display any minandmax purchase error messages at first viewof checkout page
    *            
       =============+                                    */
    $shopping_cart_url = get_option ('shopping_cart_url');    
    if ( $shopping_cart_url == $this->vtmam_currPageURL() ) {
        /*
          Priority of 99 to delay add_action execution.  Works normally on the 1st
          time through, and on any page refreshes.  The action kicks in 1st time on the page, and
          we're already on the shopping cart page and change to quantity happens.  The
          priority delays us in the exec sequence until after the quantity change has
          occurred, so we pick up the correct altered state.
          
          wpsc's own quantity change using:
               if ( isset( $_REQUEST['wpsc_update_quantity'] ) && ($_REQUEST['wpsc_update_quantity'] == 'true') ) {
        	add_action( 'init', 'wpsc_update_item_quantity' );
       */
       add_action( 'init', array(&$this, 'vtmam_wpsc_apply_checkout_cntl'),99 ); 
 
    }                                                                               
   /*  =============+++++++++++++++++++++++++++++++++++++++++++++++++++++++++    */
	}
	

           
  /* ************************************************
  **   Application - Apply Rules at E-Commerce Checkout
  *************************************************** */
	public function vtmam_wpsc_apply_checkout_cntl(){
    global $vtmam_cart, $vtmam_cart_item, $vtmam_rules_set, $vtmam_rule;
        
    //input and output to the apply_rules routine in the global variables.
    //    results are put into $vtmam_cart
    
    /*  We arrive here from a couple of different filters, depending on the situation.
    If error messages already processed (any error messages would be already processed 
    and the js 'injected') - no further processing.
    */
    if ( $vtmam_cart->error_messages_processed == 'yes' ) {  
      return;  
    }
    
     $vtmam_apply_rules = new VTMAM_Apply_Rules;   
     
    //ERROR Message Path
    if ( sizeof($vtmam_cart->error_messages) > 0 ) {      
      //insert error messages into checkout page
      add_action( "wp_enqueue_scripts", array($this, 'vtmam_enqueue_error_msg_css') );
      add_action('wp_head', array(&$this, 'vtmam_display_rule_error_msg_at_checkout') );  //JS to insert error msgs 
    }     
  } 
    
           
  /* ************************************************
  **   Application - Apply Rules when Payment button is pressed at checkout
  *
  * filter comes from wpsc-inlcudes/checkout.class.php -  
  *   $states array is part of the filter call, and must be returned.   
  *************************************************** */
	public function vtmam_wpsc_checkout_form_validation($states){
    global $vtmam_cart, $vtmam_cart_item, $vtmam_rules_set, $vtmam_info, $vtmam_rule;
        
    //input and output to the apply_rules routine in the global variables.
    //    results are put into $vtmam_cart
    
    /*  We arrive here from a couple of different filters, depending on the situation.
    If error messages already processed (any error messages would be already processed 
    and the js 'injected') - no further processing.
    */
    /*  NO Don't cut out => lifetime edits not yet done
    if ( $vtmam_cart->error_messages_processed == 'yes' ) {  
      return $states;  
    }
     */
             
            //this is the time to get purchaser info from screen!! (done at load cart info time)
            //$vtmam_info['get_purchaser_info'] = 'yes'; 
        
     $vtmam_apply_rules = new VTMAM_Apply_Rules;   
 
    //ERROR Message Path
    if ( sizeof($vtmam_cart->error_messages) > 0 ) {      
      //insert error messages into checkout page
      add_action( "wp_enqueue_scripts", array($this, 'vtmam_enqueue_error_msg_css') );
      add_action('wp_head', array(&$this, 'vtmam_display_rule_error_msg_at_checkout') );  //JS to insert error msgs      
      
      /*  turn on the messages processed switch
          otherwise errors are processed and displayed multiple times when the
          wpsc_checkout_form_validation filter finds an error (causes a loop around, 3x error result...) 
      */
      $vtmam_cart->error_messages_processed = 'yes';    
      
      /*  *********************************************************************
        Mark checkout as having failed edits, and can't progress to Payment Gateway. 
        This works only with the filter 'wpsc_checkout_form_validation', which is activated on submit of
        "payment" button. 
      *************************************************************************  */
      $is_valid = false;
      $bad_input_message =  '';
      $states = array( 'is_valid' => $is_valid, 'error_messages' => $bad_input_message );
     }
     
     return $states;   
  } 

  
  
  /* ************************************************
  **   Application - On Error Display Message on E-Commerce Checkout Screen  
  *************************************************** */ 
  public function vtmam_display_rule_error_msg_at_checkout(){
    global $vtmam_info, $vtmam_cart, $vtmam_setup_options;
     
    //error messages are inserted just above the checkout products, and above the checkout form
      //In this situation, this 'id or class Selector' may not be blank, supply wpsc checkout default - must include '.' or '#'
    if ( $vtmam_setup_options['show_error_before_checkout_products_selector']  <= ' ' ) {
       $vtmam_setup_options['show_error_before_checkout_products_selector'] = VTMAM_CHECKOUT_PRODUCTS_SELECTOR_BY_PARENT;             
    }
      //In this situation, this 'id or class Selector' may not be blank, supply wpsc checkout default - must include '.' or '#'
    if ( $vtmam_setup_options['show_error_before_checkout_address_selector']  <= ' ' ) {
       $vtmam_setup_options['show_error_before_checkout_address_selector'] = VTMAM_CHECKOUT_ADDRESS_SELECTOR_BY_PARENT;             
    }
     ?>     
        <script type="text/javascript">
        jQuery(document).ready(function($) {
    <?php 
    //loop through all of the error messages 
    //          $vtmam_info['line_cnt'] is used when table formattted msgs come through.  Otherwise produces an inactive css id. 
    //          msg_minandmax_label brings in the label (Minimum or Maximum) loaded at msg creation time by 'apply_rules'
    for($i=0; $i < sizeof($vtmam_cart->error_messages); $i++) { 
     ?>
        <?php 
          if ( $vtmam_setup_options['show_error_before_checkout_products'] == 'yes' ){ 

        ?>
           $('<div class="vtmam-error" id="line-cnt<?php echo $vtmam_info['line_cnt'] ?>"><h3 class="error-title"><?php echo $vtmam_cart->error_messages[$i]['msg_minandmax_label'] ?> Purchase Error</h3><p> <?php echo $vtmam_cart->error_messages[$i]['msg_text'] ?> </p></div>').insertBefore('<?php echo $vtmam_setup_options['show_error_before_checkout_products_selector'] ?>');
        <?php 
          } 
          if ( $vtmam_setup_options['show_error_before_checkout_address'] == 'yes' ){ 
           
        ?>
           $('<div class="vtmam-error" id="line-cnt<?php echo $vtmam_info['line_cnt'] ?>"><h3 class="error-title"><?php echo $vtmam_cart->error_messages[$i]['msg_minandmax_label'] ?>  Purchase Error</h3><p> <?php echo $vtmam_cart->error_messages[$i]['msg_text'] ?> </p></div>').insertBefore('<?php echo $vtmam_setup_options['show_error_before_checkout_address_selector'] ?>');
    <?php 
          }
    }  //end 'for' loop      
     ?>   
            });   
          </script>
     <?php    


     /* ***********************************
        CUSTOM ERROR MSG CSS AT CHECKOUT
        *********************************** */
     if ($vtmam_setup_options[custom_error_msg_css_at_checkout] > ' ' )  {
        echo '<style type="text/css">';
        echo $vtmam_setup_options[custom_error_msg_css_at_checkout];
        echo '</style>';
     }
     
     /*
      Turn off the messages processed switch.  As this function is only executed out
      of wp_head, the switch is only cleared when the next screenful is sent.
     */
     $vtmam_cart->error_messages_processed = 'no';   
 } 
 
   
  /* ************************************************
  **   Application - get current page url
  *************************************************** */ 
 public  function vtmam_currPageURL() {
     $pageURL = 'http';
     if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
     if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     }
     return $pageURL;
  } 
 
    

  /* ************************************************
  **   Application - On Error enqueue error style
  *************************************************** */
  public function vtmam_enqueue_error_msg_css() {
    wp_register_style( 'vtmam-error-style', VTMAM_URL.'/core/css/vtmam-error-style.css' );  
    wp_enqueue_style('vtmam-error-style');
  } 

 
  /* ************************************************
  **   After purchase, store max purchase info for lifetime rules on db
  *************************************************** */ 
  function vtmam_post_purchase_save_info () {
    global $post, $wpdb, $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info;
    
    //action wpsc_confirm_checkout in wpsc-transaction_results_functions.php  is in a 'for' loop, and we only want to do this once.    Control via a 'done' switch
    if ($vtmam_info['purch_hist_done'] == 'yes') {
      return;
    }

 
    
    for($i=0; $i < sizeof($vtmam_rules_set); $i++) {                                                               
      if ( ( $vtmam_rules_set[$i]->rule_status == 'publish' ) &&
           ( $vtmam_rules_set[$i]->maxRule_typeSelected_selection == 'lifetime') && 
           ( sizeof($vtmam_rules_set[$i]->inpop_found_list) > 0 ) )  {     //inpop has been found, rule purchase history needed
        
        /*  apply cart info to purchaser table
         $purchaser_max_purchase_row_id, $purchaser_max_purchase_row_qty_total, $purchaser_max_purchase_row_price_total all computed
         during apply-rules processing, so it's all ready for update here
        */
        //add new cart totals to existing history totals ...
        $rule_currency_total = $vtmam_rules_set[$i]->inpop_total_price + $vtmam_rules_set[$i]->purch_hist_rule_row_price_total;
        $rule_units_total    = $vtmam_rules_set[$i]->inpop_qty_total   + $vtmam_rules_set[$i]->purch_hist_rule_row_qty_total;
        $rule_anychoice_max  = $vtmam_rules_set[$i]->anyChoice_max['value']; 
        $rule_max_amt        = $vtmam_rules_set[$i]->minandmax_amt['value']; 

        if ($vtmam_rules_set[$i]->purch_hist_rule_row_id > ' ') {
          //update totals only, as needed
          $sql = "UPDATE `".MAX_PURCHASE_RULE_PURCHASER."` SET `rule_currency_total` = ".$rule_currency_total.", `rule_units_total` = ".$rule_units_total." WHERE `id`=".$vtmam_rules_set[$i]->purch_hist_rule_row_id."";
    	   	$wpdb->query($sql);
          $rule_purchaser_row_id = $vtmam_rules_set[$i]->purch_hist_rule_row_id;
        } else {
          //add max_purchase_row          
          //$next_rule_purch_id = $wpdb->get_var("SELECT LAST_INSERT_ID() AS `id` FROM `".MAX_PURCHASE_RULE_PURCHASER."` LIMIT 1");
          //$next_rule_purch_id = $next_rule_purch_id + 1;
          $next_rule_purch_id; //supply null value for use with autoincrement table key

          $wpdb->query("INSERT INTO `".MAX_PURCHASE_RULE_PURCHASER."` (`id`,`rule_id`,`purchaser_ip_address`,`purchaser_email`,
          `billto_name`,`billto_address`, `billto_city`,`billto_state`,`billto_postcode`,`billto_country`,
          `shipto_name`,`shipto_address`, `shipto_city`,`shipto_state`,`shipto_postcode`,`shipto_country`,
          `rule_currency_total`,`rule_units_total`, 
          `orig_rule_inpop_selection`,`orig_rule_inpop_varprodid`,`orig_rule_var_in_checked`,
          `orig_rule_inpop_singleprodid`,`orig_rule_prodcat_in_checked`,`orig_rule_rulecat_in_checked`,
          `orig_rule_role_in_checked`,`orig_rule_role_and_or_in_selection`,`orig_rule_specchoice_in_selection`,
          `orig_rule_anychoice_max`,`orig_rule_amtselected_selection`,`orig_rule_minandmax_amt`) 
          VALUES ('{$next_rule_purch_id}','{$vtmam_rules_set[$i]->post_id}','{$vtmam_cart->purchaser_ip_address}','{$vtmam_cart->purchaser_email}', 
          '{$vtmam_cart->billto_name}','{$vtmam_cart->billto_address}','{$vtmam_cart->billto_city}','{$vtmam_cart->billto_state}','{$vtmam_cart->billto_postcode}','{$vtmam_cart->billto_country}',
          '{$vtmam_cart->shipto_name}','{$vtmam_cart->shipto_address}','{$vtmam_cart->shipto_city}','{$vtmam_cart->shipto_state}','{$vtmam_cart->shipto_postcode}','{$vtmam_cart->shipto_country}',
          '{$rule_currency_total}','{$rule_units_total}',
          '{$vtmam_rules_set[$i]->inpop_selection}','{$vtmam_rules_set[$i]->inpop_varProdID}','{$vtmam_rules_set[$i]->var_in_checked}',
          '{$vtmam_rules_set[$i]->inpop_singleProdID}','{$vtmam_rules_set[$i]->prodcat_in_checked}','{$vtmam_rules_set[$i]->rulecat_in_checked}',
          '{$vtmam_rules_set[$i]->role_in_checked}','{$vtmam_rules_set[$i]->role_and_or_in_selection}','{$vtmam_rules_set[$i]->specChoice_in_selection}',
          '{$rule_anychoice_max}','{$vtmam_rules_set[$i]->amtSelected_selection}','{$rule_max_amt}' );");
          
          $rule_purchaser_row_id = $wpdb->get_var("SELECT LAST_INSERT_ID() AS `id` FROM `".MAX_PURCHASE_RULE_PURCHASER."` LIMIT 1");
        }

 
        //add cart info to product table
         for($k=0; $k < sizeof($vtmam_rules_set[$i]->inpop_found_list); $k++) { 
            
            //add new cart totals to existing history totals ...
            $product_price_total = $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_total_price'] + $vtmam_rules_set[$i]->inpop_found_list[$k]['purch_hist_product_price_total'];
            $product_qty_total   = $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty']         + $vtmam_rules_set[$i]->inpop_found_list[$k]['purch_hist_product_qty_total'];
            
            if ( $vtmam_rules_set[$i]->inpop_found_list[$k]['purch_hist_product_row_id'] > ' ' ) {
                //update row
                $sql = "UPDATE `".MAX_PURCHASE_RULE_PRODUCT."` SET `product_price_total` = ".$product_price_total.", `product_qty_total` = ".$product_qty_total." WHERE `id`=".$vtmam_rules_set[$i]->inpop_found_list[$k]['purch_hist_product_row_id']."";
  	   	        $update = $wpdb->query($sql);
            } else {
                //insert row
                //$next_rule_prod_id = $wpdb->get_var("SELECT LAST_INSERT_ID() AS `id` FROM `".MAX_PURCHASE_RULE_PRODUCT."` LIMIT 1");
                //$next_rule_prod_id = $next_rule_prod_id + 1;
                $next_rule_prod_id; //supply null value for use with autoincrement  table key
                
                $prod_id            = $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_id'];
                $prod_name          = $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_name'];
                
                $wpdb->query("INSERT INTO `".MAX_PURCHASE_RULE_PRODUCT."` (`id`,`rule_purchaser_row_id`,`rule_id`,
                `product_id`,`product_title`,`product_price_total`,`product_qty_total` ) 
                VALUES ('{$next_rule_prod_id}','{$rule_purchaser_row_id}','{$vtmam_rules_set[$i]->post_id}',
                '{$prod_id}','{$prod_name}','{$product_price_total}','{$product_qty_total}' );");
            }           
         }       
                                                     
      } 
    } //end for loop
    
    
    $vtmam_info['purch_hist_done'] = 'yes' ;  //mark action as completed, tested at top in next iteration...
  } // end  function vtmam_store_max_purchaser_info()    
 
} //end class
$vtmam_parent_cart_validation = new VTMAM_Parent_Cart_Validation;