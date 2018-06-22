<?php 
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !function_exists('chym_logo') ) {
    /**
     * Create logo 
     * @more {@link: //}
     * @since 1.0
     * @author Chym Con
     */
    function chym_logo( $att = array() )
    {
        if ( ! is_singular() ) {
            $h = 'h3';
        } else {
            $h = 'h1';
        }
        if ( is_front_page() || is_home() ) {
            $meta = get_post_meta( $att['post_id'], $att['key_name'], true );
            if ( !empty( $meta['meta_seo_title'] ) ) {
                $title = esc_attr( $meta['meta_seo_title'] );
            } else {
                $title = get_bloginfo( 'name' );
            }
        } else {
            $title = get_bloginfo( 'name' );
        }
        $out = '<'.$h.' id="chym-logo"><a href="'.esc_url( get_bloginfo('url') ).'" title="'.$title.'">'.$title.'</a></'.$h.'>';
        return $out;
    }
}