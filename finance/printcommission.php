<?php
session_start();
?>
<html>
<head>

<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
</head>
<body>
<?php
echo "<center>".$_SESSION['commhdr']."</center>";
echo $_SESSION['commstr']; 

echo "<table width=95% align=center>";
echo "<tr><td height=10></td></tr><tr class=smallmed><td >Dibuat Oleh, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Disetujui Oleh,</td></tr></table>";
?>

</body>
</html>
