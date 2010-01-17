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

if (!defined('ft_check')) {die('System intrusion ');}
require_once("admin/class.adminview.php");

class PlaceMapPartView extends AdminView {
  function table ($pm_id, $live = false){
      global $_SHOP;

      if ($pm = PlaceMap::load($pm_id)) {
          $mine = true;
      }

      $alt = 0;
      echo "<table class='admin_list' width='$this->width' cellspacing='1' cellpadding='2'>\n";
      echo "<tr><td class='admin_list_title' colspan='3' align='left'>" . con('pm_parts') . "</td>";
      if ($mine and !$live) {
        echo "<td colspan=1 align=right>".$this->show_button("{$_SERVER['PHP_SELF']}?action=add_pmp&pm_id=$pm_id","add",3)."</td>";
      }
      echo "</tr>\n";
      $query = "select * from PlaceMapPart where pmp_pm_id="._esc($pm_id);
      if (!$res = ShopDB::query($query)) {
          return;
      } while ($pmp = shopDB::fetch_assoc($res)) {
          echo "<tr class='admin_list_row_$alt'>";
          echo "<td class='admin_list_item' width=10>&nbsp;</td>\n";
          echo "<td class='admin_list_item' title='{$pmp['pmp_id']}' width='50%'>{$pmp['pmp_name']}</td>\n";
          echo "<td class='admin_list_item'>{$pmp['pmp_width']} &times; {$pmp['pmp_height']} (".$pmp['pmp_width'] * $pmp['pmp_height'].")</td>\n";

          echo "<td class='admin_list_item' width=65 align=right>\n";

          if ($mine) {
            echo $this->show_button("{$_SERVER['PHP_SELF']}?action=edit_pmp&pm_id=$pm_id&pmp_id={$pmp['pmp_id']}","edit",2);
          } else {
            echo $this->show_button("{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id={$pmp['pmp_id']}","view",2, array('image'=>'view.png'));
          }
          echo $this->show_button("{$_SERVER['PHP_SELF']}?action=split_pmp&pm_id={$pmp['pmp_pm_id']}&pmp_id={$pmp['pmp_id']}","split_pm",2,
                                  array('image'=>'copy_to_folder16.gif',
                                        'disable'=>(!$mine)));
          echo $this->show_button("javascript:if(confirm(\"".con('delete_item')."\")){location.href=\"{$_SERVER['PHP_SELF']}?action=remove_pmp&pmp_id={$pmp['pmp_id']}&pm_id=$pm_id\";}","remove",2,
                                  array('tooltiptext'=>"Delete {$row['ort_name']}?",
                                        'disable'=>($live and $mine)));
          echo'</td></tr>';
          $alt = ($alt + 1) % 2;
      }

      echo '</table>';
  }

  function form ($data, $err, $view_only = false) {
    $data['pm_id'] =(isset($data['pm_id']))?$data['pm_id']:$_REQUEST['pm_id'];
    $data['pm_ort_id']=(isset($data['pm_ort_id']))?$data['pm_ort_id']:$_REQUEST['pm_ort_id'];
    echo "<form action='{$_SERVER['PHP_SELF']}' method=post>\n";
    echo "<input type=hidden name=action value=save_pmp>\n";
    echo "<input type=hidden name=pm_id value={$data['pm_id']}>\n";
    echo "<input type=hidden name=pm_ort_id value={$data['pm_ort_id']}>\n";
    if ($data['pmp_id']) {
      echo "<input type=hidden name=pmp_id value={$data['pmp_id']}>\n";
    } else {
      $this->form_head(con('pm_part'));
    }
//        $this->print_field_o('pmp_id', $data, $err);
    $this->print_input('pmp_name', $data, $err, 30, 50);

    if (!$data['pmp_id']) {
      $this->print_input('pmp_width', $data, $err, 4, 4);
      $this->print_input('pmp_height', $data, $err, 4, 4);
    }

    $this->print_select('pmp_scene', $data, $err, array('none','north', 'east', 'south', 'west'));
    $this->print_checkbox('pmp_shift', $data, $err, 30, 50);
    $this->form_foot(2,"{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$data['pm_id']}");
  }

