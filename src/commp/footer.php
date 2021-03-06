<?php 
if ( !defined('ABSPATH') ) {
    exit;
}
add_action( 'chym_footer', function() {
    global $mobile;
    /**
     * Create Footer Template
     * 
     * @import_commp /commp/global/footer-credit
     * 
     * @since 1.0
     * @author chym con
     */
    
    import_commp( 'footer', 'global' );
    $out  = '<footer class="chym-footer">';
    if ( $mobile->isMobile() ) {
        $out .= Chym_navbar_dropdown_m();
    }
    $out .= Chym_footer_ht( [
        'title' => 'Tổng Đài Hổ Trợ',
        'phone' => [
            'key-1' => [
                'phone' => '0987 098 007',
                'name' => 'Số Điện Thoại Mua Hàng',
            ],
            'key-2' => [
                'phone' => '0945 141 318',
                'name' => 'Giao Hàng Và Bảo Hành',
            ]
        ]
    ] );
    if ( $mobile->isMobile() ) {
        $out .= Chym_footer_info_m();
    }
    $out .= Chym_footer_mxh( [
        'face' => '#',
        'googleplus' => '#',
        'zalo' => '#',
        'youtube' => '#',
    ] );
    $out .= '</footer>';
    __render( $out );
});