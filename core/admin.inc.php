<?php 
/**
 * 检查管理员是否存在
 * @param unknown_type $sql
 * @return Ambigous <multitype:, multitype:>
 */
function checkAdmin($sql){
	return fetchOne($sql);
}
/**
 * 检测是否有管理员登陆.
 */
function checkLogined(){
	if(@$_SESSION['adminId']==""&&@$_COOKIE['adminId']==""){
	    header("location:login.php");
	}
}

/**
 * 得到所有的管理员
 * @return array
 */
function getAllAdmin(){
	
	$sql="select id,username,email from pg_admin ";
	$rows=fetchAll($sql);
	return $rows;
}

function getAdminByPage($page,$pageSize=2){
	$sql="select * from pg_admin";
	global $totalRows;
	$totalRows=getResultNum($sql);
	global $totalPage;
	$totalPage=ceil($totalRows/$pageSize);
	if($page<1||$page==null||!is_numeric($page)){
		$page=1;
	}
	if($page>=$totalPage)$page=$totalPage;
	$offset=($page-1)*$pageSize;
	$sql="select id,username,email from pg_admin limit {$offset},{$pageSize}";
	$rows=fetchAll($sql);
	return $rows;
}

/**
 * 注销管理员
 */
function logout(){
	$_SESSION=array();
	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(),"",time()-1);
	}
	if(isset($_COOKIE['adminId'])){
		setcookie("adminId","",time()-1);
	}
	if(isset($_COOKIE['adminName'])){
		setcookie("adminName","",time()-1);
	}
	session_destroy();
	header("location:login.php");
}
