<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/woocommerce-role-based-price/
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    @TODO
 * @subpackage @TODO
 * @author     Varun Sridharan <varunsridharan23@gmail.com>
 */
if ( ! defined( 'WPINC' ) ) { die; }

class WooCommerce_Single_Product_Checkout_Product_Settings extends WooCommerce_Single_Product_Checkout_Admin {

    /**
     * Inits Class And Runs It
     */
    public function __construct() { 
        add_filter( 'woocommerce_product_data_tabs', array($this,'add_product_data_tab'));
        add_action( 'woocommerce_process_product_meta_simple', array($this, 'save_meta'));
        add_action( 'woocommerce_process_product_meta_variable', array($this, 'save_meta'));
        add_action( 'woocommerce_product_data_panels', array($this,'add_settings'));
    }  
    
    public function add_product_data_tab($tabs){
        $tabs['sku_shortlinks'] = array(
            'label'  => $this->__('Single Product Checkout'),
            'target' => 'single_product_checkout', 
            'class'  => array(),
        );
        return $tabs;
    }
    
    
    public function add_settings(){
        global $post_id;
        $status = get_post_meta( $post_id, WC_SPC_DBKEY.'product_status',  true );
        $qty = get_post_meta( $post_id, WC_SPC_DBKEY.'product_allowed_qty',  true ); 
        $qty2 = get_post_meta( $post_id, WC_SPC_DBKEY.'product_minimum_qty',  true ); 

    ?>

        <div id="single_product_checkout" class="panel woocommerce_options_panel">
            <div class="options_group">
                <?php woocommerce_wp_checkbox(
                        array( 
                            'id' => WC_SPC_DBKEY.'product_status', 
                            'label' =>$this->__('Enable Single Checkout'), 
                            'cbvalue' => 'yes', 
                            'desc_tip' => 'true', 
                            'description' => $this->__('Allows Users To Checkout This Product Separately'),
                            'value' => esc_attr($status) 
                        )
                   ); ?>
            </div>
            
            <div class="options_group">
                <?php woocommerce_wp_text_input(  
                        array( 
                            'id' => WC_SPC_DBKEY.'product_minimum_qty', 
                            'label' => $this->__('Min Required Qty'), 
                            'desc_tip' => 'true', 
                            'description' => $this->__('Minimum required Qty Per Order'), 
                            'value' => intval($qty2), 
                            'type' => 'number', 
                            'custom_attributes' => array('step'=> '1')
                        )
                    ); ?>
            </div>
            
            <div class="options_group">
                <?php woocommerce_wp_text_input(  
                        array( 
                            'id' => WC_SPC_DBKEY.'product_allowed_qty', 
                            'label' => $this->__('Max Allowed Qty'), 
                            'desc_tip' => 'true', 
                            'description' => $this->__('Max Allowed Qty Per Order'), 
                            'value' => intval($qty), 
                            'type' => 'number', 
                            'custom_attributes' => array('step'=> '1')
                        )
                    ); ?>
            </div>
            <div class="options_group"></div>
        </div>
    <?php
    
    
    }
    
    public function save_meta($post_id){
        
        if(isset($_POST[WC_SPC_DBKEY.'product_status'])){
            update_post_meta($post_id,WC_SPC_DBKEY.'product_status','yes');
        } else {
            update_post_meta($post_id,WC_SPC_DBKEY.'product_status','');
        }

        if(isset($_POST[WC_SPC_DBKEY.'product_allowed_qty'])){
           $value = intval($_POST[WC_SPC_DBKEY.'product_allowed_qty']);
           update_post_meta($post_id,WC_SPC_DBKEY.'product_allowed_qty',$value); 
        }
        
        if(isset($_POST[WC_SPC_DBKEY.'product_minimum_qty'])){
           $value = intval($_POST[WC_SPC_DBKEY.'product_minimum_qty']);
           update_post_meta($post_id,WC_SPC_DBKEY.'product_minimum_qty',$value); 
        }
        
        
    }
}