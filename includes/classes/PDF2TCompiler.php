<?PHP
/*
%%%copyright%%%
 * phpMyTicket - ticket reservation system
 * Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of phpMyTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * Licencees holding a valid "phpmyticket professional licence" version 1
 * may use this file in accordance with the "phpmyticket professional licence"
 * version 1 Agreement provided with the Software.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 * The "phpmyticket professional licence" version 1 is available at
 * http://www.phpmyticket.com/ and in the file
 * PROFESSIONAL_LICENCE included in the packaging of this file.
 * For pricing of this licence please contact us via e-mail to 
 * info@phpmyticket.com.
 * Further contact information is available at http://www.phpmyticket.com/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@phpmyticket.com if any conditions of this licencing isn't 
 * clear to you.
 
 */


class PDF2TCompiler {
  var $res_stat ="";
  var $res_dyna ="";
  var $mode=0; //1 static 2 dynamic 0 none 3 ezText 
  var $stack=array(); // local stack
  var $vars=array();
  var $args='data';
  var $errors;
  
  function PDF2TCompiler ($font_dir=''){
    global $_SHOP;
    if($font_dir){
      $this->font_dir=$font_dir;
    }else{
      $this->font_dir=$_SHOP->font_dir;
    }
    //$this->template_dir=$_SHOP->template_dir;
    
    $this->template_dir=$_SHOP->tpl_dir;
  }

  function compile ($input, $out_class_name){

$ret=
'
/*this is a generated file. do not edit!

produced '.date("l dS of F Y h:i:s A").' 


*/
require_once("smarty/Smarty.class.php");
require_once("html2pdf/html2pdf.class.php");


class '.$out_class_name.' {

  var $object_id;
  var $engine;

  function write($pdf, $data){
    global $_SHOP;

    $input = '."'" .addslashes($input)."'".';

    $smarty = new Smarty;
    $smarty->plugins_dir  = array("plugins", $_SHOP->includes_dir . "shop_plugins");
    $smarty->cache_dir    = $_SHOP->tmp_dir;
    $smarty->compile_dir  = $_SHOP->tmp_dir;
    $smarty->compile_id   = "HTML2PDF";

    $smarty->assign("_SHOP_lang", $_SHOP->lang);
    $smarty->assign("organizer_currency", $_SHOP->organizer_data->organizer_currency);
    $smarty->assign("organizer", $_SHOP->organizer_data);
    $smarty->assign($data);
    $smarty->assign("OrderData",$data);

    $smarty->my_template_source = stripslashes($input);
    $input = $smarty->fetch("text:".'.$out_class_name.');
    $pdf->WriteHTML($input, false);
    unset($smarty);
  }
}
';
    //  echo "<pre>$ret</pre>";
      return $ret;
  }
}
?>