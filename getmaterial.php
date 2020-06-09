<?php
//为产品logo获取图片信息
require_once './include.php';
header("Content-Type: text/html; charset=utf-8");
$pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:null;
$cid=isset($_POST['cid'])?$_POST['cid']:null;
$path= "uploads/".$pid.'/'.$cid.'/';
$where=($pid&&$cid)?"where  c.pid='{$pid}'and p.cid='{$cid}'":"where c.pid=1 and p.cid='{$cid}";
//得到数据库中所有图片 
$orderBy="order by p.pversion asc";
$sql="select p.id,p.pname,p.ppath,c.cname from pg_materialphoto p join pg_cate c on p.cid=c.id {$where} {$orderBy}";
$rows=fetchAll($sql);
$arr2=[];
$arr=[];
foreach ($rows as $row){
    $row['ppath']=$path.$row['pname'];
    $arr[] = $row;
}
$arr2['data']['rows']=$arr;
$arr2['success'] = true;
echo json_encode($arr2);

