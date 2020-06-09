<?php 
/**
 * 获取数据连接
 * @return resource
 */

function connect(){
    $link = mysqli_connect(DB_HOST,DB_USER, DB_PWD);
    mysqli_select_db($link, DB_DBNAME);
    mysqli_set_charset($link, DB_CHARSET);
	return $link;
}

/**
 * 插入记录
 * @param string $table
 * @param array $array
 * @return number
 */
function insert($table,$array){
    $link=connect();
	$keys=join(",",array_keys($array));
	$vals="'".join("','",array_values($array))."'";
	$sql="insert {$table}($keys) values({$vals})";
	mysqli_query($link,$sql);
	$id= mysqli_insert_id($link);
	mysqli_close($link);
	return $id;
}

/**
 * 更新记录update imooc_admin set username='king' where id=1
 * @param string $table
 * @param array $array
 * @param string $where
 * @return number
 */
function update($table,$array,$where=null){
    $link=connect();
    $str="";
	foreach($array as $key=>$val){
		if($str==null){
			$sep="";
		}else{
			$sep=",";
		}
		$str.=$sep.$key."='".$val."'";
	}
	$sql="update {$table} set {$str} ".($where==null?null:" where ".$where);
	$result=mysqli_query($link,$sql);
	mysqli_close($link);
    return $result;

}

/**
 *	删除
 * @param string $table
 * @param string $where
 * @return number
 */
function delete($table,$where=null){
    $link=connect();
	$where=$where==null?null:" where ".$where;
	$sql="delete from {$table} {$where}";
	mysqli_query($link,$sql);
	$con=mysqli_affected_rows($link);
	mysqli_close($link);
	return $con;
}

/**
 * 获取一条记录
 * @param string $sql
 * @param string $result_type
 * @return multitype:
 */
function fetchOne($sql,$result_type=MYSQL_ASSOC){
    $link=connect();
	$result=mysqli_query($link,$sql);
	$row=mysqli_fetch_array($result,$result_type);
	mysqli_close($link);
	return $row;
}


/**
 * 获取全部记录
 * @param string $sql
 * @param string $result_type
 * @return multitype:
 */
function fetchAll($sql,$result_type=MYSQL_ASSOC){
    $rows=array();
    $link=connect();
	$result=mysqli_query($link,$sql);
	while(@$row=mysqli_fetch_array($result,$result_type)){
		$rows[]=$row;
	}
	mysqli_close($link);
	return $rows;
}

/**
 * 获取结果条数
 * @param  $sql
 * @return number
 */
function getResultNum($sql){
    $link=connect();
	$result=mysqli_query($link,$sql);
	$con=mysqli_num_rows($result);
	mysqli_close($link);
	return $con;
}

/**
 * 获取数据库变动id
 * @return number
 */
function getInsertId(){
    $link=connect();
    $id=mysqli_insert_id($link);
    mysqli_close($link);
	return $id;
}
