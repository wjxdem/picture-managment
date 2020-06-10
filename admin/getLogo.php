<?php
//获取图片logo分类信息
require_once '../include.php';
$page=isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
$pid=isset($_REQUEST['pid'])?(int)$_REQUEST['pid']:1;
$sql="select * from pg_cate where pid='{$pid}'";
$totalRows=getResultNum($sql);
//分页
$pageSize=isset($_REQUEST['limit'])?$_REQUEST['limit']:null;
$offset=isset($_REQUEST['start'])?$_REQUEST['start']:null;//start
$sql="select c.id,c.pid,c.cname,c.pnum,c.ptime,p.pcatename from pg_cate c join pg_pcate p on p.id=c.pid where c.pid='{$pid}' order by c.ptime desc limit {$offset},{$pageSize}";
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