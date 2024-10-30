<?php

class VTMAM_Backbone{   
	
	public function __construct(){
		  $this->vtmam_register_post_types();
      $this->vtmam_add_dummy_rule_category();
   //   add_filter( 'post_row_actions', array(&$this, 'vtmam_remove_row_actions'), 10, 2 );

	}
  
  public function vtmam_register_post_types() {
   global $vtmam_info;
  
  $tax_labels = array(
		'name' => _x( 'Min and Max Purchase Categories', 'taxonomy general name', 'vtmam' ),
		'singular_name' => _x( 'Min and Max Purchase Category', 'taxonomy singular name', 'vtmam' ),
		'search_items' => __( 'Search Min and Max Purchase Category', 'vtmam' ),
		'all_items' => __( 'All Min and Max Purchase Categories', 'vtmam' ),
		'parent_item' => __( 'Min and Max Purchase Category', 'vtmam' ),
		'parent_item_colon' => __( 'Min and Max Purchase Category:', 'vtmam' ),
		'edit_item' => __( 'Edit Min and Max Purchase Category', 'vtmam' ),
		'update_item' => __( 'Update Min and Max Purchase Category', 'vtmam' ),
		'add_new_item' => __( 'Add New Min and Max Purchase Category', 'vtmam' ),
		'new_item_name' => __( 'New Min and Max Purchase Category', 'vtmam' )
  ); 	

  
  $tax_args = array(
    'hierarchical' => true,
		'labels' => $tax_labels,
		'show_ui' => true,
		'query_var' => false,
		'rewrite' => array( 'slug' => 'vtmam_rule_category' )
  ) ;            

  $taxonomy_name =  'vtmam_rule_category';
 
  
   //REGISTER TAXONOMY 
  	register_taxonomy($taxonomy_name, $vtmam_info['applies_to_post_types'], $tax_args); 
    
        
 //REGISTER POST TYPE
 $post_labels = array(
				'name' => _x( 'Min and Max Purchase Rules', 'post type name', 'vtmam' ),
        'singular_name' => _x( 'Min and Max Purchase Rule', 'post type singular name', 'vtmam' ),
        'add_new' => _x( 'Add New', 'admin menu: add new Min and Max Purchase Rule', 'vtmam' ),
        'add_new_item' => __('Add New Min and Max Purchase Rule', 'vtmam' ),
        'edit_item' => __('Edit Min and Max Purchase Rule', 'vtmam' ),
        'new_item' => __('New Min and Max Purchase Rule', 'vtmam' ),
        'view_item' => __('View Min and Max Purchase Rule', 'vtmam' ),
        'search_items' => __('Search Min and Max Purchase Rules', 'vtmam' ),
        'not_found' =>  __('No Min and Max Purchase Rules found', 'vtmam' ),
        'not_found_in_trash' => __( 'No Min and Max Purchase Rules found in Trash', 'vtmam' ),
        'parent_item_colon' => '',
        'menu_name' => __( 'Min and Max Purchase Rules', 'vtmam' )
			);
      
	register_post_type( 'vtmam-rule', array(
		  'capability_type' => 'post',
      'hierarchical' => true,
		  'exclude_from_search' => true,
      'labels' => $post_labels,
			'public' => true,
			'show_ui' => true,
      'query_var' => true,
      'rewrite' => false,     
      'supports' => array('title' )	 //remove 'revisions','editor' = no content/revisions boxes 
		)
	);
 
	$role = get_role( 'administrator' );
	$role->add_cap( 'read_vtmam-rule' );
}

  public function vtmam_add_dummy_rule_category () {
      $category_list = get_terms( 'vtmam_rule_category', 'hide_empty=0&parent=0' );
    	if ( count( $category_list ) == 0 ) {
    		wp_insert_term( __( 'Min and Max Purchase Category', 'vtmam' ), 'vtmam_rule_category', "parent=0" );
      }
  }


/*------------------------------------------------------------------------------------
  	remove quick edit for custom post type 
  ------------------------------------------------------------------------------------*/
 /*
  public function vtmam_remove_row_actions( $actions, $post )
  {
    global $current_screen;
  	if( $current_screen->post_type = 'vtmam-rule' ) {
    	unset( $actions['edit'] );
    	unset( $actions['view'] );
    	unset( $actions['trash'] );
    	unset( $actions['inline hide-if-no-js'] );
  	//$actions['inline hide-if-no-js'] .= __( 'Quick&nbsp;Edit' );
     }
  	return $actions;
  }
*/





function vtmam_register_settings() {
    register_setting( 'vtmam_options', 'vtmam_rules' );
} 


} //end class
$vtmam_backbone = new VTMAM_Backbone;
  
  
  
  class VTMAM_Functions {   
	
	public function __construct(){

	}
    
  function vtmam_getSystemMemInfo() 
  {       
      $data = explode("\n", file_get_contents("/proc/meminfo"));
      $meminfo = array();
      foreach ($data as $line) {
          list($key, $val) = explode(":", $line);
          $meminfo[$key] = trim($val);
      }
      return $meminfo;
  }
  
  } //end class