<?php
if ( !defined('ABSPATH') ) {
    exit;
}
//import field
require_once ( N_EXTEND_FOLDER .'/src/inc/class.field.php' );

/**
 * Custom Meta Box 
 * @link {https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/}
 * @since 1.0
 * @author Trangfox
 */
if ( !class_exists('Content_metabox') ) {
    
    class Content_metabox
    {
        protected $fields = NULL;
        
        public function __construct()
        {
            add_action( 'add_meta_boxes',        array( $this, 'metabox_add'  ) );
            add_action( 'save_post',             array( $this, 'metabox_save'  ) );
            add_action( 'admin_footer',          array( $this, 'print_script' ) );

            $this->fields = new Chym_field;

        }
        public function metabox_add()
        {
            /**
             * add_meta_boxes
             * @link {https://developer.wordpress.org/reference/functions/add_meta_box/}
             * @since 1.0
             * @author Trangfox
             */
            add_meta_box(
                '_chym_meta_product',
                __( 'Thuộc Tính' ),
                array( $this, 'mb_callback' ),
                'product',
                'normal', 
                'default'
            );
            add_meta_box(
                '_chym_meta_seo',
                __( 'Thuộc Tính SEO' ),
                array( $this, 'mb_callbackseo' ),
                array( 'product', 'page' ),
                'normal', 
                'default'
            );
        }
        public function mb_callbackseo( $post ) 
        {
            wp_nonce_field( 'car_nonce_action', 'car_nonce' );
            
            $this->fields->Metabox_field( array(
                'keyname' => '_chym_meta_seo',
                'post_id' => $post->ID,
                'content' => array(
                    'id_1' => [
                        'title' => 'Title Seo',
                        'Desc'  => 'Thêm Tiêu Đề Dành Cho Content',
                        'value' => 'meta_seo_title',
                    ],
                ),
            ) );
            $this->fields->Metabox_field( array(
                'type' => 'textarea',
                'keyname' => '_chym_meta_seo',
                'post_id' => $post->ID,
                'content' => array(
                    'id_1' => [
                        'title' => 'Mô Tả SEO',
                        'desc' => 'Mô tả ngắn là một đoạn mô tả về nội dung mà bạn tự nhập bằng tay, có thể được sử dụng để hiển thị trong giao diện của bạn. Xem thêm về mô tả ngắn.',
                        'value' => 'meta_seo_desc',
                    ],
                ),
            ) );

        }
        public function mb_callback( $post )
        {
            wp_nonce_field( 'car_nonce_action', 'car_nonce' );
            
            $this->fields->Metabox_field( array(
                'keyname' => '_chym_meta_product',
                'post_id' => $post->ID,
                'content' => array(
                    'id_1' => [ 
                                'title' => 'Ảnh Đại Diện sản Phẩm', 
                                'value' => 'meta_thumbnail',
                    ],
                    'id_4' => [
                                'title' => 'Thư Viên Ảnh Sản Phẩm',
                                'value' => 'meta_gallery',
                                'type'  => 'multi',
                    ],
                ),
            ) );
        }
        public function metabox_save( $post_id )
        {
            // Save logic goes here. Don't forget to include nonce checks!
            $nonce_name   =  isset( $_POST['car_nonce'] ) ? $_POST['car_nonce'] : '';
        
            $nonce_action = 'car_nonce_action';
            // Check if a nonce is set.
            if ( ! isset( $nonce_name ) )
               return;
        
            // Check if a nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
                return $post_id;

            // check autosave
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
                return $post_id;

            // check permissions
            if ( 'page' == $_POST['post_type'] ) {

                if (!current_user_can('edit_page', $post_id)) 

                    return $post_id;

            } elseif (! current_user_can('edit_post', $post_id) ) {

                return $post_id;

            }
            // loop through fields and save the data
            
                $new_Thumbnail = $_POST['_chym_meta_product'];
                $new_Seo       = $_POST['_chym_meta_seo'];

                update_post_meta( $post_id, '_chym_meta_product', $new_Thumbnail );
                update_post_meta( $post_id, '_chym_meta_seo', $new_Seo );

        }
        public function print_script()
        {
            $screen = get_current_screen(); // This is how we will check what page we are on
            #if ( in_array( $screen->id, array( 'post', 'page' ) ) ) {
                ?>
                    <style>
                        .button_plus {
                            background: #e02222;
                            padding: 0px 5px 3px;
                            color: #fff;
                            border-radius: 5px;
                            font-size: 15px;
                            display: inline-flex;
                            font-weight: 700;
                            cursor: pointer;
                            margin-left: 5px;
                            vertical-align: -3px;
                            line-height: 15px;
                        }
                        .address input {
                            width: 90%;
                            margin-bottom: 5px;
                        }
                    </style>
                    <script> 
                        jQuery(document).ready(function ($) {
                            $(document).ready(function(){
                                $("#add-address").click(function(e){
                                    e.preventDefault();
                                    var numberOfAddresses = $(".postbox").find("input[name^='_meta_thumbnail[meta_download]']").length;
                                    var input = '<input type="text" name="_meta_thumbnail[meta_download][' + numberOfAddresses + ']" />';
                                    var removeButton = '<span class="remove-address button_plus">-</span>';
                                    var html = "<div class='address'>" + input + removeButton + "</div>";
                                    $(".postbox").find(".meta_label").after(html);
                                });
                            });

                            $(document).on("click", ".remove-address",function(e){
                                e.preventDefault();
                                $(this).parents(".address").remove();
                                //update labels
                                $(".postbox").find("label[for^='_meta_thumbnail']").each(function(){
                                    //$(this).html("Ảnh Đại Diện " + ($(this).parents('.address').index() + 1));
                                });
                            });
                        });
                    </script>
                <?php 
            #}
        }
    }
}
return new Content_metabox;