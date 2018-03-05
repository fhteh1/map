<?php
  include("dbconnection.php");

  // $id=addslashes($_POST['id']);
  // $password=addslashes(md5($_POST['psw']));
  // $password2=addslashes($_POST['psw2']);
  // $name=addslashes($_POST['uname']);
  // $email=addslashes($_POST['mail']);
  // $attribution=addslashes("user");

  session_start();
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
 $id=$_POST['id'];
 $password=$_POST['psw'];
 $password2=$_POST['psw2'];
 $name=$_POST['uname'];
 $email=$_POST['mail'];
 $attribution="user";
 $sql = "insert into login values('$id', '$password', '$name', '$email', '$attribution')";

 if($db_conn->query($sql)){
   echo "<script>alert(\"success inserting\");</script>";

  session_destroy();
  echo "<script> self.close(); </script>";
  // header("location: member.php");
 }else{
   echo "<script>alert(\"fail to insert sql\");</script>";
 }
 // if($password==$password2){
 //
 // }
 // else{
 //   echo "<script>alert(\"Check your Password\");</script>";
 // }
}
?>

<!DOCTYPE html>
<link rel="stylesheet" href="login.css" type="text/css">
<meta charset="utf-8">
  <script type="text/javascript">
    function check(){
        if(document.signup_form.id.value==" "){
          alert("아이디를 입력하세요");
          document.signup_form.id.focus();
          return false;

        }else if(document.signup_form.psw.value==" "){
          alert("비밀번호를 입력하세요");
          document.signup_form.psw.focus();
          return false;
        }else if(document.signup_form.psw2.value==" "){
          alert("비밀번호확인을 입력하세요");
          document.signup_form.psw2.focus();
          return false;
        }else if(document.signup_form.uname.value==" "){
          alert("이름을 입력하세요");
          document.signup_form.uname.focus();
          return false;
        }else if(document.signup_form.mail.value==" "){
          alert("이메일을 입력하세요");
          document.signup_form.mail.focus();
          return false;
        }
        if(document.signup_form.psw.value!=document.signup_form.psw2.value){ //비밀번호와 비밀번호확인의 값이다를경우
          alert("입력한 2개의 비밀번호가 일치하지 않습니다.");
          document.signup_form.psw.focus();
          return false;
        }
          var exptext = /^[A-Za-z0-9_\.\-]+@[A-Za-z0-9\-]+\.[A-Za-z0-9\-]+/;

          if(exptext.test(document.signup_form.mail.value)==false){
          //이메일 형식이 알파벳+숫자@알파벳+숫자.알파벳+숫자 형식이 아닐경우
          alert("이메일형식이 올바르지 않습니다.");
          document.signup_form.mail.focus();
          return false;
        }
        document.signup_form.submit();
}
  </script>
<!-- <form action="action_page.php"> -->
  <form name="signup_form" method="post" action="" onsubmit="check();">
    <!-- phpjs/dbconnection.php -->
  <div class="containers">
  <div>
    <h1>TEST JOIN</h1>
  </div>

    <label><b>ID</b></label>
    <input type="text" placeholder="Enter ID" name="id" required>
    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
    <label><b>Confirm Password</b></label>
    <input type="password" placeholder="Enter Password Again" name="psw2" required>
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>
    <label><b>E-mail</b></label>
    <input type="text" placeholder="Enter E-mail" name="mail" required>


  <p>
    <button type="submit">JOIN</button>
    <button type="button" onclick="window.close()">CANCEL</button>
  </p>


  <div class="container" style="background-color:#f1f1f1">

  </div>
  </div>
