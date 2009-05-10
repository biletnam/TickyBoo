{*
%%%copyright%%%
 * Fusion Ticket System
 * Based on phpMyTicket - ticket reservation system
 * Orginal Copyright (C) 2004-2005 Anna Putrino, Stanislav Chachkov. All rights reserved.
 *
 * Copyright (C) 2007-2008 Christopher Jenkins
 *
 * This file is part of fusion ticket, it may be modified or used in any senario but
 * not as your own. This file is free and open source any distrubution of your own
 * will have to apply to the GNU rules as well.
 *
 * This file may be distributed and/or modified under the terms of the
 * "GNU General Public License" version 2 as published by the Free
 * Software Foundation and appearing in the file LICENSE included in
 * the packaging of this file.
 *
 *
 * This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
 * THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE.
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 *}
<form name="PayPal" action="{$url}" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="business" value="{$shop_handling.extra.pm_paypal_business}">
  <input type="hidden" name="item_name" value="Pepper Tickets - Order No. {$id}">
  <input type="hidden" name="item_number" value="{$id}">
  <input type="hidden" name="amount" value="{$price}">
  <input type="hidden" name="image_url" value="https://www.paypal.com/images/x-click-but23.gif">
  <input type="hidden" name="return" value="{$_SHOP_root_secured}paypal_return.php">
  <input type='hidden' name='notify_url' value='{$_SHOP_root_secured}paypal_notify.php'>
  <input type="hidden" name="cancel_return" value="{$_SHOP_root_secured}paypal_cancel.php">
  <input type="hidden" name="currency_code" value="GBP">
  <input type='hidden' name='undefined_quantity' value='0'>
  <input type="hidden" name="no_shipping" value="1">
  <input type='hidden' name='no_note' value='1'>
  <input type="image" src="https://www.paypal.com/images/x-click-but23.gif" name="submit2" alt="Make payments with PayPal - it's fast, free and secure!" >
</form>