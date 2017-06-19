<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>

<?php
	
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$search = $_GET['search'];
	$divisionid = $_GET['divisionid'];
	
if($_SESSION['groups'] == "root" || $_SESSION['groups'] == "administrator" || $_SESSION['groups'] == "personnel") 
{

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
			
			
		</script>
		
	</head>
	<body >
		<?php include('../menu.php'); ?><br>
		
		
	
		<table border=0 cellspacing=0 cellpadding=3 align=center width=600>
		<tr><td colspan=4><div class="header icon-48-user"><font class="big"><b>User Manager </b></font></div></td></tr>
		<tr valign=top bgcolor=#e5ebf9 class=header>
			<td ><b>Real Name</b></td>
			<td><b>User Name</b></td>
			<td ><b>Groups</b></td>
			<td ><b>Position</b></td>
		</tr>
			</tr>
		<?php
		$res = read_write("SELECT * FROM tb_user where groups != 'root' and groups != 'taxhead' 
							AND
								tb_user.usercode like '".$branch."%'
							ORDER BY 
								usercode ASC");
		while ($row = mysql_fetch_array($res)) {
		
			echo "<tr class=small>";
			echo "<td><a class=smalllink href='adduser.php?act=2&usercode=".$row['usercode']."'>".$row['realname']."</a></td>";
			echo "<td>".$row['user_name']."</td>";
			echo "<td>".$row['groups']."</td>";
			echo "<td>".$row['position']."</td>";
				
			
			echo "</tr>";
			
		}
		?>
	<tr><td height=10></td></tr>
	<tr class=small>
	<td colspan=7><a class=smalllink href='adduser.php?act=1'>Add New User</a></td></tr>
	</table>
	</body>
</html>

<?php
	} else {
	Header('Location: '.$row['homepage'].'../master/salesman.php');
	}
?>
	
