<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" id="minwidth" >
<head>
<link href="/joomla/administrator/templates/khepri/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript" src="/joomla/includes/js/joomla.javascript.js"></script>
<script type="text/javascript" src="templates/css/mootools.js"></script>
<script type="text/javascript">
window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false,alwaysHide: true}); });
</script>
<link href="templates/css/system.css" rel="stylesheet"  type="text/css" />
<link href="templates/css/template.css" rel="stylesheet" type="text/css" />
 
<!--[if IE 7]>
<link href="templates/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
 
<!--[if lte IE 6]>
<link href="templates/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
 
	<link rel="stylesheet" type="text/css" href="templates/css/rounded.css" />
 
	<script type="text/javascript" src="templates/js/menu.js"></script>
	<script type="text/javascript" src="templates/js/index.js"></script>
 
</head>
<body id="minwidth-body">
	<div id="border-top" class="h_green">
		<div>
			<div>
				<span class="version"> GAPURA RAYA SALES ADMINISTRATION SYSTEM</span>
				
			</div>
		</div>
	</div>
<div id="header-box">
<div id="module-status">
<span class="logout"><a href="logout.php">Logout</a></span>
</div>


<div id="module-menu">
	<ul id="menu" >
		<li class="node"><a>Faktur</a>
			<ul>
                <li><a class="icon-16-cpanel" href="<?php echo DOMAIN_NAME; ?>sales/">New Sales Order</a></li>
                <li class="separator"><span></span></li>
                <li><a class="icon-16-user" href="<?php echo DOMAIN_NAME; ?>sales/advancedsearch.php">Advanced Search</a></li>
                <li class="separator"><span></span></li>
			</ul>
		</li>
		<li class="node"><a>Pembayaran</a>
		<ul>
        		<li><a class="icon-16-menu" href="<?php echo DOMAIN_NAME; ?>finance/">Find Invoice/TTU </a></li>
                <li class="separator"><span></span></li>
                <li><a class="icon-16-menu" href="<?php echo DOMAIN_NAME; ?>finance/outstanding.php">Oustanding</a></li>
                <li class="separator"><span></span></li>
                <li><a class="icon-16-trash" href="<?php echo DOMAIN_NAME; ?>finance/commission.php">Comission</a></li>
                <li class="separator"><span></span></li>                        
		</ul>
		</li>
		<li class="node"><a>Master</a>
		<ul>
            <li><a class="icon-16-article" href="<?php echo DOMAIN_NAME; ?>master/divisions.php">Product</a></li>
	        <li class="separator"><span></span></li>
            <li><a class="icon-16-trash" href="<?php echo DOMAIN_NAME; ?>master/salesman.php">Salesman</a></li>
            <li class="separator"><span></span></li>
            <li><a class="icon-16-section" href="<?php echo DOMAIN_NAME; ?>master/commission.php">Commision</a></li>
			<li class="separator"><span></span></li>         
        </ul>
        </li>


        <li class="node"><a>Tools</a>
        <ul>
        <li><a class="icon-16-user" href="<?php echo DOMAIN_NAME; ?>admin/index.php">User Manager</a></li>
        <li class="separator"><span></span></li>
        <li><a class="icon-16-messages" href="<?php echo DOMAIN_NAME; ?>pass.php">Change Password</a></li>
        <li class="separator"><span></span></li>
        <li><a class="icon-16-massmail" href="<?php echo DOMAIN_NAME; ?>backup/index.php">Back Up</a></li>
        <li class="separator"><span></span></li>
        <li><a class="icon-16-logout" href="<?php echo DOMAIN_NAME; ?>logout.php">Logout</a></li>
        </ul>
        </li>
      	<li class="node"><a>Help</a>
        <ul>
        <li><a class="icon-16-help" href="#">Sales Administration! Help</a></li>
        <li><a class="icon-16-info" href="#">System Info</a></li>
        </ul>
        </li>
        </ul>
		<div id="content-box">
		<div class="padding">
        &nbsp;<br />
        </div>
		</div>
 
<table width="100%" border=0 cellpadding=2 cellspacing=0>
<tr>
	<td class="small" align="right">
	<?php
	$username = $_SESSION['realname'];
	$groups = $_SESSION['groups'];
	echo "Welcome <b>".$groups."</b><br>";
	
	?></td>
</tr>
	<tr bgcolor=#3300ff>
		<th width="100%" class="small">
			<?php 
			if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator') {
				?>
				<a href="<?php echo DOMAIN_NAME; ?>sales/" class="menu">Sales</a> |
				<a href="<?php echo DOMAIN_NAME; ?>finance/" class="menu">Finance</a> |
				<a href="<?php echo DOMAIN_NAME; ?>master/divisions.php" class="menu">Master</a> |
				<a href="<?php echo DOMAIN_NAME; ?>admin/index.php" class="menu">Users</a> | 
				<a href="<?php echo DOMAIN_NAME; ?>pass.php" class="menu">Change Password</a> |
				<a href="<?php echo DOMAIN_NAME; ?>backup/index.php" class="menu">Backup</a> |
				<a href="<?php echo DOMAIN_NAME; ?>logout.php" class="menu">Log Out</a>
				<?php
			} else if ($_SESSION['groups'] == 'finance') { 
			
			 ?>
				<a href="<?php echo DOMAIN_NAME; ?>sales/" class="menu">Sales</a> |
				<a href="<?php echo DOMAIN_NAME; ?>finance/" class="menu">Finance</a> |
				<a href="<?php echo DOMAIN_NAME; ?>master/salesman.php" class="menu">Master</a> |
				<a href="<?php echo DOMAIN_NAME; ?>pass.php" class="menu">Change Password</a> |
				<a href="<?php echo DOMAIN_NAME; ?>backup/index.php" class="menu">Backup</a> |
				<a href="<?php echo DOMAIN_NAME; ?>logout.php" class="menu">Log Out</a>
			<? 
			
			} else  { ?>
				<a href="<?php echo DOMAzIN_NAME; ?>sales/" class="menu">Sales</a> |				
				<a href="<?php echo DOMAIN_NAME; ?>master/salesman.php" class="menu">Master</a> |
				<a href="<?php echo DOMAIN_NAME; ?>pass.php" class="menu">Change Password</a> |
				<a href="<?php echo DOMAIN_NAME; ?>backup/index.php" class="menu">Backup</a> |
				<a href="<?php echo DOMAIN_NAME; ?>logout.php" class="menu">Log Out</a>
			<? } ?>
		</th>
	</tr>
</table>
</div>
</div>
</body>
</html>