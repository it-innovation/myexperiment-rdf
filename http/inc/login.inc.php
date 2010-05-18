<?php
require_once('genxml.inc.php');
require('loginfunc.inc.php');
session_start();
if ($_POST['login']){
        $res=whoami($_POST['username'],$_POST['password']);
        if ($res[0]>0){
                $_SESSION['userid']=$res[0];;
                $_SESSION['useruri']=$datauri."User/".$res[0];
                $_SESSION['users_name']=$res[1]['name'];
        }
        else $err="Invalid User Credentials";
}
if ($_POST['logout']){
        unset($_SESSION);
        @session_unregister('userid');
        @session_unregister('useruri');
        @session_unregister('users_name');
}
if ($_SESSION['userid']>0){
        $userid=$_SESSION['userid'];
        $useruri=$_SESSION['useruri'];
        $users_name=$_SESSION['users_name'];
}
?>
    <div class="login">
      <h2 style="margin: 0;">Login</h2>
      <?php 
if ($err) echo "<p><b>$err</b>\n";
      ?>
    <?php if ($_SESSION['userid']){ ?>
      <form name="logoutform" method="post">
        <p><br/><b>Logged in as:</b><br/><?= $_SESSION['users_name'] ?>
        <br/><br/><input type="submit" name="logout" value="Logout"/></p>
    <?php }else{ ?>
      <form name="loginform" method="post">
	<?php if (!$err) echo "<p>&nbsp;</p>"; ?>
        <p><small><b>Username:</b></small> <input type="text" name="username"/></p>
        <p><small><b>Password:</b></small> <input type="password" name="password"/>
        <br/><br/><input type="submit" name="login" value="Login"/></p>
      </form>
    <?php } ?>
    </div>
