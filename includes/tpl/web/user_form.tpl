{*
 * %%%copyright%%%
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
 *}
<tr>
  <td class='TblLower'> {!firstname!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_firstname' size='30' maxlength='50' value='{$user_data.user_firstname}'><span class='error'>{$user_errors.user_firstname}</span></td>
</tr>
<tr>
  <td class='TblLower'> {!lastname!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_lastname' size='30'  maxlength='50' value='{$user_data.user_lastname}'><span class='error'>{$user_errors.user_lastname}</span></td>
</tr>
<tr>
  <td class='TblLower'> {!address!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_address' size='30'  maxlength='75' value='{$user_data.user_address}'><span class='error'>{$user_errors.user_addresse}</span></td>
</tr>
<tr>
  <td class='TblLower'> {!address!} 2 </td>
  <td class='TblHigher'><input type='text' name='user_address1' size='30'  maxlength='75' value='{$user_data.user_address1}'><span class='error'>{$user_errors.user_addresse1}</span></td>
</tr>
<tr>
  <td class='TblLower'> {!zip!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_zip' size='8'  maxlength='20' value='{$user_data.user_zip}'><span class='error'>{$user_errors.user_zip}</span></td>
</tr>
<tr>
  <td class='TblLower'> {!city!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_city' size='30'  maxlength='50' value='{$user_data.user_city}'><span class='error'>{$user_errors.user_city}</span></td>
</tr>
<tr>
  <td class='TblLower'> {!state!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_state' size='30' maxlength="50" value='{$user_data.user_state}'><span class='error'>{$user_errors.user_state}</span></td>
</tr>
<tr>
  <td class='TblLower'> {!country!}&nbsp;* </td>
  <td class='TblHigher'>
    <select name='user_country'>
      {include file="countries.tpl" selected=$user_data.user_country}
    </select><span class='error'>{$user_errors.user_country}</span>
  </td>
</tr>
<tr>
  <td class='TblLower'  > {!phone!} </td>
  <td class='TblHigher'><input type='text' name='user_phone' size='30'  maxlength='50' value='{$user_data.user_phone}'><span class='error'>{$user_errors.user_phone}</span></td>
</tr>
<tr>
  <td class='TblLower'  > {!fax!} </td>
  <td class='TblHigher'><input type='text' name='user_fax' size='30'  maxlength='50' value='{$user_data.user_fax}'><span class='error'>{$user_errors.user_fax}</span></td>
</tr>
<tr>
  <td class='TblLower' > {!email!}&nbsp;* </td>
  <td class='TblHigher'><input type='text' name='user_email' size='30'  maxlength='50' value='{$user_data.user_email}'><span class='error'>{$user_errors.user_email}</span></td>
</tr>