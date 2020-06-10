<?php 
require_once '../include.php';
$page=isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
$sql="select * from pg_log";
$totalRows=getResultNum($sql);
$allRcd=fetchAll($sql);
//分页
$pageSize=isset($_REQUEST['limit'])?$_REQUEST['limit']:null;
$offset=isset($_REQUEST['start'])?$_REQUEST['start']:null;//start
$sql="select id, ptime,pdesc from pg_log  order by ptime desc limit {$offset},{$pageSize}";
$rows=fetchAll($sql);
$arr=[];
foreach ($rows as $row){
    $row['ptime']=date('Y-m-d H:i:s',$row['ptime']);
    $arr[] = $row;
}
$arr2['data'] ['rows']= $arr;
$arr2['data'] ['count']= $totalRows;
$arr2['success'] = true;
echo json_encode($arr2);