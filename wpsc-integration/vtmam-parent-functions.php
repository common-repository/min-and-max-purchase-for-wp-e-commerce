<?php
/*
VarkTech Min and Max Purchase for WP E-Commerce
WPSC-specific functions
Parent Plugin Integration
*/


class VTMAM_Parent_Functions {
	
	public function __construct(){
    
 
	}
	
 	
	public function vtmam_load_vtmam_cart_for_processing(){
      global $wpdb, $wpsc_cart, $vtmam_cart, $vtmam_cart_item, $vtmam_info; 

      $vtmam_cart = new VTMAM_Cart;  

      foreach($wpsc_cart->cart_items as $key => $cart_item) {
        $vtmam_cart_item                = new VTMAM_Cart_Item;
        //load up the wpsc values into $vtmam_cart_item
        $vtmam_cart_item->product_id    = $cart_item->product_id;
        
        //get post for current product id, call it var post
        //   if post_parent > 0, this is a variation product!
        $var_post = get_post($cart_item->product_id);
        if ($var_post->post_parent > 0) {
          // cats associated with parent, not variation...
          $cart_item_id_for_cats = $var_post->post_parent; 
        } else {
          //default to current cart item product id  if product not a variation
          $cart_item_id_for_cats = $cart_item->product_id;
        }
        
         
        /* there's a WPEC variation title bug in the checkout cart.  Verify if product is 
        * a variation id, then if so, verify that the title has an 
        * open paren (standard variation title naming).  If not, go
        * directly to the variation post and get the title.                                      
        */
        if ($var_post->post_parent > 0) {  
           if ( !strstr($cart_item->product_name, '(') ) {              
              $cart_item->product_name = $var_post->post_title ;
           }                      
        } 
        
        $vtmam_cart_item->product_name  = $cart_item->product_name;
        $vtmam_cart_item->quantity      = $cart_item->quantity;
        $vtmam_cart_item->unit_price    = $cart_item->unit_price;
        $vtmam_cart_item->total_price   = $cart_item->total_price;
       
        /*  *********************************
        ***  JUST the cat *ids* please...
        ************************************ */
        $vtmam_cart_item->prod_cat_list = wp_get_object_terms( $cart_item_id_for_cats, $vtmam_info['parent_plugin_taxonomy'], $args = array('fields' => 'ids') );
        $vtmam_cart_item->rule_cat_list = wp_get_object_terms( $cart_item_id_for_cats, $vtmam_info['rulecat_taxonomy'], $args = array('fields' => 'ids') );
        //*************************************              
        
        //add cart_item to cart array
        $vtmam_cart->cart_items[]       = $vtmam_cart_item; 
        
      } 
      
      /*
       ($vtmam_info['get_purchaser_info'] == 'yes') is set in parent-cart-validation.php in 
       function vtmam_wpsc_checkout_form_validation only.  This is executed only at 'pay' button,
       the only time we can be sure that the purchaser info is there.
      */ 
 //     if( defined('VTMAM_PRO_DIRNAME') && ($vtmam_info['get_purchaser_info'] == 'yes') )  {
      if(defined('VTMAM_PRO_DIRNAME')) {
        require ( VTMAM_PRO_DIRNAME . '/wpsc-integration/vtmam-get-purchaser-info.php' );   
      }
     
  }
      
 
   /*
    *  checked_list (o) - selection list from previous iteration of rule selection                               
    *                           
   */
   
   /*
      $product_variation_IDs = $this->vtmam_get_variations_list($product_ID);
      if ($product_variation_IDs) {
         $this->vtmam_post_category_meta_box($post, array( 'args' => array( 'taxonomy' => 'variations', 'tax_class' => 'var-in', 'checked_list' => $vtmam_rule->var_in_checked, 'product_ID' => $product_ID, 'product_variation_IDs' => $product_variation_IDs )));/
         /perform  vtmam_post_category_meta_box , use $tax_class = 'variations'
         // in the vtmam_post_category_meta_box
         $vtmam_parent_functions->vtmam_fill_variations_checklist($tax_class, $checked_list, $product_ID, $product_variation_IDs);  //add checked logic later
      } 
   */
    public function vtmam_fill_variations_checklist ($tax_class, $checked_list = NULL, $product_ID, $product_variation_IDs) { 
        global $post;
                  
       // echo '<br>$product_variation_IDs = <pre>'.print_r( $product_variation_IDs , true).'</pre><br>' ; 
       // echo '<br>$checked_list = <pre>'.print_r( $checked_list , true).'</pre><br>' ; 
        
        foreach ($product_variation_IDs as $product_variation_ID) {     //($product_variation_IDs as $product_variation_ID => $info)
            $post = get_post($product_variation_ID);
            $output  = '<li id='.$product_variation_ID.'>' ;
            $output  .= '<label class="selectit">' ;
            $output  .= '<input id="'.$product_variation_ID.'_'.$tax_class.' " ';
            $output  .= 'type="checkbox" name="tax-input-' .  $tax_class . '[]" ';
            $output  .= 'value="'.$product_variation_ID.'" ';
            if ($checked_list) {
                if (in_array($product_variation_ID, $checked_list)) {   //if variation is in previously checked_list   
                   $output  .= 'checked="checked"';
                }                
            }
            $output  .= '>'; //end input statement
            $output  .= '&nbsp;' . $post->post_title;
            $output  .= '</label>';            
            $output  .= '</li>';
              echo $output ;
         }
        return;   
    }
    

  /* ************************************************
  **   Get all variations for product
  *************************************************** */
  public function vtmam_get_variations_list($product_ID) {
        
    //do variations exist?
    $product_has_variations = $this->vtmam_test_for_variations ($product_ID); 
    
    if ($product_has_variations == "yes") {    
      //get all variation IDs (title will be obtained in checkbox logic)
      /*Loop through product variations saved previously and create array of the variations *only* 
      * tt.`parent` > '0' ==> parent = 0 indicates a variation set name rather than a variation set member    
      * the inner select gets the 'child' variation posts (status = 'inherit'), then the outer select passes by the variation set name post  
      * 
      *the inner select will eventually be slow, but won't be accessed that often, so is currently acceptable.  The alternative is massively complex
      * (use db_id to go to term_rel and get the variation set name term_tax_id, get all of the term_tax_ids of the varition set and variations, get all of the obj_id's they own and compare to posts.id...)                    	
       */
      global $wpdb;
    	$varsql = "SELECT tr.`object_id` 
          FROM `".$wpdb->term_relationships."` AS tr 
    			LEFT JOIN `".$wpdb->term_taxonomy."` AS tt
          ON  tr.`term_taxonomy_id` = 	tt.`term_taxonomy_id`	
    			WHERE  tr.`object_id` in 
               ( SELECT posts.`id` 
            			FROM `".$wpdb->posts."` AS posts			
            			WHERE posts.`post_status` = 'inherit' AND posts.`post_parent`= '" . $product_ID . "'
                )
           AND  tt.`parent` > '0'      
            ";                    
    	$product_variations_list = $wpdb->get_col($varsql);  // yields an array of child post ids (variations, where the $$, sku etc are held).
    } else  {
      $product_variations_list;
    }
    
    return ($product_variations_list);
  } 
  
  
  public function vtmam_test_for_variations ($prod_ID) { 
     $vartest_response = 'no';
     if ( wpsc_product_has_variations( $prod_ID ) )  {
        $vartest_response = 'yes';
     }
      return ($vartest_response);   
  }     
    
} //end class
$vtmam_parent_functions = new VTMAM_Parent_Functions;