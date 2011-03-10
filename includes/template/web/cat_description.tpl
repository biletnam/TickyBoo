{*                  %%%copyright%%%
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
 *}
{if $shop_event.event_pm_id}
	{assign var=event_has_seats value="false"}
  <form id='catselect' method='post' action='index.php'>
    {ShowFormToken}
  <br>
  <table border=0 class='table_midtone'>
    <tr>
      <td colspan='3' class="title2">
        {!cat_description!}
      </td>
    </tr>
		<tr>
      <th></th>
			<th>{!category!}</th>
			<th>{!price!}</th>
			<th>{!tickets_available!}</th>
		</tr>
    {category event_id=$shop_event.event_id stats="on"}
      <tr class="{cycle name='events' values="TblHigher,TblLower"}">
        <td>
    		  {if $user->mode() neq '-1' or $user->logged}
            <span id="catcolor" style="background-color:{$shop_category.category_color}">
              <input type="radio" id="category_id_{$shop_category.category_id}" name="category_id" value="{$shop_category.category_id}" {if $category_id eq $shop_category.category_id}checked{/if} onClick="setNum('{$shop_category.category_numbering}')" {if $shop_category.category_free == 0}disabled="true"{/if}>
            </span>
          {/if}
        </td>
        <td><b>{$shop_category.category_name}</b></td>
        <td align='right'>
          {valuta value=$shop_category.category_price}
        </td>
        <td align='left' width='25%'>
          {if $shop_category.category_free>0}
          	{assign var=event_has_seats value="true"}
	          {if $shop_category.category_free/$shop_category.category_size ge 0.2}
              <font>{!tickets_available!} {$shop_category.category_free}</font>
            {else}
              <font color='Yellow'>{!tickets_available!} {$shop_category.category_free}</font>
            {/if}
          {else}
            <span class='error'>{!category_sold!}</span>
          {/if}
        </td>
      </tr>
      <tr>
        <td colspan='4'>
          {!Discount_for!}
          {discount event_id=$shop_event.event_id cat_price=$shop_category.category_price}
            &nbsp;
            <span class='note'>
               {$shop_discount.discount_name}:
                {valuta value=$shop_discount.discount_price|string_format:"%.2f"}
            </span> &nbsp;
          {/discount}
        </td>
      </tr>
    {/category}
     <tr>
      <td colspan='3' align='left' class='note'>
        {!prices_in!} {$organizer_currency}
      </td>
    </tr>
  </table>
  {/literal}
  {if $user->mode() eq '-1' and !$user->logged}
      <br />
    	<table class='table_dark' cellpadding='5' bgcolor='white' width='100%'>
      	<tr>
    			<td class='TblLower'>
          			{!Please_login!}
          </td>
			</tr>
		</table> <br/>   <br/>
  {elseif $shop_event.event_date ge $smarty.now|date_format:"%Y-%m-%d"}
    <div id='num-tickets' style='visibility:hidden;'>
      <label>{!select_qty!}:</label>
				{cart->maxSeatsAlowed event=$shop_event}
                <select name='qty' id='qty'  class="styled">
                  {section name="myLoop" start=0 loop=$seatlimit+1}
                    <option value='{$smarty.section.myLoop.index}' > {$smarty.section.myLoop.index} </option>
                  {/section}
                </select>
                <span class="limit">{if $seatlimit>0}({!order_limit!} {$seatlimit}){/if}</span>
	  </div>
	      <!-- Steps -->
    {if $event_has_seats == "true"}
			<div class="next" align='right'>
		        <input type='hidden' name='event_id' value='{$shop_event.event_id}'>
		        <button title="Next" onclick=" validateSelection();" >Next</button>
			</div>
  	{/if}
    </form>
		{literal}
			<script>
					mode = 'both';
					function setNum(cat_mode) {
						if (cat_mode != 'none') {
							$("#num-tickets").css({visibility: "hidden", display: ""});
							mode = 'both';
						}
						else {
							$("#num-tickets").css({visibility: "visible", display: ""});
							mode = 'none';
						}
						$("#qty").val(0);
					}

					function validateSelection() {
						if (mode == 'none') {
							bool= ($('#qty').val() != 0 && $('input[name=category_id]:checked').val() != "");
							if (!bool) {
								showErrorMsg('Please select your category and the number of tickets');
								$("html,body").animate({
			        				scrollTop: $("#error-message").offset().top
									}, 1000, function(){
									        //scroll complete function
									});
								return false;
							}
						} else {
							bool= $('input[name=category_id]:checked').val() != null;
							if (!bool) {
								showErrorMsg('Please select your category');
								$("html,body").animate({
			        				scrollTop: $("#error-message").offset().top
									}, 1000, function(){
									        //scroll complete function
									});
								return false;
							}
						}
						$('#catselect').submit();
						return true;
					}
			</script>
			{/literal}
      {if $shop_event.pm_image}
        <br>
        <table  class='table_midtone'>
          <tr>
            <td class='title2' colspan='3' >
              {!select_category!}
            </td>
          </tr>
          <tr>
            <td colspan='3'>
              <img class="chartmap" src="files/{$shop_event.pm_image}"  border='0'  usemap="#ort_map">
              <map name="ort_map">
                {category event_id=$shop_event.event_id stats="on"}
                  {if $shop_category.category_free gt 0}
                    <area href="index.php?category_id={$shop_category.category_id}&event_id={$shop_event.event_id}" {$shop_category.category_data} />
                  {/if}
                {/category}
              </map>
            </td>
        </tr>
      </table>
      {literal}
      <script>$(function() {
    		$('.chartmap').maphilight();
    	});</script>
      {/literal}
    {/if}
    <br>
  {else}
    	<table class='table_dark' cellpadding='5' bgcolor='white' width='100%'>
      	<tr>
    			<td class='TblLower'>
          			{!old_event!}
          </td>
			</tr>
		</table> <br/> <br/>

  {/if}
{/if}