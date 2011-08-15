<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
<title>Zaloguj</title>
<meta name="generator" content="VIM">
<meta name="author" content="Przemyslaw Koltermann">
<meta name="date" content="">
<meta name="copyright" content="">
<meta name="keywords" content="">
<meta name="description" content="">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
</head>
<body background="images/tile11.jpg">
<br><br>
<form action="<?php echo $location; ?>?tryb=" method="post">
<center>
<table>
	<tr>
		<td><font style="font-family:Arial; font-size:14px; color:white;">Login</font></td>
		<td><input type="text" name="login" id="login"></td>
	</tr>
	<tr>
		<td><font style="font-family:Arial; font-size:14px; color:white;">Hasło</font></td>
		<td><input type="password" name="password"></td>
	</tr>
</table>
<input style="background:black; color:white;" type="submit" value="Zaloguj">
</center>
</form>
<script type="text/javascript">
var login = document.getElementById('login');
login.focus();
</script>
</body>
</html>
