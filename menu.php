<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" id="minwidth" >
<head>
<link href="../templates/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript" src="../templates/js/joomla.javascript.js"></script>
<script type="text/javascript" src="../templates/css/mootools.js"></script>
<script type="text/javascript">
window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false,alwaysHide: true}); });
</script>
<link href="../templates/css/system.css" rel="stylesheet"  type="text/css" />
<link href="../templates/css/template.css" rel="stylesheet" type="text/css" />
<link href="templates/css/ie7.css" rel="stylesheet" type="text/css" />
<link href="templates/css/ie6.css" rel="stylesheet" type="text/css" />
 	<link rel="stylesheet" type="text/css" href="../templates/css/rounded.css" />
 	<script type="text/javascript" src="../templates/js/menu.js"></script>
	<script type="text/javascript" src="../templates/js/index.js"></script>
</head>
<body id="minwidth-body">
	<div id="border-top" class="b_gapura">
		<div>
			<span class="version"> GAPURA RAYA SALES ADMINISTRATION SYSTEM</span>	<div>
			</div>
		</div>
	</div>
<div id="header-box">
<div id="module-status">
<span class="loggedin-users">
<?php echo "Welcome <b>".$_SESSION['realname']."</b><br>"; ?> 
</span>
<span class="logout"><a href="../logout.php">Logout</a></span>
</div>
<div id="module-menu">
	<ul id="menu" >
		<li class="node"><a>Faktur</a>
			<ul>
                <li><a class="icon-16-menumgr" href="../sales/">Faktur Baru</a></li>
                <li class="separator"><span></span></li>
                <li><a class="icon-16-media" href="../sales/advancedsearch.php">Pencarian & Laporan</a></li>
               
				<li class="node"><a class="icon-16-stats">Rekapitulasi</a>
				
						<ul>
						<li><a class="icon-16-menumgr" href="../sales/rekapitulasi.php?rekap=1">Harian</a></li>
						<li><a class="icon-16-menumgr" href="../sales/rekapitulasi.php?rekap=2">Bulanan</a></li>
						<li><a class="icon-16-menumgr" href="../sales/rekapitulasi.php?rekap=3">Tahunan</a></li>
						</ul>
				
				</li>
				<li class="separator"><span></span></li>
			</ul>
		</li>
		<li class="node"><a>Pembayaran</a>
		<ul>
        		<li><a class="icon-16-themes"  href="../finance/">TTU Baru & Pencarian </a></li>
                <li class="separator"><span></span></li>
                <li><a class="icon-16-menu" href="../finance/outstanding.php">Oustanding</a></li>
                <li class="separator"><span></span></li>
                <li><a class="icon-16-trash" href="../finance/commission.php">Comission</a></li>
                <li class="separator"><span></span></li>                        
		</ul>
		</li>
		<li class="node"><a>Master</a>		
		<ul>
				<li><a class="icon-16-article" href="../master/divisions.php">Product</a></li>
				<li class="separator"><span></span></li>
				<li><a class="icon-16-trash" href="../master/salesman.php">Salesman</a></li>
				<li class="separator"><span></span></li>
				<li><a class="icon-16-section" href="../master/commission.php">Commision</a></li>
				<li class="separator"><span></span></li>
		</ul>
		</li>
		<li class="node"><a>System</a>
        <ul>
			<?php if($_SESSION['groups'] == "root" || $_SESSION['groups'] == "administrator" || $_SESSION['groups'] == "personnel")  {  ?>
				<li><a class="icon-16-user" href="../admin/index.php">User Manager</a></li>
				<li class="separator"><span></span></li> <? }  ?>
				<li><a class="icon-16-messages" href="../backup/pass.php">Change Password</a></li>
				<li class="separator"><span></span></li>
				<li><a class="icon-16-massmail" href="../backup/index.php">Back Up</a></li>
				<li class="separator"><span></span></li>
				<li><a class="icon-16-logout" href="../logout.php">Logout</a></li>
			
        </ul>
        </li>
      	<li class="node"><a>Help</a>
        <ul>
        <li><a class="icon-16-help" href="../admin/help.php">Sales Administration! Help</a></li>
       
        </ul>
        </li>
        </ul>
		<div id="content-box">
		<div class="padding">
        &nbsp;<br />
        </div>
		</div>
</div>
</div>
</body>
</html>