<?php
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
require_once("admin/AdminView.php");

class PlaceMapZoneView extends AdminView {
  function table ($pm_id, $live = false) {
      global $_SHOP;

      if ($pm = PlaceMap::load($pm_id)) {
          $mine = true;
      }

      $query = "select * from PlaceMapZone where pmz_pm_id="._esc($pm_id);
      if (!$res = ShopDB::query($query)) {
          return;
      }
      // zones
      $alt = 0;

      echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='3'>\n";
      echo "<tr><td class='admin_list_title' colspan='2' align='left'>" . con('pm_zones') . "</td>\n";
      if (!$live and $mine) {
        echo "<td colspan=1 align='right'><a class='link' href='{$_SERVER['PHP_SELF']}?action=add_pmz&pm_id=$pm_id'>
              <img src='images/add.png' border='0' alt='".con('add')."' title='".con('add')."'></a></td>";
      }
      echo "</tr>";
      while ($zone = shopDB::fetch_object($res)) {
          $zone_ident = $zone->pmz_id;

          echo "<tr class='admin_list_row_$alt'>";
          echo "<td class='admin_list_item' width=10 bgcolor='{$zone->pmz_color}'>&nbsp;</td>\n";
          echo "<td class='admin_list_item'>{$zone->pmz_name} ({$zone->pmz_short_name})</td>\n";

          echo "<td class='admin_list_item' width=60 align=right>\n";

          if ($mine) {
              echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_pmz&pm_id=$pm_id&pmz_id=$zone_ident'>
                      <img src='images/edit.gif' border='0' alt='" . con('edit') . "' title='" . con('edit') . "'></a>\n";
              if (!$live) {
                  echo "<a class='link' href='javascript:if(confirm(\"" . con('delete_item') . "\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_pmz&pm_id=$pm_id&pmz_id=$zone->pmz_id\";}'>
                         <img src='images/trash.png' border='0' alt='" . con('remove') . "' title='" . con('remove') . "'></a>\n";
              }
          } else {
            echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pmz&pm_id=$pm_id&pmz_id=$zone_ident'>
               <img src='images/view.png' border='0' alt='" . con('view') . "' title='" . con('view') . "'></a>\n";
          }

          echo'</td></tr>';
          $alt = ($alt + 1) % 2;
      }

      echo '</table>';
  }

  function form ($data, $err) {
    $data['pmz_pm_id'] =(isset($data['pmz_pm_id']))?$data['pmz_pm_id']:$_REQUEST['pm_id'];
    echo "<form action='{$_SERVER['PHP_SELF']}' method=post>/n";
    echo "<input type=hidden name=pm_id value={$data['pmz_pm_id']}>/n";
    echo "<input type=hidden name=action value=save_pmz>";
    if ($data['pmz_id']) {
      echo "<input type=hidden name=pmz_id value={$data['pmz_id']}>";
    }
    $this->form_head(con('pm_zone'));
    $this->print_field_o('pmz_id', $data, $err);
    $this->print_field_o('pmz_ident', $data);
    $this->print_field_o('pmz_pm_id', $data);
    $this->print_input('pmz_name', $data, $err, 30, 50);
    $this->print_input('pmz_short_name', $data, $err, 4, 10);
    $this->print_color('pmz_color', $data, $err);
    $this->form_foot();

    echo "</form>";
    echo "<br><center>
          <a href='{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$data['pmz_pm_id']}' class=link>" . con('place_map') . "</a>
          </center>";
  }

  function view($pmz){
    $data = (array)$pmz;

    $this->form_head(con('pm_zone'));
    $this->print_field('pmz_id', $data);
    $this->print_field('pmz_ident', $data);
    $this->print_field('pmz_pm_id', $data);
    $this->print_field('pmz_name', $data);
    $this->print_field('pmz_short_name', $data);
    $this->show_color('pmz_color', $data);

    echo "</table><br><center>
          <a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$pmz->pmz_pm_id}'>" . con('place_map') . "</a>
          </center>";
  }

  function draw (){
    global $_SHOP;

    if ($_GET['action'] == 'add_pmz' and $_GET['pm_id'] > 0) {
      $pmz = new PlaceMapZone(true);
      $this->form((Array)$pmz, null);
    } elseif ($_GET['action'] == 'edit_pmz' and $_GET['pmz_id'] > 0) {
      $pmz = PlaceMapZone::load($_GET['pmz_id']);
      $this->pmz_form((array)$pmz, null);
    } else if ($_POST['action'] == 'save_pmz' and $_POST['pmz_pm_id'] > 0) {
      if (!$pmc = PlaceMapZone::load((int)$_POST['pmz_id'])) {
         $pmc = new PlaceMapZone(true);
      }
      if (!$pmc->fillPost() || !$pmc->save()) {
        $this->form($_POST, null);
      } else {
        return true;
      }
    }elseif ($_GET['action'] == 'view_pmz' and $_GET['pmz_id'] > 0) {
      $pmz = PlaceMapZone::load($_GET['pmz_id']);
      $this->pmz_view($pmz);

    }elseif ($_GET['action'] == 'remove_pmz' and $_GET['pmz_id'] > 0) {
      PlaceMapZone::delete($_GET['pmz_id']);
      return true;
    }
  }

  function show_color ($name, &$data)
  {
    if ($data[$name]) {
        $st = "style=background-color:{$data[$name]};";
    } else {
        $field = con('no_color');
    }
    echo "<tr><td class='admin_name' width='40%'>" . con($name) . "</td>
  <td class='admin_value'>
  <table width='40' $st><tr><td width='40'>$field&nbsp;</td></tr></table>
  </td></tr>\n";
  }
}

?>