<?php
require_once("connection.php");
session_start();

if(isset($_POST['username']) && isset($_POST['password'])){
	$username=$_POST['username'];
	$password=$_POST['password'];

    // ORIGINAL QUERY
	$query="select * from users where username='".$username."' and password='".$password."'";
	$users_rs=mysqli_query($db, $query);


    // MODIFIED QUERY
    $query="select * from users where username= ? and password= ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $users_rs=mysqli_stmt_get_result($stmt);



	if(mysqli_num_rows($users_rs)>0)
	{
		$_SESSION['username']=$username;
		echo "<script>window.location='admin.php';</script>";
	}
	else
	{
		$message="Incorrect username/password found!";
		echo "<script type='text/javascript'>alert('$message');</script>";
	}
}

?>

<html>
<head>
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">

  <script language="javascript" type="text/javascript">
  function fun_val()
  	{
  		var l=document.loginsell.username.value;
  		if(l=="")
  		{
  			alert("Please Enter User name");
  			document.loginsell.username.focus;
  			return false;
  		}

  		var p=document.loginsell.password.value;
  		if(p=="")
  		{
  			alert("Please Enter Password");
  			document.loginsell.password.focus;
  			return false;
  		}
  	}
  </script>

<body>
<?php
require_once("menu.php");
?>
<p align="center" id="loginpage"><br><TR></tr>
<form name="loginform" action="login.php" method="post"><TR></tr>
  <table font-family = "Georgia" class = "table" width="400" height="178"  align="center" cellpadding="1"cellspacing="1" border="3">
    <tr>
      <td height="41" colspan="2" align="center"><h2><b>Login Form</b></h2></td>
    </tr>
    <tr>
      <td width="170" height="40"  align="center"><b>User Name</b></td>
      <td width="213">
         <input type="text" name="username" style="background" />
      </font></td>
    </tr>
    <tr>
      <td height="38" align="center"><b>Password</b></td>
      <td>
        <input type="password" name="password">
        </font></td>
    </tr>
    <br />
    <tr>
      <td height="48" colspan="2" align="center">
        <input type="submit"  value="Submit" name="ok" onClick="return fun_val();"/>
    </tr>
  </table>
</form>
</body>
</html>
