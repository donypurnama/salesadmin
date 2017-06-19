<?php 
include ('database.php');
include ('constant.php');
$login = $_GET['login'];
$error = '';
$res   = NULL;

if($login == '1') {
	$username = $_POST['usr'];
	$password = $_POST['passwd'];
	$office = $_POST['office'];
	
	//CHECK PASSWORD BERTYPE MD5
	$password = md5($password);
	
		if (strpos($username," ") > 0 || strpos($password," ") > 0) {
			echo 'Invalid User Name or Password';
			exit();
		}
		
		if ($combine_db) {
			$res = read_write("SELECT * FROM tb_user WHERE user_name = '".$username."' and usercode like '".$office.".%'");
		} else {
			$res = read_write("SELECT * FROM tb_user WHERE user_name = '".$username."'");
		}
		
		$row = mysql_fetch_array($res);
		
		if ($password == $row['password'])
		{
			$_SESSION['groups'] = $row['groups'];
			$_SESSION['user'] = $row['usercode'];
			$_SESSION['realname'] = $row['realname'];
			switch ($row['homepage']) {
				case "sales":
				case "finance":
				case "admin":
					Header('Location: '.$row['homepage'].'/index.php');
					break;
				case "master":
					Header('Location: master/divisions.php');
					break;				
				default:
					Header('Location: sales/index.php');break;
			}			
		}
		else
		{
			$error = 'Invalid User Name or Password';
		}
		
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<title>Gapura Sales Administration System - Administration</title>
<link href="templates/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="templates/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript" src="templates/js/joomla.javascript.js"></script>
<script type="text/javascript" src="templates/css/mootools.js"></script>
<link href="templates/css/system.css" rel="stylesheet"  type="text/css" />
<link href="templates/css/login.css" rel="stylesheet" type="text/css" />


<!--[if IE 7]>
<link href="templates/khepri/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lte IE 6]>
<link href="templates/khepri/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="templates/css/rounded.css" />
<script language="javascript" type="text/javascript">
	function setFocus() {
		document.login.modlgn_username.select();
		document.login.modlgn_username.focus();
	}
	
	function dologin() {
		if (frm_validate()) {
			document.login.submit();
		}
	}
	function frm_validate() {
		
		with (document.login) {
			if (modlgn_username.value=='') {
				alert('Please insert username');
				return false;
			}

			if (modlgn_passwd.value=='') {
				alert('Please insert password');
				return false;
			}

		<?php if ($combine_db==true) { ?>
			if (office.value=='') {
				alert('Please choose office');
				return false;
			}
		<?php } ?>
		}
		return true;
	}	
</script>
</head>

<body onload="javascript:setFocus()">
	<div id="border-top" class="b_gapura">
		<div>
			<span class="version"> </span>	<div>
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
					<h1>Gapura Raya Sales Administration System Login</h1>
					<?php echo $error; ?>
						<div id="section-box">
							<div class="t">
								<div class="t">
									<div class="t"></div>
								</div>
							</div>
							<div class="m">
	<form action="index.php?login=1" method="post" name="login" id="form-login" style="clear: both;" onsubmit='return frm_validate()'>
	<p id="form-login-username">
		<label for="modlgn_username">Username</label>
		<input name="usr" id="modlgn_username" type="text" class="inputbox" size="15" />
	</p>

	<p id="form-login-password">
		<label for="modlgn_passwd">Password</label>
		<input name="passwd" id="modlgn_passwd" type="password" class="inputbox" size="15" />
	</p>
<?php if ($combine_db) { ?>
	<p id="form-login-password">
		<label for="modlgn_office">Office</label>
		<select name=office class="inputbox">
		<option value=''>
		<option value='01'>Jakarta
		<option value='00'>Pusat
		<option value='A'>Nasional
		</select>
	</p>	
<?php } ?>
	<div class="button_holder">
	<div class="button1">
		<div class="next">
			<!--<a onclick="login.submit();">
				Login</a>-->
			<a onclick="javascript:dologin();">
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
		Copyright © 2010 PT GAPURA RAYA
		
</div>
</body>
</html>
