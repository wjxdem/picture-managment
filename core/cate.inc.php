<?php 

function getCateByPid2($pid){
    $sql="select id,pid,cname,pnum from pg_cate where pid={$pid} ORDER BY cname";
    $rows=fetchAll($sql);
    return $rows;
}
/**
 * 根据pid得cate到指定分类信息，二级分类 
 * @param int $id
 * @return array
 */
function getCateByPid($pid){
    $sql="select id,pid,cname,pnum from pg_cate where pid={$pid} ORDER BY cname";
    $rows=fetchAll($sql);
    $arr2=[];
    foreach ($rows as $arr){
        foreach ($arr as $k=>$v){
            if(strtolower($v)=="ws"){
                $arr2=$arr;
            }
        }
    
    }    
    foreach( $rows as $key => $value ) {
            if(in_array('WS',$value)) unset($rows[$key]); 
    }
    array_unshift($rows, $arr2);
    return $rows;
}

/**
 * 根据id得cate到指定分类的pid
 * @param int $id
 * @return array
 */
function getCatePidById($id){
    $sql="select pid from pg_cate where id={$id}";
    return fetchOne($sql);
}

/**
 * 一级分类信息
 * @param int $id
 * @return array
 */
function getScateById($id){
    $sql="select id,sname,snum from pg_scate where id={$id}";
    return fetchOne($sql);
}

/**
 * 一级分类信息
 * @param int $id
 * @return array
 */
function getPcate(){
    $sql="select id,pcatename from pg_pcate";
    return fetchAll($sql);
}
/**
 * 一级分类信息
 * @param int $id
 * @return array
 */
function getScate(){
    $sql="select id,sname,snum,stime from pg_scate ORDER BY stime";
    return fetchAll($sql);
}