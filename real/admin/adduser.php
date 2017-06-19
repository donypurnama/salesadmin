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
	if($update==1) {
		
		$user_name = $_POST['user_name'];	
		$groups    = $_POST['groups'];
		$realname  = $_POST['realname'];
		$position  = $_POST['position'];
		$pwd = $_POST['pwd'];
		$homepage  = $_POST['homepage'];
		
		$user_name = strtolower($user_name);
		if($act==1) {
		
		/*--------------------AUTO NUMBERING FOR USERCODE-----------------------------*/
				$rstemp = read_write("SELECT REPLACE (usercode, '".$branch.".','') AS nbr
									FROM `tb_user` where usercode like '".$branch.".%' 
									ORDER BY usercode DESC LIMIT 1");
						$rowtemp = mysql_fetch_array($rstemp);
						$codetemp = (int) $rowtemp	['nbr'];
						$codetemp++;
						
						if(strlen($codetemp)==3) {
							$codetemp = '0'.$codetemp;
						} elseif (strlen($codetemp)==2) {
							$codetemp = '00'.$codetemp;
						} elseif (strlen($codetemp)==1) {
							$codetemp = '000'.$codetemp;
						} 
						
						$usercode = $branch.".".$codetemp;
			
			//make password to md5 code
			$pwd = md5($pwd);
			
			read_write("INSERT INTO tb_user (
							usercode,
							user_name,
							password,
							groups,
							homepage,
							realname,
							position,
							last_update,
							sentstatus)
						VALUES (
						'".$usercode."',	
						'".$user_name."',
						'".$pwd."',
						'".$groups."',
						'".$homepage."',
						'".$realname."',
						'".$position."',
						'".$today."',
						0)	
						");
		Header("Location: index.php");				

			
		/*------------------------------------------------------------------------------------------------------------------------------*/
		
		} elseif($act==2) {
			read_write("UPDATE tb_user SET 
							groups = '".$groups."',
							homepage = '".$homepage."', 
							realname = '".$realname."',
							position = '".$position."',
							last_update ='".$today."',
							sentstatus = 0
						WHERE 
							usercode = '".$usercode."'");
							
			
			$message = "Database has been successfully updated ...";
		} elseif ($act==3) {
				/*********HITUNG JUMLAH**************/
				$res = read_write("select count(usercode) as cnt from tb_invoice where usercode='".$usercode."'");
			    $jum = mysql_fetch_row($res);
				/*----------------------------------------------------------------*/
				if($jum[0] == 0) {
					if ($branch <> '01' && $branch <> '00' && $branch <> '') {
					/***** INSERT BUYERCODE TO TBDELBUYER  *******/ 
					$res = read_write("INSERT INTO tb_user (usercode, last_update, sentstatus) VALUES ('".$usercode."', '".$today."',0)");
					/*----------------------------------------------------*/
					}
					read_write("DELETE FROM tb_user WHERE usercode='".$usercode."'");
					Header("Location: index.php");				
				} else {
					Header("Location: error.php");
				}
			
		}
	}
	
	
	
		if($usercode <> "") {
			$res = read_write("SELECT * FROM tb_user WHERE usercode='".$usercode."'");
			$row = mysql_fetch_array($res);
			$usercode = $row['usercode'];
			$user_name = $row['user_name'];
			$realname = $row['realname'];
			$pwd = $row['password'];
			$groups = $row['groups'];
			$homepage = $row['homepage'];
			$position = $row['position'];
			
		}
		
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
	
	<form name="frmadmin" action="adduser.php?update=1&act=<?php echo $act; ?>&usercode=<? echo $usercode; ?>" method="POST" onSubmit="return validate()" >
	<table border="0" cellpadding="1" cellspacing="3" width="30%" align=center>
	<tr>
		<td colspan='2'>
		<div class="header icon-48-user">
	<?php 
	if ($act == 1) { ?>
		<font class="big"><b>User Manager </b></font></div>
	<? } else  {?>

		<font class="big"><b>User Manager </b> | <a href='removeuserpass.php?usercode=<? echo $usercode;?>' class='smalllink'>Remove Password</a></font></div>
	<? } 
	
		
	?>
		</div>
		</td>
	</tr>
	
	
	<? if ($act <> 4) { ?>
	
	
	<tr class="small">
		<td colspan=2 align=center><font color=red><b><?php echo $message; ?></b></font></td>
	</tr>
	<tr>
		<th colspan=2 class="small"><font color=#ff0000><?php echo $error ?></font></th>
	</tr>
	<?php if($usercode <> "") {
	echo "<tr>
		<td class='small' align='right' width='30%'><font color='#ff0000'>User Code</td>
		<td class='small' >$usercode</td></tr>";
	} ?>
	<tr class="small">
		<td class="small" align='right' ><font color="#ff0000">User Name</td>
		<td>
		<?php if($user_name <> "") {
				echo "$user_name";
		} else { ?>
			<input type="text" name="user_name" class="tBox" size="20" maxlength="20" value="<?php echo $user_name; ?>"></td>
		<? } ?>
	</tr>
	
	<? if ($act <> 2) { ?>
	<tr class="small">
		<td width="50%" class="small"align='right'><font color="#ff0000">Password</td>
		<td width="50%">
		<input type="password" name="pwd" class="tBox" size="30" maxlength="20" value="<?php echo $pwd;?>"></td>
	</tr>
	<? } ?>
			
			<!----------------------------------------------------------------->
			<tr class="small">
				<td width="50%" class="small"align='right'><font color="#ff0000">Groups</td>
				<td width="50%">
				<select name="groups" class="tBox" >
					<option value="">--
					<option value="administrator" <?php if ($groups == 'administrator') { ?>selected<?php } ?>>Administrator
					<option value="personnel" 	<?php if ($groups == 'personnel') { ?>selected<?php } ?>>Personnel
					<option value="sales" 		<?php if ($groups == 'sales') { ?>selected<?php } ?>>Sales
					<option value="finance" 	<?php if ($groups == 'finance') { ?>selected<?php } ?>>Finance
				</select>			
			</tr>	
			<tr class="small">
				<td width="50%" class="small" align='right'><font color="#ff0000">Start Page</td>
				<td width="50%">
				<select name="homepage" class="tBox" >
					<option value="">--
					<option value="master" 	<?php if ($homepage == 'master') { ?>selected<?php } ?>>Master (Admin & Personnel only)
					<option value="admin" 	<?php if ($homepage == 'admin') { ?>selected<?php } ?>>Users (Admin & Personnel only)
					<option value="sales" 	<?php if ($homepage == 'sales') { ?>selected<?php } ?>>Sales
					<option value="finance" <?php if ($homepage == 'finance') { ?>selected<?php } ?>>Finance
				</select>			
			</tr>	
			<tr>
				<td width="50%" class="small" align='right'  ><font color="#ff0000">Real Name</td>
				<td width="50%"><input type="text" name="realname" class="tBox" size="30" maxlength="30" value="<?php echo $realname; ?>"></td>
			</tr>
			<tr>
				<td width="50%" class="small" align='right' ><font color="#ff0000">Position</td>
				<td width="50%"><input type="text" name="position" class="tBox" size="30" maxlength="100" value="<?php echo $position; ?>"></td>
			</tr>
				<tr><td colspan="2"></td></tr>
			<tr>
				<td colspan=2 align="center" >
				<input type="submit" value="Save" class="tBox">&nbsp;
				<input type="button" value="Delete" class="tBox" onclick="deluser('<?php echo $usercode; ?>')">&nbsp;
				<input type=button value='Exit' class=tBox onclick='javascript:location.href="index.php"'>
			</td>
			</form>
		
<?php
	} else {

	echo "<form name='frmremove' action='adduser.php?act=".$act."&remove=1&usercode=".$usercode." method='POST'>";
	echo "act = ".$act;
	echo "usercode = ".$usercode;
	
	echo "<table border='0' cellspacing='1' cellpadding='3' align='center' width='600'>";
	echo "<tr><td colspan='2' align='center' class='small'><font color='#f000000'><b>".$message."</b></font></td></tr>";
	echo "<tr><td colspan='2'></td></tr>"; 
	echo "<tr><td class='small' align='right' width=250><font color='#ff0000'>Type a new password: </font></td>
				<td><input type='password' name='newpass' class='tBox' size='20' maxlength='20'></td></tr>";
	echo "<tr><td class='small' align='right' width=250><font color='#ff0000'>Type the new password again to confirm: </font></td>
				<td><input type='password' name='repeatpass' class='tBox' size='20' maxlength='20'></td></tr>";
	echo "<tr><td colspan='2'></td></tr>";
	echo "<tr><td colspan='2' align='center'>
		<input type='submit' value='Save' class='tBox'>
		<input type='reset' value='Reset' class='tBox'>
			
	";
	
	echo "</td></tr>";
	echo "</table></form>";
	}
?>
</body>
</html> 
<?
	} else {
	Header('Location: '.$row['homepage'].'../master/salesman.php');
	}
?>
	
