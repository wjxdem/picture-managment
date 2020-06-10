<?php
//为产品pg_logophoto表处理
require_once '../include.php';
header("Content-Type: text/html; charset=utf-8");
$pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:null;
$cid=isset($_POST['cid'])?$_POST['cid']:null;
$where=($pid&&$cid)?"where  c.pid='{$pid}'and p.cid='{$cid}'":"where c.pid=1";
//得到数据库中所有商品
  $sql="select p.id,p.pname,p.ppath,p.pubtime,p.cid,c.pid,c.cname from pg_logophoto p join pg_cate c on p.cid=c.id {$where}";  

@$totalRows=getResultNum($sql);
//分页
$pageSize=isset($_REQUEST['limit'])?$_REQUEST['limit']:null;
$offset=isset($_REQUEST['start'])?$_REQUEST['start']:null;//start
$orderBy="order by p.pubtime desc";
$sql="select p.id,p.pname,p.ppath,p.pubtime,p.cid,c.pid,c.cname from pg_logophoto p join pg_cate c on p.cid=c.id {$where} {$orderBy} limit {$offset},{$pageSize}";
$rows=fetchAll($sql);
$arr=[];
foreach ($rows as $row){
    $row['pubtime']=date('Y-m-d H:i:s',$row['pubtime']);
    $arr[] = $row;
}
$arr2['data'] ['rows']= $arr;
$arr2['data'] ['count']= $totalRows;
$arr2['success'] = true;
echo json_encode($arr2);