  function view($pmp_id, $err = null, $sel_cat = 0, $sel_pmz = 0, $view_only = false) {
      global $_SHOP;
      $pmp = PlaceMapPart::loadFull($pmp_id);
      if ($pmp->event_id and $pmp->event_status != 'unpub') {
        $view_only = true;
      }

      $stats = $pmp->getStats();
      // infos
      echo "<table class='admin_form' width='100%' border=0 cellspacing='1' cellpadding='4'>
            <tr><td class='admin_list_title' colspan='2'>	{$pmp->pm_id} {$pmp->pm_name} {$pmp->ort_name}";
      if (!$view_only) {
          echo " <a class='link' href='{$_SERVER['PHP_SELF']}?action=view_only_pmp&pmp_id={$pmp->pmp_id}'>
                   <img src='../images/view.png' border='0' alt='" . con('view') . "' title='" . con('view') . "'></a>";
      }
      echo "<br>
            {$pmp->event_name} {$pmp->event_date} {$pmp->event_time}</td></tr>";

      if (!$view_only) {
          $data =  (hasErrors())?$_POST:(array)$pmp;
          $this->form ($data, $err, $view_only);
        ///  echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=edit_pmp&pmp_id={$pmp->pmp_id}'><img src='../images/edit.gif' border='0' alt='" . edit . "' title='" . edit . "'></a>";
          echo "<form name='thisform' method='post' action='{$_SERVER['PHP_SELF']}'>";
      }
      echo '<table border=0 cellpadding=0 cellspacing=0 width="100%">
             <tr><td align="center" height=3 class="admin_value" colspan="2"> </td></tr>
             <tr><td width="50%" align=left valign=top>';

      // zones
      $alt = 0;
      $this->list_head(con('pm_zones'), 5, '99%');

      if (!empty($pmp->zones)) {
          foreach($pmp->zones as $zone_ident => $zone) {
              if ($stats->zones[$zone_ident]) {
                  echo "<tr class='admin_list_row_$alt'>";
                  echo "<td class='admin_list_item' width=10 bgcolor='{$zone->pmz_color}'>&nbsp;</td>\n";
                  echo "<td class='admin_list_item'>{$zone->pmz_ident}</td>\n";
                  echo "<td class='admin_list_item'>{$zone->pmz_name} ({$zone->pmz_short_name})</td>\n";
                  echo "<td class='admin_list_item' align='right'>{$stats->zones[$zone_ident]}</td>\n";

                  if (!$view_only) {
                      echo "<td class='admin_list_item' valign=middle width=35 align=right><a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id=$pmp_id&pmz_ident=$zone_ident'><img height=15 src='../images/checkbox-checked.gif' border='0' alt='" . view . "' title='" . view . "'></a>\n";
                      echo "<a class='link' href='{$_SERVER['PHP_SELF']}?action=pmz_edit_num_pmp&pmp_id=$pmp_id&pmz_ident=$zone_ident'><img height=15 src='../images/numbers.png' border='0' alt='" . edit . "' title='" . edit . "'></a></td>\n";
//                  } else {
//                      echo "<td>&nbsp;</td>";
                  }

                  echo'</tr>';
                  $alt = ($alt + 1) % 2;
              }
          }
      }
      echo '</table>';

      echo '</td><td align=right valign=top>';

      $this->list_head(con('categories'), 6, '99%');
      $alt = 0;
      if (!empty($pmp->categories)) {
          foreach($pmp->categories as $ident => $category) {
              if ($stats->categories[$ident]) {
                  $category->category_color = placemapCategory::resetColor($category->category_color);
                  echo "<tr class='admin_list_row_$alt'>";
                  echo "<td class='admin_list_item' width=10 bgcolor='{$category->category_color}'>&nbsp;</td>\n";
                  echo "<td class='admin_list_item' nobreak=nobreak>{$category->category_name}</td>\n";//
//                    echo "<td class='admin_list_item'>{$category->category_price} {$_SHOP->currency}</td>\n";
                  echo "<td class='admin_list_item' align='right'>{$stats->categories[$ident]}</td>\n";
                  echo "<td class='admin_list_item'>{$category->category_numbering}</td>\n";
                  if (!$view_only) {
                      echo "<td class='admin_list_item' width=18 align=right><a class='link' href='{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id=$pmp_id&category_id={$category->category_ident}'><img height=15 src='../images/checkbox-checked.gif' border='0' alt='" . view . "' title='" . view . "'></a></td>\n";
//                  } else {
//                      echo "<td></td>\n";
                  }
                  echo'</tr>';
                  $alt = ($alt + 1) % 2;
              }
          }
      }
      echo "</table>";
      echo '</td></tr></table>';

      if (!$view_only) {
          echo '<table border=0 cellpadding=0 cellspacing=0 width="100%">
            <tr><td width="50%" valign="top" align="left">';
          // define category
          $this->form_head('', '99%');
          echo "<tr> <td class='admin_value'>
        <select name='zone_id' style='width:200'>\n";

          $sel[$sel_pmz] = 'selected';
          if ($pmp->zones) {
              foreach($pmp->zones as $zone_id => $zone) {
                  echo "<option value='{$zone_id}' {$sel[$zone_id]}>{$zone->pmz_name}</option>\n";
              }
          }

          echo "</select>
        </td>
        <td align='right' class='admin_value'>

        <button name='def_pmz_pmp' value='" . con('define') . "'  onClick='this.form.action.value=\"def_pmz_pmp\";this.form.submit();'>" . con('define') . "</button>
    	  </td></tr></table>";

          echo '</td><td align=right valign=top > ';
          // define zone
          $this->form_head('', '99%');

          echo "<tr><td class='admin_value'>
      <select name='category_id' style='width:200'>\n";

          $sel[$sel_cat] = 'selected';
          if ($pmp->categories) {
              foreach($pmp->categories as $cat_id => $category) {
                  echo "<option value='{$cat_id}' {$sel[$cat_id]}>{$category->category_name}</option>\n";
              }
          }

          echo "</select>
        </td>
        <td align='right' class='admin_value'>
          <button name='def_cat_pmp' value='" . con('define') . "'  onClick='this.form.action.value=\"def_cat_pmp\";this.form.submit();'>" . con('define') . "</button>
	      </td></tr></table>";


          echo "</td></tr><table>
           <table border=0 cellpadding=0 cellspacing=0 width='100%'>
           <tr><td align='center' height=3 class='admin_value' colspan='2'> </td></tr>
          <tr>
          <td  align=left width='85%' valign=top colspan=3>";
          // define labels
          $this->form_head('', '99%');
          echo "<tr><td class='admin_value'>".con("label")."
                  <select name='label_type'>\n";
          echo "  <option value='T'  {$sel['T' ]}>".con('label_type_text')."</option>\n";
          echo "  <option value='RE' {$sel['RE']}>".con('label_type_row_east')."</option>\n";
          echo "  <option value='RW' {$sel['RW']}>".con('label_type_row_west')."</option>\n";
          echo "  <option value='SS' {$sel['SS']}>".con('label_type_seat_south')."</option>\n";
          echo "  <option value='SN' {$sel['SN']}>".con('label_type_seat_north')."</option>\n";
          echo "  <option value='E'  {$sel['E' ]}>".con('label_type_exit')."</option>\n";
          echo "</select> </td>\n";
          echo "<td class='admin_value'>
                    <input type='text' name='label_text' value='' size='20' maxlength='100'>
                    <span class='err'>{$err['label_text']}</span>
                  </td>";

          echo "<td align='right' class='admin_value'>
                <button name='def_label_pmp' value='" . con('define') . "'  onClick='this.form.action.value=\"def_label_pmp\";this.form.submit();'>" . con('define') . "</button>
    	      </td></tr></table>";

          echo '</td><td align=right valign=top>';
          // clear
          $this->form_head('', '99%');
          echo "<tr><td align='right' class='admin_value'>

        <button name='def_clear_pmp' value='" . con('clear') . "'  onClick='this.form.action.value=\"def_clear_pmp\";this.form.submit();'>" . con('clear') . "</button>
    	  </td></tr></table>";

          echo "</td></tr>
                <tr><td align='center' height=3 class='admin_value' colspan='2'> </td></tr>
                </table>";

          echo "<input type='hidden' name='action' value='coucou'>
        <input type='hidden' name='pmp_id' value='$pmp_id'>";
      }


//        echo '<br>';
      // map
      if (!$view_only) {
          echo
          "
  <script><!--
  function cc(col,state){
    form=window.document.thisform;
    for(r=0;r<{$pmp->pmp_height};r++){
      if(chk=form['seat['+r+']['+col+']']){
        chk.checked=state;
      }
    }
  }
  function rr(row,state){
    form=window.document.thisform;
    for(c=0;c<{$pmp->pmp_width};c++){
      if(chk=form['seat['+row+']['+c+']']){
        chk.checked=state;
      }
    }
  }
  --></script>
  ";
      }

      $zones = $pmp->zones;

      echo "<table class='admin_form' border=0 width='$this->width' cellspacing='0' cellpadding='0'>
      ";
      echo "<tr><td align=center>
                 <div style='overflow: auto; height: 350px; width:{$this->width}px;' align='center' valign='center'>
                 ";

      switch ($pmp->pmp_scene) {
          case 'north':$scene_n = '<img src="' .con('scene_h') . '">';
              break;
          case 'south':$scene_s = '<img src="' . con('scene_h') . '">';
              break;
          case 'west':$scene_w = '<img src="' . con('scene_v') . '">';
              break;
          case 'east':$scene_e = '<img src="' . con('scene_v') . '">';
              break;
      }

      echo "<table cellspacing=0 cellpadding=0 border=0><tr><td colspan=3 align=center>$scene_n</td></tr>
          <tr><td valign=middle>$scene_w</td><td>";

      if ($view_only) {
          echo "<table cellspacing=1 cellpadding=1>";
      } else {
          echo "<table cellspacing=0 cellpadding=0>";
      }

      if ($pmp->pmp_shift) {
          $cspan = 'colspan=2';
          $ml[1] = $mr[0] = '<td class="pm_none"><img src="images/dot.gif" width=5 height=1></td>';
          echo '<tr>';
          $width2 = ($pmp->pmp_width) * 2 + 1;
          for($k = 0;$k <= $width2;$k++) {
              echo '<td><img src="images/dot.gif" width=5 height=1></td>';
          }
          echo '<td></td></tr>';
      }

      for($j = 0;$j < $pmp->pmp_height;$j++) {
          echo "<tr>";
          echo $ml[$j % 2];

          for($k = 0;$k < $pmp->pmp_width;$k++) {
              $col = '';
              $chk = '';
              $sty = '';
              if ($z = $pmp->data[$j][$k][PM_ZONE]) {
                  if ($z == 'L') {
                      $sty = "border: 2px dashed #666666;background-color:#dddddd;";
                      $label = $pmp->data[$j][$k];
                      if ($view_only) {
                          if ($label[PM_LABEL_TYPE] == 'T' and $label[PM_LABEL_SIZE] > 0) {
                              $colspan = $label[PM_LABEL_SIZE];
                              if ($cspan) {
                                  $colspan *= 2;
                              }
                              echo "<td align=center style='$sty' colspan=$colspan>" . $label[PM_LABEL_TEXT] . "</td>";
                          } else if ($label[PM_LABEL_TYPE] == 'T' and !$label[PM_LABEL_SIZE]) {
                              continue;
                          } else {
                              echo "<td align=center style='$sty' $cspan>{$label[PM_LABEL_TYPE]}</td>";
                          }
                      } else {
                          echo "<td align=center style='$sty' $cspan><input type='checkbox' name='seat[$j][$k]' value=1 title=\"{$label[PM_LABEL_TYPE]} {$label[PM_LABEL_SIZE]} {$label[PM_LABEL_TEXT]}\"  style='border:0px;'></td>";
                      }
                      continue;
                  }

                  $zone = $zones[$z];

                  $col = "bgcolor='{$zone->pmz_color}'";

                  $cat_id = $pmp->data[$j][$k][PM_CATEGORY];
                  $category = $pmp->categories[$cat_id];

                //  if ($cat_id) {
                      $sty = "border-top:2px solid";
                      if ($pmp->data[$j - 1][$k][PM_CATEGORY] != $cat_id) {
                          $sty .= " {$pmp->categories[$cat_id]->category_color}";
                      } else $sty .= " {$zone->pmz_color}";
                      $sty .= ";border-bottom:2px solid";
                      if ($pmp->data[$j + 1][$k][PM_CATEGORY] != $cat_id) {
                          $sty .= " {$pmp->categories[$cat_id]->category_color}";
                      } else $sty .= " {$zone->pmz_color}";
                      $sty .= ";border-left:2px solid";
                      if ($pmp->data[$j][$k - 1][PM_CATEGORY] != $cat_id) {
                          $sty .= " {$pmp->categories[$cat_id]->category_color}";
                      } else $sty .= " {$zone->pmz_color}";
                      $sty .= ";border-right:2px solid";
                      if ($pmp->data[$j][$k + 1][PM_CATEGORY] != $cat_id) {
                          $sty .= " {$pmp->categories[$cat_id]->category_color}";
                      } else $sty .= " {$zone->pmz_color}";

                      $sty = "style='$sty; nowrap'";
                  //}

                  if (($cat_id and $sel_cat == $cat_id) or ($z and $sel_pmz == $z)) {
                      $chk = 'checked';
                  }

                  if ($view_only) {
                      $row = $pmp->data[$j][$k][PM_ROW];
                      $seat = $pmp->data[$j][$k][PM_SEAT];

                      if ($row == ($pmp->data[$j][$k - 1][PM_ROW])) {
                          $row = '&nbsp;';
                      }
                      if ($seat == ($pmp->data[$j - 1][$k][PM_SEAT])) {
                          $seat = '&nbsp;';
                      }
                      if ($row or $seat) {
                          $num = "$row-$seat";
                      } else {
                          $num = "&nbsp;";
                      }

                      echo "<td align=center $col $sty $cspan>$num</td>";
                  } else {
                      echo "<td align=center $col $sty $cspan>
                              <input type='checkbox' name='seat[$j][$k]' value=1 $chk title=\"{$zone->pmz_name} {$pmp->data[$j][$k][PM_ROW]}/{$pmp->data[$j][$k][PM_SEAT]} {$category->category_name}\"  style='border:0px;background-color:{$zone->pmz_color}'></td>"; //background-color:{$zone->pmz_color}
                  }
              } else {
                $sty = "border: 2px solid #ffffff;";
                if ($view_only) {
                      echo "<td style='$sty'  $cspan></td>";
                  } else {
                      echo "<td style='$sty' $cspan><input type='checkbox' name='seat[$j][$k]' value=1  style='border:0px;'></td>";
                  }
              }
          }

          echo $mr[$j % 2];

          if ($view_only) {
              echo "<td></td></tr>\n";
          } else {
              echo "<td style='border-left:1px solid #666666'><input type='checkbox' onclick='rr($j,checked)' style='border:0px;'></td></tr>\n";
          }
      }

      if (!$view_only) {
          echo "<tr>";
          echo $ml[$j % 2];
          for($x = 0;$x < $pmp->pmp_width;$x++) {
              echo "<td style='border-top:1px solid #666666' $cspan><input type='checkbox' onclick='cc($x,checked)' style='border:0px;'></td>";
          }
          echo $mr[$j % 2];
          echo "<td></td></tr>";
      }

      echo "</table>";
      echo "</td><td valign=middle>$scene_e</td></tr>
            <tr><td align=center colspan=3>$scene_s</td></tr></table>";

      echo "</div></td></tr></table>";

      // if($pmp->event_id){
      // echo "<a class='link' href='view_event.php?action=view&event_id={$pmp->event_id}'>".event."</a>";
      // }else{
      if (!$view_only) {
        echo "<br>".$this->show_button("{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$pmp->pm_id}",'admin_list',3);
      } else {
        echo "<br>".$this->show_button("{$_SERVER['PHP_SELF']}?action=edit_pmp&pmp_id={$pmp->pmp_id}",'admin_list',3);
      }
      $this->pmpnamesList($pmp->pm_id, $pmp->pmp_id, $view_only);
  }

