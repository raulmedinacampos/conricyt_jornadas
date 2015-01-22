<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class pdf {
    
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($mode="en-GB-x", $format="A4", $font_size="", $font="", $ml=10, $mr=10, $mt=10, $mb=10, $mh=6, $mf=3, $orientation="")
    {
        include_once APPPATH.'/third_party/mpdf/mpdf.php';
         
        return new mPDF($mode, $format, $font_size, $font, $ml, $mr, $mt, $mb, $mh, $mf, $orientation);
    }
}
?>