<?php
/*********************** %%%copyright%%% *****************************************
 *
 * FusionTicket - ticket reservation system
 * Copyright (C) 2007-2008 Christopher Jenkins. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of fusionTicket.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 3 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 *
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact info@noctem.co.uk if any conditions of this licencing isn't
 * clear to you.
 */

/**
 * Contains the dispatcher-class. Here is where it all starts.
 *
 */

require_once ( dirname(dirname(__FILE__)).'/config/defines.php' );
require_once ( INC.'classes'.DS.'basics.php' );

if (file_exists(LIBS.'FirePHPCore'.DS.'fb.php' )) {
  require_once ( LIBS.'FirePHPCore'.DS.'fb.php' );
  require_once ( LIBS.'FirePHPCore'.DS.'FirePHP.class.php' );
  FB::setEnabled(true);
}


class router {
	/**
	 * placeholer-array for all relevant variables a class may need later on (e.g. controller)
	 * [isAjax] => false (boolean, tells you if view got called with /ajax/)
	 * [url] => Array (
	 *       [url] => locations
	 *       [foo] => bar (if url read ?foo=bar)
	 * )
	 * [form] => Array (
	 * 	  (all post-variables, automatically dequoted if needed)
	 * )
	 * [controller] => main (name of the controller of this request)
	 * [action] => index (name of the view of this request)
	 * @var array
	 */

  static function draw($page, $module = 'web', $isAjax= false) {
    GLOBAL $action, $_SHOP;
    if (strpos($module,'/') === false) {
      $controller = 'shop';
    } else {
      $controller = substr($module,   strpos($module,'/')+1);
      $module     = substr($module,0, strpos($module,'/'));
    }
    if (strpos($page,'/') !== false) {
      $action = substr($page ,    strpos($page,'/')+1 );
      $page   = substr($page , 0, strpos($page,'/') );
    }
    if (isset($_REQUEST['action'])) {
      $action=$_REQUEST['action'];
    } elseif(!isset($action)){
      $action=false;
    }
    $_REQUEST['action'] = $action;
    $_GET['action']     = $action;
    $_POST['action']    = $action;
    //echo $controller,'-',$module, '-',$action;
/*
  	if ($action { 0 } == '_') {
  		throw new Exception('Controller [' . $params['controller'] . '] does not allow execution of action [' . $params['action'] . ']');
  	}
*/


    require_once ( INC.'config'.DS.'init_'.$module.'.php' );

		$classname = 'ctrl'.ucfirst($module).ucfirst($controller);
    require_once ( INC.'controller'.DS.'controller.'.$module.'.'.$controller.'.php' );
  	$c = new $classname($module, $page);
    $c->draw($page, $action, $isAjax);
  }
}
?>