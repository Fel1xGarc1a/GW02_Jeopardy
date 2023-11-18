<?php 
	session_start(); 
	$id= $_GET["id"];
	$pw= $_GET["pw"];
	
	setcookie("id", $id, time() + (86400 * 30), "/");
	setcookie("pw", $pw, time() + (86400 * 30), "/");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Login Script Without Using Database</title>
<link rel ="stylesheet" href="styles.css">
<style>
  body {
      background-image: url('jeopardy_background.png');
      background-size: cover; /* Adjust as needed */
      /* Other background properties like background-repeat, background-position, etc. can be added here */
      color: yellow;
  }
</style>
</head>
<body>
<form autocomplete="off" action="login_check.php" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
    <?php if(isset($msg)){?>
    <tr>
      <td colspan="2" align="center" valign="top"><?php echo $msg;?></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="2" align="center" valign="top"><h3>Login</h3></td>
    </tr>
    <tr>
      <td align="right" valign="top">Username</td>
      <td><input role="presentation" autocomplete="off" name="Username" type="text" class="Input" placeholder="ID" ></td>
    </tr>
    <tr>
      <td align="right">Password</td>
      <td><input role="presentation" autocomplete="off" name="Password" type="password" placeholder="password" class="Input" ></td>
    </tr>
    <tr>
      <td align="right"><input name="Submit" type="submit" value="Login" class="Button1"></td>
	  <td align="center"><a href="register.html"><input name="Submit" type="button" value="Register" class="Button1"></a></td>
     
    </tr>
  </table>
</form>
</body>
</html>
