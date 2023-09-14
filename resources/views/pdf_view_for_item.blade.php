<html>
	<body>
	@inject('helper', \App\Classes\CommonClass::class)	
	  Item:<br>
	  <table border=1>
	  <?php
		$header = true;	  
		foreach ($items as $item) {
			$item = (array)$item;
			if($header){
				?><tr><?php
				foreach ($item as $a => $b){
					?><td><?=str_replace('_', ' ', $a)?></td><?php
				}
				?></tr><?php	
				$header = false;				
			}		
			?><tr><?php
			foreach ($item as $a => $b){
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