	function split_form( $pm_id, $pmp_id ) {
		global $_SHOP;
		if ( !$pm = PlaceMap::load($pm_id) ) {
			return;
		}
		if ( !$pm_parts = PlaceMapPart::loadAll($pm_id) ) {
			return;
		}

		echo "<form action='{$_SERVER['PHP_SELF']}' method=POST>";
		echo "<input type='hidden' name='action' value='split_pmp'>
          <input type='hidden' name='pm_id' value='$pm_id'>";
		$this->form_head( con('pm_split') );
		if ( !$pmp_id ) {
			echo "<tr><td class='admin_name'  width='40%' >" . con('split_pm') . "</td>
                 <td class='admin_value'>
                 <select name='pm_parts[]' multiple>\n";
			foreach ( $pm_parts as $pmp ) {
				echo "<option value='{$pmp->pmp_id}'>{$pmp->pmp_name}</option>\n";
			}
	   	echo "</select></td></tr>\n";
		} else {
			echo "<input type='hidden' name='pm_parts[]' value='$pmp_id'>";
		}

		$this->print_checkbox( 'split_zones', $data, $err );
		$this->form_foot(2,"{$_SERVER['PHP_SELF']}?action=edit_pm&pm_id={$pm_id}");

	}

  function zone_edit ($zone_ident, $pmp_id) {
    global $_SHOP;

    if (!$pmp = PlaceMapPart::loadFull($pmp_id)) {
        return;
    }

    $doubles = $pmp->find_doubles($zone_ident);

    $this_zone = $pmp->zones[$zone_ident];
    // title
    echo "<table class='admin_form' width='$this->width' cellspacing='1' cellpadding='4'>
        <tr><td class='admin_list_title' colspan='2'>
    	Zone: {$this_zone->pmz_name} ($this_zone->pmz_short_name)
    	<div class='admin_list_title'style='font-size:smaller;'>
    	{$pmp->pmp_name} ({$pmp->pmp_width}x{$pmp->pmp_height}) -
    	{$pmp->pm_name} -
    	{$pmp->ort_name} - {$pmp->event_name} - {$pmp->event_date}
    	</div>
    	</td></tr></table><br>";
        // numbering
    echo "<form action='{$_SERVER['PHP_SELF']}' method=POST name=thisform>
            <input type=hidden name=action value='pmz_save_num_pmp'>
          	<input type='hidden' name='pmp_id' value='$pmp_id'>
         	<input type='hidden' name='pmz_ident' value='$zone_ident'> ";
    // <input type='hidden' name='pm_ort_id' value='{$pm['pm_ort_id']}'>";
    $this->list_head(con('seat_numbering'), 1);
    // echo "<table class='admin_list' width='$this->width' cellspacing='0' cellpadding='4'>\n";
    // echo "<tr><td class='admin_list_title'  align='center'>".seat_numbering."</td></tr>\n";
    echo "<tr><td align=center><table>";

    $zone_bounds = $pmp->zone_bounds($zone_ident);

    for($j = $zone_bounds['top'];$j <= $zone_bounds['bottom'];$j++) {
        echo "<tr>";;
        for($k = $zone_bounds['left'];$k <= $zone_bounds['right'];$k++) {
            $seat = $pmp->data[$j][$k];

            if ($z = $seat[PM_ZONE]) {
                $zone = $zones[$z];
                $col = "bgcolor='{$zone->pmz_color}'";
            } else {
                $col = '';
            }

            echo "<td  $col>";
            if ($seat[PM_ZONE] == $zone_ident) {
                if ($doubles[$j][$k]) {
                    echo "<input type='text' name='seat[$j][$k]' value='{$seat[PM_ROW]}/{$seat[PM_SEAT]}' size='4' style='font-size:8px;color:#cc0000;'>";
                } else {
                    echo "<input type='text' name='seat[$j][$k]' value='{$seat[PM_ROW]}/{$seat[PM_SEAT]}' size='4' style='font-size:8px;'>";
                }
            } else {
                echo "&nbsp;";
            }
            echo "</td>\n";
        }
        echo "</tr>\n";
    }

    echo "</td></tr></table>";
    $this->form_foot(1);
    // echo "<tr><td align='center' class='admin_value' colspan='2'>
    // <input type=hidden name=action value='set_zone_num'>
    // <input type='submit' name='save' value='".save."'>
    // </tr></table><br>";
    echo "</form>";
    // auto numbering
    echo "<form action='{$_SERVER['PHP_SELF']}' method=POST>
        <input type='hidden' name='pmp_id' value='$pmp_id'>
         <input type='hidden' name='pmz_ident' value='$zone_ident'>
         <input type='hidden' name='action' value='pmz_auto_num_pmp'>";

    $this->form_head(con('autonumber_pmz'));

    if (!isset($data['first_row'])) {
        $data['first_row'] = 1;
    }
    if (!isset($data['step_row'])) {
        $data['step_row'] = 1;
    }
    if (!isset($data['first_seat'])) {
        $data['first_seat'] = 1;
    }
    if (!isset($data['step_seat'])) {
        $data['step_seat'] = 1;
    }

    $this->print_input('first_row', $data, $err, 3, 4);
    $this->print_input('step_row', $data, $err, 3, 4);
    $this->print_checkbox('inv_row', $data, $err);
    $this->print_input('first_seat', $data, $err, 3, 4);
    $this->print_input('step_seat', $data, $err, 3, 4);
    $this->print_checkbox('inv_seat', $data, $err);
    $this->print_checkbox('flip', $data, $err);
    $this->print_checkbox('keep', $data, $err);

    $this->form_foot();

    echo "</form><br>".$this->show_button("{$_SERVER['PHP_SELF']}?action=view_pmp&pmp_id={$pmp_id}",'admin_list',3);
    $this->pmpnamesList($pmp->pm_id, $pmp->pmp_id);
  }

