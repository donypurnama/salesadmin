<?php 
include('../constant.php');
include('../database.php'); 
	
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$usercode = $_GET['usercode'];
	$act 	= $_GET['act'];
	$update	= $_GET['update'];
	$remove = $_GET['remove'];

	
if($_SESSION['groups'] == "root" || $_SESSION['groups'] == "administrator" || $_SESSION['groups'] == "personnel") 
{
	
		
		if($remove==1) {
			
		
			$newpass = $_POST['newpass'];
			$repeatpass = $_POST['repeatpass'];
			
			$len = strlen($newpass);
			
			
			
			//$len = strlen($newpass);	
				if($newpass != $repeatpass) {
					
					$message = "The password you typed do not match";			
				} else {
					if ($len <= 6) {
						$message = "The password must be more than 6 characters";
					} else {
					
					$newpass = md5($newpass);
					$repeatpass = md5($repeatpass);
					
										
					
					
					
					$res = read_write("update tb_user 
										set 
											password = '".$newpass."' 
										where 
											usercode = '".$usercode."'");
						Header("Location: adduser.php?act=2&usercode=".$usercode."");
					
					}
					
			}
				
		}
	
	$res = read_write("SELECT * FROM tb_user WHERE usercode='".$usercode."'");
			$row = mysql_fetch_array($res);
			$usercode = $row['usercode'];
			
		
		
	?>
	<html>
		<head>
			<title>Administration</title>
				<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
			<script language="javascript">
				function validate() {
					if(document.forms[0].user_name.value == "") {
						alert ("Please Fill Up the User Name");
						return false;
					} else if(document.forms[0].password.value == "") {
						alert ("Please Fill Up the Password");
						return false;
					} else if(document.forms[0].selgroups.value == "") {
						alert ("Please Select the Groups ");
						return false;
					} else {
						return true;
					}
						
				}
				
				function deluser(usercode) {
					if (confirm("Are you sure to delete this user?")) {
						if (confirm("Are you really sure?")) {
							location.href='adduser.php?act=3&update=1&usercode='+usercode;

						}
					}
				}
			</script>
			
		</head>
	<body >
	<?php include('../menu.php'); ?><br>
	<table border="0" cellpadding="1" cellspacing="3" width="30%" align=center>
	<tr>
		<td colspan='2'>
		<div class="header icon-48-user">
		
		<font class="big">
		<a href='adduser.php?act=2&usercode=<? echo $usercode; ?>' class='smalllink'><b>User Manager </b></a>| Remove Password</font></div>	
	
		</div>
		</td>
	</tr>
	</table>
	
	<form name='frmremove' action='removeuserpass.php?remove=1&usercode=<? echo $usercode;?>' method='POST'>
	<table border='0' cellspacing='1' cellpadding='3' align='center' width='600'>
	<tr><td colspan='2' align='center' class='small'><font color='#f000000'><b><? echo $message; ?></b></font></td></tr>
	<tr><td colspan='2'></td></tr>
	<tr>
		<td class='small' align='right' width=250><font color='#ff0000'>Type a new password: </font></td>
		<td><input type='password' name='newpass' class='tBox' size='20' maxlength='20'> 
		
		</td>
	</tr>
	<tr>
		<td class='small' align='right' width=250><font color='#ff0000'>Type the new password again to confirm: </font></td>
		<td><input type='password' name='repeatpass' class='tBox' size='20' maxlength='20'></td>
	</tr>
	<tr><td colspan='2'></td></tr>
	<tr><td colspan='2' align='center'>
		<input type='submit' value='Save' class='tBox'>
		<input type='reset' value='Reset' class='tBox'>
	</td></tr>
	</table>
	</form>
	
	
	
	

</body>
</html> 
<?
	} else {
		Header('Location: '.$row['homepage'].'../master/salesman.php');
	}
?>
	
