<html>
	<body>
	@inject('helper', \App\Classes\CommonClass::class)	
	  Rents:<br>
	  <table border=1>
	  <?php
		$header = true;	  
		foreach ($rents as $rent) {
			$rent = (array)$rent;
			if($header){
				?><tr><?php
				foreach ($rent as $a => $b){
					?><td><?=str_replace('_', ' ', $a)?></td><?php
				}
				?></tr><?php	
				$header = false;				
			}		
			?><tr><?php
			foreach ($rent as $a => $b){
				if(str_contains($a, 'price')){
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