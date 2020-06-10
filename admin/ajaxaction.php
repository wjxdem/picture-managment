<?php
require_once '../include.php';
@$act=$_REQUEST['act'];
@$id=$_REQUEST['id'];
@$pid=$_REQUEST['pid'];
if($act=="getselection"){
    getSelectionById($id);
}elseif ($act=="getallcate"){
    getallcate($pid);
}elseif($act=="delphoto"){
    delPhoto($id);
}
/**
 * 根据ID得到指定分类信息
 * @param int $id
 * @return array
 */
function getSelectionById($id){
    $sql="select id,cname from pg_cate where pid={$id}";
    $rows = fetchAll($sql);
    $arr2['data'] ['rows']= $rows;
    $arr2['success'] = true;
    echo json_encode($arr2);
}


/**
 * 根据pID得到指定分类信息
 * @param int $pid
 * @return array
 */
function getallcate($pid){
    $sql="select p.id,p.cname,p.pnum,p.ptime,s.sname from pg_cate p join pg_scate s on s.id=p.pid where pid={$pid}";
    $rows = fetchAll($sql);
    echo json_encode($rows);
}
