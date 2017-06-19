<?php
	session_start();
	$_SESSION['user'] = '';
	session_destroy();
	
	Header("Location: index.php");		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="joomla, Joomla" />
<meta name="description" content="Joomla! - the dynamic portal engine and content management system" />
<meta name="generator" content="Joomla! 1.5 - Open Source Content Management" />
<title>Gapura Sales Administration System - Administration</title>
<link href="/joomla/administrator/templates/khepri/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript" src="templates/css/mootools.js"></script>
<link href="templates/css/system.css" rel="stylesheet"  type="text/css" />
<link href="templates/css/login.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="style.css">

<!--[if IE 7]>
<link href="templates/khepri/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lte IE 6]>
<link href="templates/khepri/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->

	<link rel="stylesheet" type="text/css" href="templates/css/rounded.css" />
	
	<body onload="javascript:setFocus()">
	<div id="border-top" class="h_green">
		<div>
			<div>
				<span class="title">Gapura Raya</span>
			</div>
		</div>
	</div>
	<div id="content-box">
		<div class="padding">
			<div id="element-box" class="login">
				<div class="t">
					<div class="t">
						<div class="t"></div>
					</div>
				</div>
				<div class="m">

					<h1>Gapura Sales Administration System Login</h1>
					
					<dl id="system-message">
						<dt class="error">Error</dt>
							<dd class="error message fade">
								<ul>
								<li>  You have logged out of the system. </li>
										<li>You need to <a href="index.php">log in</a> to be able to use this system.</li>
								</ul>
							</dd>
							</dl>

               
					
							<div id="section-box">
			<div class="t">
				<div class="t">
					<div class="t"></div>
		 		</div>
	 		</div>
			<div class="m">
				<form action="index.php?login=1" method="post" name="login" id="form-login" style="clear: both;">
	<p id="form-login-username">
		<label for="modlgn_username">Username</label>
		<input name="usr" id="modlgn_username" type="text" class="inputbox" size="15" />
	</p>

	<p id="form-login-password">
		<label for="modlgn_passwd">Password</label>
		<input name="passwd" id="modlgn_passwd" type="password" class="inputbox" size="15" />
	</p>
		
	<div class="button_holder">
	<div class="button1">
		<div class="next">
			<a onclick="login.submit();">
				Login</a>

		</div>
	</div>
	</div>
	<div class="clr"></div>
	<input type="submit" style="border: 0; padding: 0; margin: 0; width: 0px; height: 0px;" value="Login" />
	<input type="hidden" name="option" value="com_login" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="b570fd4dc7e2287b0d9f2addd6856913" value="1" /></form>
				<div class="clr"></div>
			</div>
			<div class="b">
				<div class="b">
		 			<div class="b"></div>
				</div>
			</div>
		</div>
		
					<p>Use a valid username and password to gain access to the System.</p>
					
					<div id="lock"></div>
					<div class="clr"></div>
				</div>
				<div class="b">
					<div class="b">
						<div class="b"></div>
					</div>
				</div>
			</div>
			<noscript>
				Warning! JavaScript must be enabled for proper operation of the Administrator back-end.			</noscript>
			<div class="clr"></div>
		</div>
	</div>
	<div id="border-bottom"><div><div></div></div>
</div>
<div id="footer">
	<p class="copyright">
		Gapura Sales Administration System @2009 
		
</div>
</body>
</html>

	
	<body>
		<font class="small" align="center">
			You have logged out of the system. You need to <a href="index.php">log in</a> to be able to use this system.
		</font>
	</body>
</html>