  function pmp_check ($data, &$err)  {
    if (!isset($data['pmp_name']) or (!$data['pmp_name'])) {
        $err['pmp_name'] = con('mandatory');
    }
    if (!$data['pmp_id']) {
      if (!isset($data['pmp_width']) or (!$data['pmp_width'])) {
          $err['pmp_width'] = con('mandatory');
      }
      if (!isset($data['pmp_height']) or (!$data['pmp_height'])) {
          $err['pmp_height'] = con('mandatory');
      }
    }
    return empty($err);
  }


  function pmpnamesList ($pm_id, $pmp_id = 0, $view_only = false) {
    if (!$pmps = PlaceMapPart::loadNames($pm_id) or count($pmps) < 2) {
        return;
    }

    if ($view_only) {
        $action = "view_only_pmp";
    } else {
        $action = "view_pmp";
    }

//        echo"<br><br><center>";
    foreach($pmps as $pmp) {
      if ($pmp_id != $pmp->pmp_id) {
          echo " ".$this->show_button("{$_SERVER['PHP_SELF']}?action={$action}&pmp_id={$pmp->pmp_id}",$pmp->pmp_name,1);
      } else {
          echo " ".$this->show_button("{$_SERVER['PHP_SELF']}?action={$action}&pmp_id={$pmp->pmp_id}",$pmp->pmp_name,1,
              array('disable'=>true));
      }
    }
//        echo"</center>";
  }

