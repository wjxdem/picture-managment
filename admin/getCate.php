<?php
require_once '../include.php';
$page=isset($_REQUEST['page'])?(int)$_REQUEST['page']:1;
// 二级分类

$pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:null;//为产品logo
// $cid=isset($_REQUEST['cid'])?$_REQUEST['cid']:null;
$where=($pid)?"where c.pid='{$pid}'":null;
if($pid){
    $sql="select c.id,c.pid,c.cname,c.pnum,c.ptime ,s.sname from pg_scate s join pg_cate c on c.pid=s.id {$where}";
}else{
    $sql="select id,pid,cname,pnum,ptime from pg_cate where pid in(select id from pg_scate)";
}
$allRcd2=fetchAll($sql);
$totalRows=getResultNum($sql);
//分页
$pageSize=isset($_REQUEST['limit'])?$_REQUEST['limit']:null;
$offset=isset($_REQUEST['start'])?$_REQUEST['start']:null;//start
if($pid){
    $sql="select c.id,c.pid,c.cname,c.pnum,c.ptime ,s.sname from pg_scate s join pg_cate c on c.pid=s.id {$where} order by c.ptime desc limit {$offset},{$pageSize}";
}else{
  $sql="select c.id,c.pid,c.cname,c.pnum,c.ptime ,s.sname from pg_scate s join pg_cate c on c.pid=s.id WHERE c.pid in(select id from pg_scate) order by c.ptime desc limit {$offset},{$pageSize}";
  
}
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