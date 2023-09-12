<html>
	<body>
	@inject('helper', \App\Classes\CommonClass::class)	
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
	</body>
</html>