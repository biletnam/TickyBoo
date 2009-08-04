{literal}
<style type="text/css">

.pagination{
padding: 0px;
}

.pagination ul{
margin: 0;
padding: 0;
text-align: left; /*Set to "right" to right align pagination interface*/
font-size: 16px;
}

.pagination li{
list-style-type: none;
display: inline;
padding-bottom: 1px;
}

.pagination a, .pagination a:visited{
padding: 0 5px;
border: 1px solid #42106b;
text-decoration: none;
color: #2e6ab1;
}

.pagination a:hover, .pagination a:active{
border: 1px solid #2b66a5;
color: #42106b;
background-color: #FFFF80;
}

.pagination a.currentpage{
background-color: #42106b;
color: #FFF !important;
border-color: #2b66a5;
font-weight: bold;
cursor: default;
}

.pagination a.disablelink, .pagination a.disablelink:hover{
background-color: white;
cursor: default;
color: #929292;
border-color: #929292;
font-weight: normal !important;
}

.pagination a.prevnext{
font-weight: bold;
}
</style> {/literal}
{strip}       <br>
<div align="center">
  <table border="0" width="100%">
    <tr>
      <td align="left">
        <div class="pagination">
          {if $name==!shop!}
            {if $shop_event.event_pm_id}
              <ul>
                <li><a class="currentpage">Order</a></li>
                <li><a class="disablelink">Select Discounts</a></li>
                <li><a title="Click here to View, Edit, or Remove your Order!" href="index.php?action=view_cart">Review Order</a></li>
                {if $user->logged}
                  <li><a class="disablelink">Log-in or Register</a></li>
                {else}
                   <li><a title="Only Registered Shoppers can Purchase!" href="index.php?register_user=on">Log-in or Register</a></li>
                {/if}
                <li><a title="Choose Shipping &amp; Payment Options!" href="checkout.php">Select Payment</a></li>
                <li><a class="disablelink">Complete Order</a></li>
              </ul>
            {/if}
          {elseif $name==!select_seat!}
            <ul>
              <li><a class="currentpage">Order</a></li>
              <li><a class="disablelink">Select Discounts</a></li>
              <li><a title="Click here to View, Edit, or Remove your Order!" href="index.php?action=view_cart">Review Order</a></li>
              {if $user->logged}
                <li><a class="disablelink">Log-in or Register</a></li>
              {else}
                <li><a title="Only Registered Shoppers can Purchase!" href="index.php?register_user=on">Log-in or Register</a></li>
              {/if}
              <li><a title="Choose Shipping &amp; Payment Options!" href="checkout.php">Select Payment</a></li>
              <li><a class="disablelink">Complete Order</a></li>
            </ul>
          {elseif $name==!discounts!}
            <ul>
              <li><a class="disablelink">Order</a></li>
              <li><a class="currentpage">Select Discounts</a></li>
              <li><a class="disablelink">Review Order</a></li>
              <li><a class="disablelink">Log-in or Register</a></li>
              <li><a class="disablelink">Select Payment</a></li>
              <li><a class="disablelink">Complete Order</a></li>
            </ul>
          {elseif $name==!shopping_cart!}
             {assign var="cart_empty" value=$cart->is_empty_f()}
             {if !$cart_empty }
               <ul>
                 <li><a title="Order more Tickets!" href="calendar.php">Order</a></li>
                 <li><a class="disablelink">Select Discounts</a></li>
                 <li><a class="currentpage">Review Order</a></li>
                 {if $user->logged}
                   <li><a class="disablelink">Log-in or Register</a></li>
                 {else}
                   <li><a title="Only Registered Shoppers can Purchase!" href="index.php?register_user=on">Log-in or Register</a></li>
                 {/if}
                 <li><a title="Choose Shipping &amp;amp; Payment Options!" href="checkout.php">Select Payment</a></li>
                 <li><a class="disablelink">Complete Order</a></li>
               </ul>
             {/if}
          {elseif $name==!pers_info!}
            <ul>
              <li><a title="Order more Tickets!" href="calendar.php">Order</a></li>
              <li><a class="disablelink">Select Discounts</a></li>
              <li><a title="Click here to View, Edit, or Remove your Order!" href="index.php?action=view_cart">Review Order</a></li>
              <li><a class="currentpage">Log-in or Register</a></li>
              <li><a class="disablelink">Select Payment</a></li>
              <li><a class="disablelink">Complete Order</a></li>
            </ul>
          {elseif $name==!shopping_cart_check_out!}
             <ul>
                <li><a title="Order more Tickets!" href="calendar.php">Order</a></li>
                <li><a class="disablelink">Select Discounts</a></li>
                <li><a title="Click here to View, Edit, or Remove your Order!" href="index.php?action=view_cart">Review Order</a></li>
                <li><a class="disablelink">Log-in or Register</a></li>
                <li><a class="currentpage">Select Payment</a></li>
                <li><a class="disablelink">Complete Order</a></li>
             </ul>
           {/if}
        </div>
      </td>
    </tr>
  </table>
</div>
{/strip}