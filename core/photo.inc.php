<?php
//添加图片操作;

/**
 * 检查分类下logo表是否有图片
 * @param int $cid
 * @return array
 */
function checkLogoPhotoExist($cid){
    $sql="select * from pg_logophoto where cid={$cid}";
    $rows=fetchAll($sql);
    return $rows;
}
/**
 * 检查分类下maretial表是否有图片
 * @param int $cid
 * @return array
 */
function checkMaterialPhotoExist($cid){
    $sql="select * from pg_materialphoto where cid={$cid}";
    $rows=fetchAll($sql);
    return $rows;
}
/**
 * 检查架构图分类下是否有图片
 * @param int $cid
 * @return array
 */
function checkPhotoExistInScate($id){
    $sql="select * from pg_materialphoto where cid in(select id from pg_cate where pid={$id})";
    $rows=fetchAll($sql);
    return $rows;
}
/**
 * 检查架构图一级分类下是否有二级分类
 * @param int $cid
 * @return array
 */
function checkSubExistInScate($id){
    $sql="select id from pg_cate where pid={$id}";
    $rows=fetchAll($sql);
    return $rows;
}

function datefilter($path,$row){
        $row['ppath']=$path.$row['pname'];      
    return $row;
}
/**
 *根据cid得到1条产品的图片
 * @param int $cid
 * @return Array
 */
function getlogoPhotoById($cid,$name){ 
    $row1=getCatePidById($cid);
    $pid=$row1['pid'];
    $path= "uploads/".$pid.'/'.$cid.'/';
    $cname=strtolower($name);
    $and="and pstate='n' and psize='s' and parentname ='{$cname}' limit 1";
    $select="select pname,ppath from pg_logophoto where";
    $sql="{$select} cid={$cid} and pstyle='4' {$and}";
    $row=fetchOne($sql);
    $sql2="{$select} cid={$cid} and pstyle='2' {$and}";
    $row2=fetchOne($sql2);
    $sql3="{$select} cid={$cid} and pstyle='3' {$and}";
    $row3=fetchOne($sql3);
    $sql4="{$select} cid={$cid} and pstyle='1' {$and}";
    $row4=fetchOne($sql4);
    if(!empty($row)){ 
        
        return datefilter($path,$row);
    }
    if(empty($row)&& !empty($row2)){        
       return datefilter($path,$row2);
    }
    if(empty($row)&&empty($row2)&& !empty($row3)){    
       return datefilter($path,$row3);
    }
    if(empty($row)&&empty($row2)&&empty($row3)&& !empty($row4)){
       return datefilter($path,$row4);
    }
}
/**
 *根据cid得到1条产品的图片
 * @param int $cid
 * @return Array
 */
function getmaterialPhotoById($cid){
    $row1=getCatePidById($cid);
    $pid=$row1['pid'];
    $path= "uploads/".$pid.'/'.$cid.'/';
    $sql="select pname,ppath from pg_materialphoto where cid={$cid} and pversion='1' limit 1";
    $row=fetchOne($sql);
    return  datefilter($path,$row);
}

/**
 *根据id得到图片的pid,cid,产品的图片名
 * @param int $cid
 * @return Array
 */
function getLogoPhotoPidCid($id){
    $sql="select c.pid,p.cid, p.pname from pg_cate c, pg_logophoto p where c.id=p.cid and p.id={$id};";
    $row=fetchOne($sql);
    return $row;
}
/**
 *根据id得到图片的pid,cid,产品的图片名
 * @param int $cid
 * @return Array
 */
function getMaterialPhotoPidCid($id){
    $sql="select c.pid,p.cid,p.pname from pg_cate c, pg_materialphoto p where c.id=p.cid and p.id={$id};";
    $row=fetchOne($sql);
    return $row;
}
/**
 * 由id获取图片信息
 * @param int $id
 * @return array:
 */
function getImgByLogoPhotoId($id){
    $sql="select pname ,ppath,cid from pg_logophoto a where id={$id}";
    $row=fetchOne($sql);
    return $row;
}
/**
 * 由id获取图片信息
 * @param int $id
 * @return array:
 */
function getImgByMaterialPhotoId($id){
    $sql="select pname ,ppath,cid from pg_materialphoto a where id={$id}";
    $row=fetchOne($sql);
    return $row;
}
/**
 * 获取logo下所有图片
 * @return array:
 */
function getLogoPhoto(){
    $sql="select id,pname,ppath,pubtime,cid from pg_logophoto where cid in(select id from pg_cate WHERE pid=1)";
    $rows=fetchAll($sql);
    return $rows;
}
/**
 * 根据pstyle,pstate,patentname,获得相应的图片
 * @param unknown $pstyle
 * @param unknown $pstate
 * @param unknown $parentname
 * @return multitype:multitype: unknown
 */
function getlogoimage($pstyle,$pstate,$parentname,$path){
    $sql="select id, pname,ppath,parentname,psize from pg_logophoto  where pstate='{$pstate}' and pstyle='{$pstyle}' and parentname ='{$parentname}' order by psize";
    $rows=fetchAll($sql);
    $sql2="select id, pname,ppath,parentname,psize from pg_logophoto  where pstate='{$pstate}' and pstyle='{$pstyle}' and parentname ='{$parentname}' and psize='s'";
    $row=fetchOne($sql2);
    if(!empty($row['ppath'])){
        $row2=datefilter($path,$row);
        $arr['smallurl']=$row2['ppath'];
        $rows=getpaths($path, $rows);
        $arr[]=$rows;
    }else{
        $arr['smallurl']='';
    }    
    return $arr;
}

function getpaths($path,$rows){
    $arr=[];
    foreach ($rows as $row){
        $row['ppath']=$path.$row['pname'];
        $arr[] = $row;
    }
    return $arr;
}