	@inject('helper', \App\Classes\CommonClass::class)
<?php 
	echo "Distance: ".$years_order." years, " .$months_order." months, ".$weeks_order." weeks, ".$days_order." days, Total Order: ".$helper->rupiah($total_of_order);				
?>