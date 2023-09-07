<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rent System</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>
  $(function() {
		$(".date_rent_start,.date_rent_end").datepicker();
		$(".date_rent_start,.date_rent_end").datepicker("option", "dateFormat", "yy-mm-dd");
		$('.number').keyup(function(e){
		  if (/\D/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/\D/g, '');
		  }
		});		
  });
  </script>
</head>
	@inject('helper', \App\Classes\CommonClass::class)
  <h4>Selamat Datang <b><?=$userName?></b>.</h4>
	  Rents:<br>
	  <table border=1>
	  <?php 
		$header = true;
		foreach ($rents as $rent) {
			$rent = (array)$rent;
			if($header){
				?><tr><td></td><?php
				foreach ($rent as $a => $b){
					?><td><?=str_replace('_', ' ', $a)." ".$helper->getRentsTableComment($a)?></td><?php
				}
				?><td>&nbsp;</td></tr><?php	
				$header = false;				
			}		
			?><tr><td><input type="button" name="Edit" value="Edit" onClick="document.location.href='?edit_rents=<?=$rent["name_of_items"]?>';"/></td><?php
			foreach ($rent as $a => $b){
				if(str_contains($a, 'price')){
					?><td><?=$helper->rupiah($b)?></td><?php					
				}else{
					?><td><?=$b?></td><?php										
				}
			}
			?><td>
				<form action="/update_orders" method="POST">
					@csrf
					<input type="hidden" id="orders_name_of_items" name="name_of_items" value="<?=$rent['name_of_items']?>">					
					<input type="hidden" id="orders_days_price" name="days_price" value="<?=$rent['days_price']?>">					
					<input type="hidden" id="orders_weeks_price" name="weeks_price" value="<?=$rent['weeks_price']?>">
					<input type="hidden" id="orders_months_price" name="months_price" value="<?=$rent['months_price']?>">										
					<input type="hidden" id="orders_years_price" name="years_price" value="<?=$rent['years_price']?>">															
					Rent:
					<input name="date_rent_start" class="date_rent_start" type="text" value=""></input>
					<input name="date_rent_end" class="date_rent_end" type="text" value=""></input>				
					<br>Address Buyer:
					<input name="address_buyer" type="text" value=""></input><br>												
					<br>Address Name:
					<input name="address_name" type="text" value=""></input><br>																
					<br>Address Phone:
					<input id="address_phone" name="address_phone" type="text" value=""></input><br>																				
					<input id="submit" name="submit" type="submit" value="Rent"></input>								
				</form>
			  </td>
			</tr><?php			
		}    
	  ?>
	  </table>
		<?php 
			for($a=1;$a<=$count_rents;$a++){
				if($a==$current_rents+1){
					echo $a."&nbsp;";										
				}else{
					echo "<a href='?count_rents=".$a."'>".$a."</a>&nbsp;";					
				}				
			}
		?>	  
	<form action="/update_rent" method="POST">
		@csrf	
	  <?php 
	  // old_name_of_items
		foreach ($rents as $rent) {
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
	  Items:<br>	  
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
			?><tr><td><input type="button" name="Edit" value="Edit" onClick="document.location.href='?edit_items=<?=$item["vehicle_license_plate"]?>';"/></td><?php
			foreach ($item as $a => $b){
				if(str_contains($a, 'price')){
					?><td><?=$helper->rupiah($b)?></td><?php						
				}else{
					?><td><?=$b?></td><?php					
				}
			}
			?>	<td>
					<form id="update_the_returned_items" name="update_the_returned_items" method="POST" action="/update_the_returned_items" >
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
					echo "<a href='?count_items=".$a."'>".$a."</a>&nbsp;";					
				}
			}
		?>	  
	<form action="/update_items" method="POST">
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
							foreach($rents as $rent){
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
	  Orders:<br>
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
					echo "<a href='?count_orders=".$a."'>".$a."</a>&nbsp;";					
				}				
			}
		?>
		<a href="/logout">logout</a>
		</body>
</html>