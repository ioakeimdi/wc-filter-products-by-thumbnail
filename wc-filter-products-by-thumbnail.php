<?php
/**
 * Plugin Name: Filter products by thumbnail
 * Description: Adds a filter to the admin products list to display products based on the presence of a thumbnail image.
 * Version:     1.0.0
 * Author:      id
 */

defined( 'ABSPATH' ) || exit;

class WC_Admin_Products_Filter_By_Thumbnail {
    
    function __construct() {
        add_action( 'restrict_manage_posts', array( $this, 'filter_by_the_thumbnail' ) );
        add_action( 'parse_query', array( $this, 'filter_products_by_thumbnail' ) );
    }

    /**
     * Add option in filters above table
     * 
     */
    function filter_by_the_thumbnail( $post_type ) {
        if ( $post_type !== 'product' ) {
            return;
        }
    
        ?>
            <select name="product_thumbnail_filter" onchange="this.form.submit()">
                <option value="">Thumbnail filter</option>
                <?php
                    $filter_img_opt = array( 'No Thumbnail', 'Thumbnail' );

                    $selected_opt = isset( $_GET['product_thumbnail_filter'] ) ? $_GET['product_thumbnail_filter'] : '';

                    foreach ( $filter_img_opt as $o => $opt ) {
                        ?>
                            <option value="<?php echo $o ?>" <?php selected( $selected_opt, $o ) ?>><?php echo $opt ?></option>
                        <?php
                    }
                ?>
            </select>
        <?php
    }

    function filter_products_by_thumbnail( $query ) {
        global $typenow;
    
        if ( $typenow !== 'product' || ! isset( $_GET['product_thumbnail_filter'] ) ) {
            return;
        }

        $thumbnail_filter = $_GET['product_thumbnail_filter'];
    
        if ( $thumbnail_filter === '0' ) {
            $query->set( 'meta_query', array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS',
                ),
            ));
            
            $query->set( 'post_status', 'publish' );
        } elseif ( $thumbnail_filter === '1' ) {
            $query->set( 'meta_query', array(
                    array(
                        'key'     => '_thumbnail_id',
                        'compare' => '=',
                    ),
                )
            );
        }
    }

}

new WC_Admin_Products_Filter_By_Thumbnail();