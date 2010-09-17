<?php
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2010 Christopher Jenkins, Niels, Lou. All rights reserved.
 *
 * Original Design:
 *	phpMyTicket - ticket reservation system
 * 	Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * This file is part of FusionTicket.
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
 * Any links or references to Fusion Ticket must be left in under our licensing agreement.
 *
 * By USING this file you are agreeing to the above terms of use. REMOVING this licence does NOT
 * remove your obligation to the terms of use.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * Contact help@fusionticket.com if any conditions of this licencing isn't
 * clear to you.
 */



require_once(CLASSES."jsonwrapper.php"); // Call the real php encoder built into 5.2+

class ctrlWebJson  {
  protected $smarty ;
  protected $HelperList = array();
  protected $context = '';

  public function __construct($context='web') {
    require_once (INC. 'config'.DS.'init.php' );
  }

  public function draw($page, $action) {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
  		$this->request    = $_REQUEST;
  		$this->actionName = $action;
      $this->action = 'do'.ucfirst($action);
      $result = $this->callAction();
  		if(!$result){
  		    $object = array("status" => false, "reason" => 'Missing action request');
  		    echo json_encode($object);
  		}
    }else{
    	header("Status: 400");
    	echo "This is for AJAX / AJAJ / AJAH requests only, please go else where.";
    }

    orphanCheck();
    trace("End of ajax req \n");
	}

  public function callAction(){
    if(is_callable(array($this,$this->action))){
		  $this->json = am($this->json,array("status" =>true, "reason" => 'success'));
      //Instead of falling over in a heap at least return an error.
      try{
        $return = call_user_func(array($this,$this->action));
      }catch(Exception $e){
        return false;
      }
      if($return){
    		echo json_encode($this->json);
			}
			return true;
		}
		return false;
	}

  public function doDiscountpromo() {
    $discount = Discount::load($this->request['id']);
    if (!empty($discount->discount_promo)) {
      $promo = $this->request[$this->request['name']];
      $this->json = (strtoupper($promo) == strtoupper($discount->discount_promo));
    } else {
      $this->json = false;
    }
    return true;
  }
}
?>