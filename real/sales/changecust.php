<?php 
include('../constant.php');
include('../database.php'); 
	if ($_SESSION['user'] == '') {
		Header('Location: ../index.php');
	}

	$sid = $_GET['sid'];
?>
<html>
<head>
<title>Change Customer</title>
<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
<script language=javascript>
function validate() {
		with(document.frmsearch) {
			if (buyer_name.value== '' && salesman.value=='') {
				alert('Please fill something to search');
				return false;
			} else {
				return true;
			}				
		}
	}
</script>
</head>
<body>
<?php include('../menu.php'); ?>

<br>
<form method="POST" action="result.php?x=1&sidcust=<?php echo $sid; ?>" name=frmsearch onsubmit="return validate()" >
	<table border="0" cellspacing="1" cellpadding="1" width="100%">
	<tr>
		<td width="15%"></td>
		<td class="small"><b>Costumer Name:</b>&nbsp;&nbsp;
			<input type="text" name="buyer_name" class="tBox" size="40" maxlength=220 >
			<select class="tBox" name="salesman" style="width:150px;">
					<option value="" class="tBox">==Salesman==
					<?php
					$res = read_write("SELECT * FROM tb_salesman where salescode > 0 order by salesname ASC");
					while ($row = mysql_fetch_array($res)) {
						echo "<option value='".$row['salescode']."'"; 
						echo ">".$row['salesname'];
					}
					?></select>
			<input type="submit" value="Search" class="tBox">
		</td>
	</tr>
	</table>
</form>
</body>
</html>