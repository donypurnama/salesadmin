<center>
<?php if($_SESSION['groups'] == 'administrator') 
{?>	
	<b class="big">Product</b> | 
	<a class=smalllink href='salesman.php' >Salesman</a> |  
	<a class=smalllink href='commission.php' >Commission</a>	
<?} else {?> 

<a class=smalllink href='salesman.php' >Salesman</a> |  
<? } ?>
</center><br>