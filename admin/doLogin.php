<?php 
require_once '../include.php';
$username=$_POST['username'];
$username=addslashes($username);
$password=$_POST['password'];

// $verify=$_POST['verify'];
// $verify1=$_SESSION['verify'];
// $autoFlag=isset($_POST['autoFlag']);
// if($verify==$verify1){
	$sql="select * from pg_admin where username='{$username}' and password='{$password}'";
	$row=checkAdmin($sql);

	if($row){
// 		//如果选了一周内自动登陆
// 		if($autoFlag){
// 			setcookie("adminId",$row['id'],time()+7*24*3600);
// 			setcookie("adminName",$row['username'],time()+7*24*3600);
// 		}
		$_SESSION['adminName']=$row['username'];
		$_SESSION['adminId']=$row['id'];
		header("location:index.php");
			var_dump($row);exit();
// 		alertMes("登陆成功","home.php");
	}else{
	    header("location:login.php");
		alertMes("登陆失败，重新登陆","login.php");
	}
// }else{
	header("location:login.php");
// 	alertMes("验证码错误","login.php");
// }