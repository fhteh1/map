<?php
include("dbconnection.php");
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
$myusername=addslashes($_POST['uname']);
$mypassword=addslashes($_POST['psw']);
$sql="SELECT attribution FROM login WHERE id='$myusername' and password='$mypassword'";
$result=mysqli_query($db_conn, $sql);
$count=mysqli_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
//echo "<script>alert(\"fail to insert sql\");</script>";

//echo "<script>alert(".$count." );</script>";
//echo "<script>alert(\"$count\");</script>";
if($count==1)
  {
    $row= mysqli_fetch_array($result);
    //echo "<script>alert($sql);</script>";
    // session_register("myusername");

    //echo $row['attribution'];
    $_SESSION['login_user']=$myusername;
    if($row['attribution']=="manager"){             //만약 user 권한이 manager이라면
      header("location: welcome.php");              // welcome.php 페이지로 넘깁니다.
    }
    else{                                           //만약 user 권한이 manager가 아니라면(user)
      header("location: welcomeuser.php");          // welcomeuser.php 페이지로 넘깁니다.
    }
  }
else
  {
    $error="Your Login Name or Password is invalid";
    echo "<script>alert(\"$error\");</script>";
  }
}
?>

<!DOCTYPE html>
<link rel="stylesheet" href="login.css" type="text/css">
<!-- <form action="action_page.php"> -->
  <form method="post" action="">
    <!-- phpjs/dbconnection.php -->
  <div class="containers">
  <div>
    <h1>TEST LOGIN</h1>
  </div>

    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

  <p>
    <button type="submit">Login</button>
    <button type="button" onclick="window.open('join.php','win','width=550,height=700,toolbar=0,scrollbars=0,resizable=0')">Join</button>
  </p>


  <div class="container" style="background-color:#f1f1f1">

    <span class="pass"> <a href="#">Forgot password?</a></span>
  </div>
  </div>
</form>