  function draw (){
    global $_SHOP;
//    print_r($_REQUEST);
    if ($_GET['action'] == 'add_pmp' and $_GET['pm_id'] > 0) {
      $this->form($_GET, null);
    } else if ($_GET['action'] == 'edit_pmp' and $_GET['pmp_id'] > 0) {
      $this->view($_GET['pmp_id'] , null);
    } elseif ($_POST['action'] == 'save_pmp' and $_POST['pm_id'] > 0) {
      if (!$pmp = PlaceMapPart::load((int)$_POST['pmp_id'])) {
        if ($pm = PlaceMap::load($_POST['pm_id'])) {
          $pmp = new PlaceMapPart;
          $pmp->pmp_event_id = $pm->pm_event_id;
          $pmp->pmp_pm_id    = $pm->pm_id;
        }
      }
      if (!$pmp or ((!$pmp->fillPost() or !$pmp->saveEx()) && !$_POST['pmp_id'] )) {
        $this->form($_POST, null);
      } else {
        $this->view($pmp->pmp_id, null);
      }
    } elseif ($_GET['action'] == 'view_pmp' and $_GET['pmp_id'] > 0) {
        $this->view($_GET['pmp_id'], $err, $_GET['category_id'], $_GET['pmz_ident'], $err);
    } elseif ($_GET['action'] == 'view_only_pmp' and $_GET['pmp_id'] > 0) {
        $this->view($_GET['pmp_id'], null, 0, 0, true);

    } elseif ($_GET['action'] == 'remove_pmp' and $_GET['pmp_id'] > 0) {
      if($pmp = PlaceMapPart::load($_GET['pmp_id']))
        $pmp->delete();
      return true;

    } else if ($_POST['action'] == 'def_cat_pmp' and $_POST['pmp_id'] > 0 and $_POST['category_id'] > 0) {
      if (empty($_POST['seat']) or is_array($_POST['seat'])) {
        if ($pmp = PlaceMapPart::load($_POST['pmp_id']) ) {
          $pmp->set_category($_POST['category_id'], $_POST['seat']);
          $pmp->save();
        }
      }
      $this->view($_POST['pmp_id']);
    } else if ($_POST['action'] == 'def_pmz_pmp' and $_POST['pmp_id'] > 0 and $_POST['zone_id'] > 0) {
      if (is_array($_POST['seat'])) {
        if ($pmp = PlaceMapPart::load($_POST['pmp_id']) ) {
          $pmp->set_zone($_POST['zone_id'], $_POST['seat']);
          $pmp->save();
        }
      }
      $this->view($_POST['pmp_id'], $err);
    } else if ($_POST['action'] == 'def_label_pmp' and $_POST['pmp_id'] > 0 and $_POST['label_type']) {
      if (is_array($_POST['seat'])) {
        if ($pmp = PlaceMapPart::load($_POST['pmp_id'])) {
          $pmp->set_label($_POST['label_type'], $_POST['seat'], $_POST['label_text']);
          $pmp->save();
        }
      }
      $this->view($_POST['pmp_id']);
    } else if ($_POST['action'] == 'def_clear_pmp' and $_POST['pmp_id'] > 0) {
      if (is_array($_POST['seat'])) {
        if ($pmp = PlaceMapPart::load($_POST['pmp_id'])) {
          $pmp->clear($_POST['seat']);
          $pmp->save();
        }
      }
      $this->view($_POST['pmp_id']);

    } else if ($_GET['action'] == 'pmz_edit_num_pmp' and $_GET['pmp_id'] and $_GET['pmz_ident']) {
        $this->zone_edit($_GET['pmz_ident'], $_GET['pmp_id']);
    } else if ($_POST['action'] == 'pmz_save_num_pmp' and $_POST['pmp_id'] and $_POST['pmz_ident']) {
      if ($pmp = PlaceMapPart::load($_POST['pmp_id'])) {
        $pmp->setNumbers($_POST['pmz_ident'], $_POST['seat']);
        $pmp->save();
      }
      $this->zone_edit($_POST['pmz_ident'], $_POST['pmp_id']);
    } else if ($_POST['action'] == 'pmz_auto_num_pmp' and $_POST['pmp_id'] and $_POST['pmz_ident']) {
      if ($this->check_autonumbers($_POST, $err)) {
        if ($pmp = PlaceMapPart::load($_POST['pmp_id'])) {
          $pmp->auto_numbers($_POST['pmz_ident'],
              $_POST['first_row'], $_POST['step_row'], $_POST['inv_row'],
              $_POST['first_seat'], $_POST['step_seat'], $_POST['inv_seat'],
              $_POST['flip'], $_POST['keep']);
          $pmp->save();
        }
      }
      $this->zone_edit($_POST['pmz_ident'], $_POST['pmp_id']);

    } elseif ( $_GET['action'] == 'split_pmp' and $_GET['pm_id'] > 0 ) {
      $this->split_form( $_GET['pm_id'], $_GET['pmp_id'] );
    } elseif ( $_POST['action'] == 'split_pmp' and $_POST['pm_id'] > 0 ) {
      if ($pm = PlaceMap::load($_POST['pm_id']) ) {
        $pm->split( $_POST['pm_parts'], $_POST['split_zones'] );
        $this->view( $_POST['pm_id'] );
      }
    }
  }

    function check_autonumbers (&$data, &$err)
    {
        if (!isset($data['first_row']) or (!$data['first_row'])) {
            $err['first_row'] = con('mandatory');
        }
        if (!isset($data['first_row']) or (!$data['step_row'])) {
            $err['step_row'] = con('mandatory');
        }
        if (!isset($data['first_row']) or (!$data['inv_row'])) {
            $data['inv_row'] = 0;
        }
        if (!isset($data['first_row']) or (!$data['first_seat'])) {
            $err['first_seat'] = con('mandatory');
        }
        if (!isset($data['first_row']) or (!$data['step_seat'])) {
            $err['step_seat'] = con('mandatory');
        }
        if (!isset($data['first_row']) or (!$data['inv_seat'])) {
            $data['inv_seat'] = 0;
        }
        if (!isset($data['first_row']) or (!$data['flip'])) {
            $data['flip'] = 0;
        }
        if (!isset($data['first_row']) or (!$data['keep'])) {
            $data['keep'] = 0;
        }
        return empty($err);
    }
}

?>