<?php
//架构图素材pg_materialphoto表处理
require_once '../include.php';
header("Content-Type: text/html; charset=utf-8");
$pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:null;
$cid=isset($_POST['cid'])?$_POST['cid']:null;
$where=($pid&&$cid)?"where  c.pid='{$pid}'and p.cid='{$cid}'":"where c.pid=1";
//得到数据库中所有商品
if($pid==null&&$cid==null){
    $sql="select p.id,p.pname,p.ppath,p.pubtime,p.cid,c.cname,s.sname from (pg_materialphoto p join pg_cate c on p.cid=c.id) join pg_scate s on c.pid=s.id where pid in(select id from pg_scate)"; 
}else{ 
  $sql="select p.id,p.pname,p.ppath,p.pubtime,p.cid,c.cname,s.sname from (pg_materialphoto p join pg_cate c on p.cid=c.id) join pg_scate s on c.pid=s.id {$where}"; 
}

@$totalRows=getResultNum($sql);
//分页
$pageSize=isset($_REQUEST['limit'])?$_REQUEST['limit']:null;
$offset=isset($_REQUEST['start'])?$_REQUEST['start']:null;//start
$orderBy="order by p.pubtime desc";
if($pid==null&&$cid==null){
    $sql="select p.id,p.pname,p.ppath,p.pubtime,p.cid,c.cname,s.sname from (pg_materialphoto p join pg_cate c on p.cid=c.id) join pg_scate s on c.pid=s.id where pid in(select id from pg_scate) {$orderBy} limit {$offset},{$pageSize}";
}else{
    $sql="select p.id,p.pname,p.ppath,p.pubtime,p.cid,c.cname,s.sname from (pg_materialphoto p join pg_cate c on p.cid=c.id) join pg_scate s on c.pid=s.id {$where} {$orderBy} limit {$offset},{$pageSize}";
}
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




