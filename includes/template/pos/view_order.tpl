{*
/**
%%%copyright%%%
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
 *}
<form name='f' action='index.php' method='post'>
<table width='700'>
  <tr>
	<td  width='50%' valign='top'>
	{order->order_list order_id=$smarty.get.order_id}
  	  <table  cellspacing='1' cellpadding='4' border='0'>
  		<tr>
		  <td class='title'>{!order_id!} {$shop_order.order_id}</td>
  		  <td align='right'>
			<table width='100' >
  			  <tr>
		  		<td align='center'>
				  {if $shop_order.order_status neq "cancel" and $shop_order.order_status neq "reemit"}
      				<a href='print.php?mode=doit&order_id={$shop_order.order_id}'><img border='0' src='images/printer.gif'></a> 
      				<a href='javascript:if(confirm("{!pos_deleteorder!}")){literal}{location.href="index.php?action=cancel_order&order_id={/literal}{$shop_order.order_id}{literal}";}{/literal}'>
      				<img border='0' src='images/trash.png'></a>
   					{/if}
  				  </td>
				</tr>
  			</table>
  		  </td>
  		</tr>
  		<tr>
		  <td class='admin_info'>
    		{!number_tickets!}
  		  </td>
		  <td class='subtitle'>{$shop_order.order_tickets_nr}</td>
		</tr>
  		<tr>
		  <td class='admin_info'>{!user!} {!id!}</td>
		  <td class='subtitle'>{$shop_order.order_user_id}</td>
		</tr>
		<tr>
		  <td class='admin_info'>{!total_price!}</td>
		  <td class='subtitle'>{$shop_order.order_total_price|string_format:"%1.2f"} {$organizer_currency}</td>
		</tr>
		<tr>
		  <td class='admin_info'>{!order_date!}</td>
		  <td class='subtitle'>{$shop_order.order_date}</td>
		</tr>
		<tr>
		  <td class='admin_info'>{!status!}</td>
		  <td class='subtitle'>
		  {if $shop_order.order_status eq "res"}
		    <font color='orange'>{!reserved!}</font>
		  {elseif $shop_order.order_status eq "ord"}
		    <font color='blue'>{!ordered!}</font>
		  {elseif $shop_order.order_status eq "cancel"}
		    <font color='#cccccc'>{!cancelled!}</font>
		  {elseif $shop_order.order_status eq "reemit"}
		    <font color='#ffffcc'>{!reemitted!}</font>
		    (<a href='index.php?action=view_order&order_id={$shop_order.order_reemited_id}'>
		    {$shop_order.order_reemited_id}</a>)
		  {/if}
		  </td>
		</tr>
		{if $shop_order.order_status eq "res"}
			{* order->tickets order_id=$shop_order.order_id limit=1}
			<input type='hidden' name='category' value='{$shop_ticket.seat_category_id}'>
        	<input type='hidden' name='event' value='{$shop_ticket.seat_event_id}'>
        	{/order->tickets *}
			<input type='hidden' name='action' value='reorder'>
			<input type="hidden" name="user_id" value="{$shop_order.order_user_id}" >
			<input type="hidden" name="order_id" value="{$shop_order.order_id}" >
		<tr>
		  <td colspan="2" align="left">
		  {!pos_reorder_info!}<br>
		  	<center>
			  <input type='submit' name='submit' value='Order'>
			</center>
		  </td>
		</tr>
		{/if}
		<tr>
		  <td class="admin_info">{!Paymentstatus!}</td>
		  <td class="subtitle">
		  {if $shop_order.order_payment_status eq "none"}
		    <font color="#FF0000">{!NotPaid!}</font>
		  {elseif $shop_order.order_payment_status eq "payed"}
		  	<font color='green'>{!paid!}</font>
		  {/if}
		  </td>
		</tr>
		<tr>
		  <td class="admin_info">{!Shipmentstatus!}</td>
		  <td class="subtitle">
		  {if $shop_order.order_shipment_status eq "none"}
		  	<font color="#FF0000">{!Notsent!}</font>
		  {elseif $shop_order.order_shipment_status eq "send"}
		  	<font color='green'>{!sent!}</font>
		  {/if}
  	  	  </td>
		</tr>
  	  </table>
	</td>
	<td width="50%" valign="top">
 	  <table width="100%">
		<tr> 
	 	  <td class="title" valign="top">{!UserInformation!}</td>
		  <td class="title" valign="top">&nbsp;</td>
		</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!FirstName!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_firstname}</td>
	  	</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!SecondName!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_lastname}</td>
	  	</tr>
		<tr>
		  <td class="admin_info" valign="top">{!Address!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_address}</td>
	  	</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!Address1}</td>
		  <td class="sub_title" valign="top">{$user_order.user_address1}</td>
	  	</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!zip!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_zip}</td>
	  	</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!City!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_city}</td>
	  	</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!State!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_state}</td>
	  	</tr>
		<tr>
		  <td class="admin_info" valign="top">{!Country!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_country}</td>
	  	</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!Phone!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_phone}</td>
	  	</tr>
	  	<tr>
		  <td class="admin_info" valign="top">{!Email!}</td>
		  <td class="sub_title" valign="top">{$user_order.user_email}</td>
	  	</tr>
 	  </table>
	</td>
  </tr>
  <tr>
  	<td colspan="2">
	{/order->order_list}
  	  <table width='100%' cellspacing='1' cellpadding='4'>
		<tr>
		  <td class='title' colspan='8'>{!tickets!}<br></td>
		</tr>   
		<tr>
		  <td class='subtitle'>{!id!}</td>
		  <td class='subtitle'>{!event!}</td>
		  <td class='subtitle'>{!category!}</td>
		  <td class='subtitle'>{!zone!}</td>
		  <td class='subtitle'>{!seat!}</td>
		  <td class='subtitle'>{!discount!}</td>
		  <td class='subtitle'>{!price!}</td>
		</tr>
		{order->tickets order_id=$shop_order.order_id}
		{counter assign='row' print=false}
		<input type='hidden' name='place[]' value='{$shop_ticket.seat_id}'>
		<tr class='admin_list_row_{$row%2}'>
		  <td class='admin_info'>{$shop_ticket.seat_id}</td>
		  <td class='admin_info'>{$shop_ticket.event_name}</td>
		  <td class='admin_info'>{$shop_ticket.category_name}</td>
		  <td class='admin_info'>{$shop_ticket.pmz_name}</td>
		  <td class='admin_info'>
		  {if not $shop_ticket.category_numbering or $shop_ticket.category_numbering eq "both"}
		  	{$shop_ticket.seat_row_nr}  -  {$shop_ticket.seat_nr}
		  {elseif $shop_ticket.category_numbering eq "rows"}
		  	{!row!}{$shop_ticket.seat_row_nr}
		  {else}
		  	---
		  {/if}</td>
		  <td class='admin_info'>{$shop_ticket.discount_name}</td>
		  <td class='admin_info' align='right'>{$shop_ticket.seat_price}</td>
		  <td class='admin_info' align='center'><a href='javascript:if(confirm("{#cancel_ticket#}  {$shop_ticket.seat_id}?")){literal}{location.href="index.php?action=cancel_ticket&order_id={/literal}{$shop_ticket.seat_order_id}&ticket_id={$shop_ticket.seat_id}{literal}";}{/literal}'><img border='0' src='images/trash.png'></a></td>
		</tr>
		{/order->tickets}
	  </table>
	<br>
	</td>
  </tr>
</table></form>
<br>