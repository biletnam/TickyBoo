<script type="text/javascript">
{literal}
	$(document).ready(function(){
		loadOrder();	
	});
{/literal}
</script>
<div style="width:100%;">
	<h2>{!pos_booktickets!}</h2>
	<form name='addtickets' action='index.php' method='post'>
	<table id="cart-table">
		<thead>
			<tr>
				<td>{!event!}</td>
				<td>{!tickets!}</td>
				<td>{!total!}</td>
				<td>{!expires_in!}</td>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tbody>
			<tr>
				<td>{!event!}:</td>
				<td>
					<input type="text" id="event-input" size="30" />
					<input type="hidden" id="event-id" />
				</td>
				<td>
			   		{!select_category!}:
				</td>
				<td>
					<select name='category_id' id='cat-select'>
						<option value='0'>No Event Selected</option>
					</select>
				</td>
				<td id="qty-td">
				</td>
				<td>
					<button type="button" id="clear-button">Clear Selection</button>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div id="seat-chart" style="width:100%; border:1px solid #000;">
</div>
<div id="continue-div" style="width:100%; overflow:auto;">
	<button type="button" id="continue" name='submit' value='continue'>{!continue!} </button>
</div>
<div style="float:left; overflow:auto; width:50%;">
	Personal details
</div>
<div style="float:left; overflow:auto; width:50%;">
	Payment Method
</div>
</form>
