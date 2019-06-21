<?php
/**
 * emag Custom Metabox
 *
 * @package emag
 */
$emag_post_types = array(
    'post',
    'page'
);

add_action('add_meta_boxes', 'emag_add_layout_metabox');
function emag_add_layout_metabox() {
    global $post;
    $frontpage_id = get_option('page_on_front');
    if( $post->ID == $frontpage_id ){
        return;
    }

    global $emag_post_types;
    foreach ( $emag_post_types as $post_type ) {
        add_meta_box(
            'emag_layout_options', // $id
            __( 'Layout options', 'emag' ), // $title
            'emag_layout_options_callback', // $callback
            $post_type, // $page
            'normal', // $context
            'high'// $priority
        );
    }

}
/* emag sidebar layout */
$emag_default_layout_options = array(
    'left-sidebar' => array(
        'value'     => 'left-sidebar',
        'thumbnail' => get_template_directory_uri() . '/inc/images/left-sidebar.png'
    ),
    'right-sidebar' => array(
        'value' => 'right-sidebar',
        'thumbnail' => get_template_directory_uri() . '/inc/images/right-sidebar.png'
    ),
    'no-sidebar' => array(
        'value'     => 'no-sidebar',
        'thumbnail' => get_template_directory_uri() . '/inc/images/no-sidebar.png'
    )
);
/* emag featured layout */
$emag_single_post_image_align_options = array(
    'full' => array(
        'value' => 'full',
        'label' => __( 'Full', 'emag' )
    ),
    'right' => array(
        'value' => 'right',
        'label' => __( 'Right ', 'emag' ),
    ),
    'left' => array(
        'value'     => 'left',
        'label' => __( 'Left', 'emag' ),
    ),
    'no-image' => array(
        'value'     => 'no-image',
        'label' => __( 'No Image', 'emag' )
    )
);

function emag_layout_options_callback() {

    global $post , $emag_default_layout_options, $emag_single_post_image_align_options;
    $emag_customizer_saved_values = emag_get_all_options(1);

    /*emag-single-post-image-align*/
    $emag_single_post_image_align = $emag_customizer_saved_values['emag-single-post-image-align'];

    /*emag default layout*/
    $emag_single_sidebar_layout = $emag_customizer_saved_values['emag-default-layout'];

    wp_nonce_field( basename( __FILE__ ), 'emag_layout_options_nonce' );
    ?>
    <table class="form-table page-meta-box">
        <!--Image alignment-->
        <tr>
            <td colspan="4"><em class="f13"><?php _e( 'Choose Sidebar Template', 'emag' ); ?></em></td>
        </tr>
        <tr>
            <td>
                <?php
                $emag_single_sidebar_layout_meta = get_post_meta( $post->ID, 'emag-default-layout', true );
                if( false != $emag_single_sidebar_layout_meta ){
                   $emag_single_sidebar_layout = $emag_single_sidebar_layout_meta;
                }
                foreach ($emag_default_layout_options as $field) {
                    ?>
                    <div class="hide-radio radio-image-wrapper" style="float:left; margin-right:30px;">
                        <input id="<?php echo esc_attr( $field['value'] ); ?>" type="radio" name="emag-default-layout"
                               value="<?php echo esc_attr( $field['value'] ); ?>"
                            <?php checked( $field['value'], $emag_single_sidebar_layout ); ?>/>
                        <label class="description" for="<?php echo esc_attr( $field['value'] ); ?>">
                            <img src="<?php echo esc_url( $field['thumbnail'] ); ?>" />
                        </label>
                    </div>
                <?php } // end foreach
                ?>
                <div class="clear"></div>
            </td>
        </tr>
        <tr>
            <td><em class="f13"><?php _e( 'You can set up the sidebar content', 'emag' ); ?> <a href="<?php echo esc_url( admin_url('/widgets.php') ); ?>"><?php _e( 'here', 'emag' ); ?></a></em></td>
        </tr>
        <!--Image alignment-->
        <tr>
            <td colspan="4"><?php _e( 'Featured Image Alignment', 'emag' ); ?></td>
        </tr>
        <tr>
            <td>
                <?php
                $emag_single_post_image_align_meta = get_post_meta( $post->ID, 'emag-single-post-image-align', true );
                if( false != $emag_single_post_image_align_meta ){
                    $emag_single_post_image_align = $emag_single_post_image_align_meta;
                }
                foreach ($emag_single_post_image_align_options as $field) {
                    ?>
                    <input id="<?php echo esc_attr( $field['value'] ); ?>" type="radio" name="emag-single-post-image-align" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $emag_single_post_image_align ); ?>/>
                    <label class="description" for="<?php echo esc_attr( $field['value'] ); ?>">
                        <?php echo esc_html( $field['label'] ); ?>
                    </label>
                <?php } // end foreach
                ?>
                <div class="clear"></div>
            </td>
        </tr>
    </table>

<?php }

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function emag_save_sidebar_layout( $post_id ) {
    global $post;
    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'emag_layout_options_nonce' ] ) || !wp_verify_nonce( $_POST[ 'emag_layout_options_nonce' ], basename( __FILE__ ) ) ) {
        return;
    }

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( !current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
    }
    
    if(isset($_POST['emag-default-layout'])){
        $old = get_post_meta( $post_id, 'emag-default-layout', true);
        $new = sanitize_text_field($_POST['emag-default-layout']);
        if ($new && $new != $old) {
            update_post_meta($post_id, 'emag-default-layout', $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id,'emag-default-layout', $old);
        }
    }

    /*image align*/
    if(isset($_POST['emag-single-post-image-align'])){
        $old = get_post_meta( $post_id, 'emag-single-post-image-align', true);
        $new = sanitize_text_field($_POST['emag-single-post-image-align']);
        if ($new && $new != $old) {
            update_post_meta($post_id, 'emag-single-post-image-align', $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id,'emag-single-post-image-align', $old);
        }
    }
}
add_action('save_post', 'emag_save_sidebar_layout');
