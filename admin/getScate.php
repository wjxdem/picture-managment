<?php
require_once '../include.php';
$page=isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
$pageSize=10;

//一级分类
$sql="select * from pg_scate";
$totalRows=getResultNum($sql);
$allRcd=fetchAll($sql);
//分页
$pageSize=isset($_REQUEST['limit'])?$_REQUEST['limit']:null;
$offset=isset($_REQUEST['start'])?$_REQUEST['start']:null;//start
$sql="select id,sname,snum,stime from pg_scate  order by stime desc limit {$offset},{$pageSize}";
$rows=fetchAll($sql);
$arr=[];
foreach ($rows as $row){
    $row['stime']=date('Y-m-d H:i:s',$row['stime']);
    $arr[] = $row;
}
$arr2['data'] ['rows']= $arr;
$arr2['data'] ['count']= $totalRows;
$arr2['success'] = true;
echo json_encode($arr2);