<?PHP
/**
%%%copyright%%%
 *
 * FusionTicket - ticket reservation system
 *  Copyright (C) 2007-2009 Christopher Jenkins, Niels, Lou. All rights reserved.
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

if (!defined('ft_check')) {die('System intrusion ');}
require_once('classes/ShopDB.php');

class Ort Extends Model {
  protected $_idName    = 'ort_id';
  protected $_tableName = 'Ort';
  protected $_columns   = array( '#ort_id',
      '*ort_name', '*ort_address', 'ort_address1', '*ort_zip',
      '*ort_city', '*ort_country', 'ort_state', 'ort_phone',
      '#ort_fax', 'ort_image', 'ort_url', 'ort_pm');

  function load ($ort_id){
    $query="select * from Ort where ort_id=$ort_id";
    if($res=ShopDB::query_one_row($query)){

      $ort=new Ort;
      $ort->_fill($res);

      return $ort;
    }
  }

  function _fill($arr, $nocheck=true){
    if (parent::_fill($arr, $nocheck)){
      if (!$this->fillFilename('ort_image')){
        addError('ort_image','img_loading_problem');
      } else return true;
    }
    return false;
  }

  function copy (){
    If (ShopDB::begin('Copy Ort')) {
      $old_id=$this->ort_id;
      unset($this->ort_id);

      $new_id=$this->save();

      if($pms=PlaceMap::loadAll($old_id)){
        foreach($pms as $pm){
          $pm->pm_ort_id=$new_id;
          if (!$pm->copy()) {
            return self::_abort(con('Cant copy Placemap'));
          }
        }
      }
      return ShopDB::commit('Copied ort');
    }
  }

  function delete () {
    $query = "SELECT count(event_name)
              FROM Event
              Where event_ort_id="._esc($this->ort_id);
    if (!$res = ShopDB::query_one_record($query, false)) {
      if ($res[0]) {
         addWarning('in_use');
         return false;
      }
    }

    If (ShopDB::begin('Delete Ort')) {
      if($pms=PlaceMap::loadAll($this->ort_id)){
        foreach($pms as $pm){
          if (!$pm->delete()) {return false;}
        }
      }
      if (!parent::delete()) {
        return self::_abort(con('cant delete venue'));
      }
      return ShopDB::commit('Ort deleted');
    }
  }

}
?>