<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rental System</title>
	  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	  <link rel="stylesheet" href="/resources/demos/style.css">
	  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>  
	  <script>
	  $(function() {
			$(".date_rent_start,.date_rent_end").datepicker();
			$(".date_rent_start,.date_rent_end").datepicker("option", "dateFormat", "yy-mm-dd");			
			$(".date_rent_start").change(function(){
				$(".date_rent_end").change();
			});
			$(".date_rent_end").change(function(){
				var id = $(this).attr("id");
				$.post( "calculate_distance_between_two_date", { 
					"date_rent_start": $("#"+id+"_rent_start").val(),  
					"date_rent_end": $(this).val(),
					"years_price": $("#"+id+"_years_price").val(),
					"months_price": $("#"+id+"_months_price").val(),
					"weeks_price": $("#"+id+"_weeks_price").val(),
					"days_price": $("#"+id+"_days_price").val(),	
					_token : "{{ csrf_token() }}"
					})
				  .done(function( data ) {
						$("#"+id+"_rent_calculation").html(data);					
					});
			});			
			$('.number').keyup(function(e){
			  if (/\D/g.test(this.value)){
				// Filter non-digits from input value.
				this.value = this.value.replace(/\D/g, '');
			  }
			});		
	  });
				var year = <?php echo $year; ?>;
				var order_charts = <?php echo $order_charts; ?>;
				var barChartData = {
					labels: year,
					datasets: [{
						label: 'Order',
						backgroundColor: "pink",
						data: order_charts
					}]
				};

				window.onload = function() {
					var ctx = document.getElementById("canvas").getContext("2d");
					window.myBar = new Chart(ctx, {
						type: 'bar',
						data: barChartData,
						options: {
							elements: {
								rectangle: {
									borderWidth: 2,
									borderColor: '#c1c1c1',
									borderSkipped: 'bottom'
								}
							},
							responsive: true,
							title: {
								display: true,
								text: 'Yearly Orders Total'
							}
						}
					});
				};
	  </script>
</head>
	@inject('helper', \App\Classes\CommonClass::class)
  <h4>Welcome <b>{{Auth::user()->email}}</b>.</h4>
	  <input type="button" name="rents" value="Clear Searching" onClick="document.location.href='{{route('home')}}';"/>
	  <br>
	  Rented Data:<br>
	  <form action="{{route('home') }}" method="GET" enctype="multipart/form-data">
		<input type="text" id="searching_renteds" name="searching_renteds" value="<?=$searching_renteds?>"/> 
		<input type="submit" id="searching_button" name="searching_button" value="Search"/>	 
	  </form>	  
	  <table border=1>
	  <?php 
		$header = true;
		foreach ($renteds as $rent) {
			$rent = (array)$rent;
			if($header){
				?><tr><td></td><?php
				foreach ($rent as $a => $b){
					?><td><?=str_replace('_', ' ', $a)." ".$helper->getRentedsTableComment($a)?></td><?php
				}
				?><td>&nbsp;</td></tr><?php	
				$header = false;				
			}		
			?><tr><td>
			<?php 
			if(Auth::user()->role == "Administrator"){			
			?>
			<input type="button" name="Edit" value="Edit" onClick="document.location.href='?edit_renteds=<?=$rent["name_of_items"]?>';"/>
			<input type="button" name="Delete" value="Delete" onClick="document.location.href='?delete_rents=<?=$rent["name_of_items"]?>';"/>
			<?php 
			}
			?>
			</}td><?php
			foreach ($rent as $a => $b){
				if(str_contains($a, 'price')){
					?><td><?=$helper->rupiah($b)?></td><?php					
				}else{
					?><td><?=$b?></td><?php										
				}
			}
			$rent['name_of_items'] =  str_replace("/","_",str_replace(")","_",str_replace("(","_",str_replace(" ","_",$rent['name_of_items']))));
			?><td>
				<form action="{{route('update_orders')}}" method="POST">
					@csrf
					<input type="hidden" id="orders_name_of_items" name="name_of_items" value="<?=$rent['name_of_items']?>">					
					<input type="hidden" id="<?=$rent['name_of_items']?>_days_price" name="days_price" value="<?=$rent['days_price']?>">					
					<input type="hidden" id="<?=$rent['name_of_items']?>_weeks_price" name="weeks_price" value="<?=$rent['weeks_price']?>">
					<input type="hidden" id="<?=$rent['name_of_items']?>_months_price" name="months_price" value="<?=$rent['months_price']?>">										
					<input type="hidden" id="<?=$rent['name_of_items']?>_years_price" name="years_price" value="<?=$rent['years_price']?>">															
					Rent:
					<input id="<?=$rent['name_of_items']?>_rent_start" name="date_rent_start" class="date_rent_start" type="text" value=""></input>
					<br>Till:
					<input id="<?=$rent['name_of_items']?>" name="date_rent_end" class="date_rent_end" type="text" value=""></input>
					<div id="<?=$rent['name_of_items']?>_rent_calculation"></div>	
					<br>Address Buyer:
					<input name="address_buyer" type="text" value=""></input><br>												
					<br>Address Name:
					<input name="address_name" type="text" value=""></input><br>																
					<br>Address Phone:
					<input id="address_phone" class="number" name="address_phone" type="text" value=""></input><br>																				
					<input id="submit" name="submit" type="submit" value="Rent"></input>								
				</form>
			  </td>
			</tr><?php			
		}    
	  ?>
	  </table>
		<?php 
			for($a=1;$a<=$count_renteds;$a++){
				if($a==$current_renteds+1){
					echo $a."&nbsp;";										
				}else{
					echo "<a href='?searching_renteds=".$searching_renteds."&count_rents=".$a."'>".$a."</a>&nbsp;";					
				}				
			}
