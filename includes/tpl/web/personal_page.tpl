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

{*
Replication is not allowed under the Open source software act, this file
may be edited but may not be used as yours or redistributed.
*}
<table width="100%" cellpadding="3" class="main">
	<tr>
	  <td class="title">
      <h3>{!personal!}</h3>      </td>
      <td class="title">
      <h3>{!pers_orders!}</h3>      </td>
    </tr>
    <tr>
    	<td valign="top">
          <table class="table_dark">
            <tr>
              <td colspan="2"><p>{!pers_mess!}
              </p><br />
			  </td>
            </tr>
            <tr>
              <td>{!firstname!}</td>
              <td>{user->user_firstname}</td>
            </tr>
            <tr>
              <td>{!lastname!}</td>
              <td>{user->user_lastname}</td>
            </tr>
            <tr>
              <td>{!address!} 1</td>
              <td>{user->user_address}</td>
            </tr>
            <tr>
              <td>{!address!} 2</td>
              <td>{user->user_address2}</td>
            </tr>
            <tr>
              <td>{!zip!}</td>
			        <td>{user->user_zip}</td>
            </tr>
            <tr>
              <td>{!city!}</td>
              <td>{user->user_city}</td>
            </tr>
            <tr>
              <td>{!state!}</td>
              <td>{user->user_state}</td>
            </tr>
            <tr>
              <td>{!country!}</td>
              <td>{include file="countries.tpl" code=$user->user_country}</td>
            </tr>
            <tr>
              <td>{!phone!}</td>
              <td>{user->user_phone}</td>
            </tr>
            <tr>
              <td>{!fax!}</td>
              <td>{user->user_fax}</td>
            </tr>
            <tr>
              <td>{!email!}</td>
              <td>{user->user_email}</td>
            </tr>
        </table>
	  </td>
      <td valign="top">
		<table class="table_dark">
		  <tr>
		  	<td colspan="5"><p>{!pers_mess2!}  <br></p>
			</td>
		  </tr>
          <tr>
            <td><p><strong>{!ordernumber!}</strong></p></td>
            <td><p><strong>{!orderdate!}</strong></p></td>
            <td><p><strong>{!tickets!}</strong></p></td>
            <td><p><strong>{!total_price!}</strong></p></td>
            <td><p><b>{!status!}</b></p></td>
          </tr>
   {order->order_list user_id=$user->user_id order_by_date="DESC" length=6}
		    {if $shop_order.order_status eq "cancel"}
				<tr class='user_order_{$shop_order.order_status}'>
			{elseif $shop_order.order_status eq "reemit"}
				<tr class='user_order_{$shop_order.order_status}'>
			{elseif $shop_order.order_status eq "res"}
				<tr class='user_order_{$shop_order.order_status}'>
			{elseif $shop_order.order_shipment_status eq "send"}
				<tr class='user_order_{$shop_order.order_shipment_status}'>
			{elseif $shop_order.order_payment_status eq "payed"}
				<tr class='user_order_{$shop_order.order_payment_status}'>
			{elseif $shop_order.order_status eq "ord"}
				<tr class='user_order_{$shop_order.order_status}'>
			{else}
				<tr class='user_order_cancel'>
			{/if}
		    <td class='admin_info'>{$shop_order.order_id}</td>
			<td class='admin_info'>{$shop_order.order_date}</td>
			<td class='admin_info'>{$shop_order.order_tickets_nr}</td>
			<td class='admin_info'>{$shop_order.order_total_price}</td>
			<td class='admin_info'>
			{if $shop_order.order_status eq "cancel"}{!pers_cancel!}
			{elseif $shop_order.order_status eq "reemit"}{!pers_reeemit!}
			{elseif $shop_order.order_status eq "res"}{!pers_res!}
			{elseif $shop_order.order_shipment_status eq "send"}{!pers_sent!}
			{elseif $shop_order.order_payment_status eq "payed"}{!pers_payed!}
			{elseif $shop_order.order_status eq "ord"}{!pers_ord!}
			{else}{!pers_unknown!}
			{/if}</td>
	  	  </tr>
 	  {/order->order_list}
        </table></td>
    </tr>
</table>