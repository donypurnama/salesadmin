<?php 
include('../constant.php'); 
include('../database.php'); 

session_start();
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	if ($_GET['change'] == '1')
	{
		$error = '';
		$success = false;
		$old = $_POST['currentpass'];
		
		
		//BUAT PASSWORD LAMA MENJADI KODE MD5 
		$old = md5($old);
		
		$new = $_POST['newpass'];
		$repnew = $_POST['repeatpass'];
		$res = read_write("select * from tb_user where usercode = '".$_SESSION['user']."' and password = '".$old."'");
		
		
		//BUAT PASSWORD BARU MENJADI KODE MD5 
		$new = md5($new);
		$repnew = md5($repnew);
		
		
		
		if (mysql_num_rows($res) == 0) {
			$error = "The current password you typed is invalid";	
		} else {
			if ($new != $repnew) {
				$error = "The new password you typed do not match";	
			} else {
				if (strlen($new) < 6) {
					$error = "The password must be more than 6 characters";
				} else {
					$res = read_write("update tb_user set password = '".$new."' where usercode = '".$_SESSION['user']."'");
					$error = "Your Password has been changed";
					$success = true;
					Header("Location: ../admin/index.php");
					
				}
			}
		}
	}
?>
<html>
<head>
<title>Change Password</title>
<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
<script language="javascript">
	function validate() {
		if (document.forms[0].newpass.value == "") {
			alert("Please fill up your current password ");
			return false;
		} else {
			return true;
		}
	}
	
	
		</script>
</head>
<body>
	<?php include('../menu.php'); ?><br>
	<form onSubmit="return validate()" action="pass.php?change=1" method="POST">
	<table border="0" cellpadding="1" cellspacing="3" width="30%" align=center>
	<tr>
		<td colspan=2><div class="header icon-48-inbox"><font class="big"><b>Change Password</b></font></div></td>
	</tr>
	</table>
	
	
	
	<table border='0' cellspacing='1' cellpadding='3' align='center' width='500'>
	
	<tr>
		<th colspan=2 class="small"><font color=#ff0000><?php echo $error; ?></font></th>
	</tr>
	<tr>
		<td width="50%" class="small" align="right" width='300'>Type your current Password:</td>
		<td width="50%"><input type="password" name="currentpass" class="tBox" size=20 maxlength=20></td>
	</tr>
	<tr>
		<td class="small" align="right" width='300'>Type a new password:</td>
		<td><input type="password" name="newpass" class="tBox" size=20 maxlength=20></td>
	</tr>
	<tr>
		<td class="small" align="right" width='300'>Type the new password again to confirm:</td>
		<td><input type="password" name="repeatpass" class="tBox" size=20 maxlength=20></td>						
	</tr>
	<tr>
		<td colspan=2 align="center">
			<input type="submit" value="Submit" class="tBox">
			<input type="reset" value="Reset" class="tBox">
			<input type='button' value='Exit' class=tBox onclick='javascript:location.href="../admin/index.php"'>
		</td>
	</tr>
	<tr><td colspan='2' height='10'>&nbsp;<br></td></tr>
	</table>
	</form>
		
	</body>
</html>