?>
	  <br><a href="{{ route('create_rents_pdf') }}">Download the Rented Data For PDF</a><br>	
	  <a class="btn btn-info" href="{{ route('exportRents.excel') }}">Download the Rented Data For Excel</a><br>		
<?php			
			if(Auth::user()->role == "Administrator"){			
		?>	
		<form action="{{ route('importRents.excel') }}"
			  method="POST"
			  enctype="multipart/form-data">
			  Upload Rented Data:
			@csrf
			<input type="file" name="file"
				   class="form-control">
			<br>
			<button class="btn btn-success">
				  Import Rented Data
			   </button>
		</form>		
	<form action="{{route('update_renteds')}}" method="POST">
		@csrf	
	  <?php 
	  // old_name_of_items
		foreach ($renteds as $rent) {
			$rent = (array)$rent;
			foreach ($rent as $a => $b){		
				$rent_selected[$a] = empty($rent_selected[$a])?"":$rent_selected[$a];
				if($a=="name_of_items"){
					?><input id="<?=$a?>" name="old_<?=$a?>" type="hidden" value="<?=$rent_selected[$a]?>"></input><?php										
					?><?=str_replace('_', ' ', $a)?><input id="<?=$a?>" name="<?=$a?>" type="text" value="<?=$rent_selected[$a]?>"></input><br><?php										
				}else if(str_contains($a, 'price')){
					?><?=str_replace('_', ' ', $a)?><input id="<?=$a?>" name="<?=$a?>" class="number" type="text" value="<?=$rent_selected[$a]?>"></input><br><?php					
				}else if($a != "created_at" && $a != "updated_at"){
					?><?=str_replace('_', ' ', $a)?><input id="<?=$a?>" name="<?=$a?>" type="text" value="<?=$rent_selected[$a]?>"></input><br><?php					
				}
			}
			break;	
		}
	  ?>
		<input id="submit" name="submit" type="submit" value="submit"></input><br>									  
	</form>		
	<?php 
		}
	?>
	  Items:<br>	
	  <form action="{{route('home') }}" method="GET">
		<input type="text" id="searching_items" name="searching_items" value="<?=$searching_items?>"/> 
		<input type="submit" id="searching_button" name="searching_button" value="Search"/>	 
	  </form>
	  <table border=1>
	  <?php
		$header = true;	  
		foreach ($items as $item) {
			$item = (array)$item;
			if($header){
				?><tr><td>&nbsp;</td><?php
				foreach ($item as $a => $b){
					?><td><?=str_replace('_', ' ', $a)?></td><?php
				}
				?><td>&nbsp;</td></tr><?php	
				$header = false;				
			}		
			?><tr><td>
		<?php 
			if(Auth::user()->role == "Administrator"){			
			?>			
			<input type="button" name="Delete" value="Delete" onClick="document.location.href='?delete_items=<?=$item["vehicle_license_plate"]?>';"/>
			<input type="button" name="Edit" value="Edit" onClick="document.location.href='?edit_items=<?=$item["vehicle_license_plate"]?>';"/>
			<?php 
			}
			?>		
			</td><?php
			foreach ($item as $a => $b){
				if(str_contains($a, 'price')){
					?><td><?=$helper->rupiah($b)?></td><?php						
				}else{
					?><td><?=$b?></td><?php					
				}
			}
			?>	<td>
					<form id="update_the_returned_items" name="update_the_returned_items" method="POST" action="{{ route('update_the_returned_items') }}" >
						@csrf			
						<input type="hidden" id="vehicle_license_plate" name="vehicle_license_plate" value="<?=$item["vehicle_license_plate"]?>"></input>						
						<input type="submit" id="dikembalikan" name="dikembalikan" value="returned"></input>
					</form>
				</td>
			</tr><?php			
		}
		?>		
	  </table>
		<?php 
			for($a=1;$a<=$count_items;$a++){
				if($a==$current_items+1){
					echo $a."&nbsp;";										
				}else{
					echo "<a href='?searching_items=".$searching_items."&count_items=".$a."'>".$a."</a>&nbsp;";					
				}
			}
		?>
	  <br><a href="{{ route('create_items_pdf') }}">Download The Items Data For PDF</a><br>	
	  <a class="btn btn-info" href="{{ route('exportItems.excel') }}">Download The Items Data For Excel</a><br>		
		<?php
			if(Auth::user()->role == "Administrator"){
		?>	  
		<form action="{{ route('importItems.excel') }}"
			  method="POST"
			  enctype="multipart/form-data">
			  Upload Items:
			@csrf
			<input type="file" name="file"
				   class="form-control">
			<br>
			<button class="btn btn-success">
				  Import Items Data
			   </button>
		</form>			
			<?php
			}
			if(Auth::user()->role == "Administrator"){			
			?>
	<form action="{{ route('update_items') }}" method="POST">
		@csrf	
	  <?php 
		foreach ($items as $item) {
			$item = (array)$item;			
			foreach ($item as $a => $b){
				$edit_items_selected[$a] = empty($edit_items_selected[$a])?"":$edit_items_selected[$a];
				if(str_contains($a, 'available')){
				
				}else if(str_contains($a, 'name_of_items')){
					?>
						<?=str_replace('_', ' ', $a)?>
						<select id="name_of_items_select" name="name_of_items">
							<?php
							foreach($renteds as $rent){
								$rent = (array)$rent;
								?><option value="<?=$rent['name_of_items']?>"><?=$rent['name_of_items']?></option><?php	
							}
							?>
						</select><br>
						<script>
							$('#name_of_items_select').val('<?=$edit_items_selected[$a]?>');
						</script>	
					<?php					
				}else if(str_contains($a, 'price')){
					?><?=str_replace('_', ' ', $a)?><input id="<?=$a?>" name="<?=$a?>" type="text" class="number" value="<?=$edit_items_selected[$a]?>"></input><br><?php										
				}else if($a != "created_at" && $a != "updated_at"){
					?><?=str_replace('_', ' ', $a)?><input id="<?=$a?>" name="<?=$a?>" type="text" value="<?=$edit_items_selected[$a]?>"></input><br><?php					
				}
			}		
			break;				
		}
	  ?>
		<input id="submit" name="submit" type="submit" value="submit"></input><br>									  
	</form>
		<?php 
			}
		?>
	  Orders:<br>
	  <form action="{{route('home') }}" method="GET">
		<input type="text" id="searching_orders" name="searching_orders" value="<?=$searching_orders?>"/> 
		<input type="submit" id="searching_button" name="searching_button" value="Search"/>	 
	  </form>
	  <table border=1>
	  <?php
		$header = true;	  
		foreach ($orders as $order) {
			$order = (array)$order;
			if($header){
				?><tr><?php
				foreach ($order as $a => $b){
					?><td><?=str_replace('_', ' ', $a)?></td><?php
				}
				?></tr><?php	
				$header = false;				
			}		
			?><tr><?php
			foreach ($order as $a => $b){
				if($a == "total_of_order"){
					?><td><?=$helper->rupiah($b)?></td><?php					
				}else{
					?><td><?=$b?></td><?php					
				}
			}
			?>
			</tr><?php			
		}
		?>		
	  </table>	
		<?php 
			for($a=1;$a<=$count_orders;$a++){
				if($a==$current_orders+1){
					echo $a."&nbsp;";										
				}else{
					echo "<a href='?searching_orders=".$searching_orders."&count_orders=".$a."'>".$a."</a>&nbsp;";					
				}				
			}
		?>
	  <br><a href="{{ route('create_orders_pdf') }}">Download The Orders Data For PDF</a><br>	
	  <a class="btn btn-info" href="{{ route('exportOrders.excel') }}">Download The Orders Data For Excel</a><br>		
		<canvas id="canvas" height="280" width="600"></canvas>
		<a href="/logout">logout</a>
		</body>
</html>