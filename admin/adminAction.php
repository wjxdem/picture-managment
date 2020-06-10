<?php 
require_once '../include.php';
checkLogined();
$act=$_REQUEST['act'];
@$id=$_REQUEST['id'];
@$name=$_REQUEST['name'];
if($act=="logout"){
    logout();
}elseif($act=="addcate"){
    addCate();
}elseif($act=="addscate"){
    addScate();
}elseif($act=="editcate"){
    $where="id={$id}";
    editCate($where);
}elseif($act=="editscate"){
    $where="id={$id}";
    editScate($where);
}elseif($act=="delcate"){//material
    delCate($id);
}elseif($act=="dellogocate"){//logo
    delLogoCate($id);
}elseif($act=="delscate"){
    delScate($id);
}elseif($act=="uploadphoto"){
    $mes=addPhoto();
}elseif($act=="dellogophoto"){
    $mes=dellogoPhoto($id);
}elseif($act=="delmaterialphoto"){
    $mes=delMaterialPhoto($id);
}elseif($act=="addlog"){
    addLog();
}elseif($act=="editlog"){
    $where="id={$id}";
    editLog($where);
}elseif($act=="dellog"){
    delLog($id);
}
/**
 * 添加图片
 * @return json
 */
function addPhoto(){
    $arr=$_REQUEST;
    $pid=$arr['pid'];
    $cid=$arr['cid'];
    if($pid && $cid){
        $path="../uploads/".$pid.'/'.$cid;
        $uploadFiles=uploadFile($path);
        $ids=[];
        if($pid==1){//对logo处理
            foreach ($uploadFiles as $uploadFile){//对文件名进行处理
            $pname=$uploadFile['name'];
            $ext1=explode(".",$pname);
            $ext2=explode("_",strtolower(reset($ext1)));
                if(count($ext2)==4){
                    $arr=array('parentname','pstate','pstyle','psize');
                    $arr3=[];
                    foreach ($arr as $key => $val){
                        $arr3[$val]=$ext2[$key];
                    }
                    $arr3['pname']=$uploadFile['name'];
                    $arr3['ppath']=$path.'/'.$uploadFile['name'];
                    $arr3['pubtime']=time();
                    $arr3['cid']=$cid;
                    $id=insert("pg_logophoto",$arr3);
                    array_push($ids,$id);
                    if(!empty($ids)&& !(in_array(0 ,$ids))){
                        $where="id=".$cid;
                        $sql="select pnum from pg_cate where ".$where;
                        $row=fetchOne($sql);
                        $arry['pnum']=$row['pnum']+count($uploadFiles);
                        update("pg_cate", $arry,$where);
                        $arr2['data'] ['rows']= $ids;
                        $arr2['success'] = true;
                    }else{
                        $arr2['success'] = false;
                        $arr2['msg'] = '操作失败！文件重名！';
                        break;
                    }
                }else{
                    $arr2['success'] = false;
                    $arr2['msg'] = '文件命名规则不正确！';
                    break;
                }
            }
        }else{
            foreach ($uploadFiles as $uploadFile){//对文件名进行处理
                $pname=$uploadFile['name'];
                $ext1=explode(".",$pname);
                $ext2=explode("-",strtolower(reset($ext1)));
                if(count($ext2)==2){
                    $arr=array('parentname','pversion');
                    $arr2=[];
                    foreach ($arr as $key => $val){
                        @$arr2[$val]=$ext2[$key];
                    }
                    $arr2['pname']=$uploadFile['name'];
                    $arr2['ppath']=$path.'/'.$uploadFile['name'];
                    $arr2['pubtime']=time();
                    $arr2['cid']=$cid;
                    $id=insert("pg_materialphoto",$arr2);
                    array_push($ids,$id);
                    if(!empty($ids)&& !(in_array(0 ,$ids))){
                        $where="id=".$cid;
                        $sql="select pnum from pg_cate where ".$where;
                        $row=fetchOne($sql);
                        $arry['pnum']=$row['pnum']+count($uploadFiles);
                        update("pg_cate", $arry,$where);
                        $arr2['data'] ['rows']= $ids;
                        $arr2['success'] = true;
                    }else{
                        $arr2['success'] = false;
                        $arr2['msg'] = '操作失败！文件重名！';
                        break;
                    }
                }else{
                    $arr2['success'] = false;
                    $arr2['msg'] = '文件命名规则不正确！';
                    break;
                }
                
            }
        }       

    }else{
        $arr2['success'] = false;
        $arr2['msg'] = '操作失败！请选择上传分类';
    }
    echo  json_encode($arr2);
}
/**
 * 删除logo图片
 * @param int $id
 * @return json
 */
function dellogoPhoto($id){
    $arr=$_REQUEST;
    $where="id=$id";
    $photoName=getImgByLogoPhotoId($id);
    $cid=$photoName['cid'];
    $res=delete("pg_logophoto",$where);
    if($res){
        if(file_exists($photoName['ppath'])){
            unlink($photoName['ppath']);
        }
        $where="id=".$cid;
        $sql="select pnum from pg_cate where ".$where;
        $row=fetchOne($sql);
        $arry['pnum']=$row['pnum']-1;
        update("pg_cate", $arry,$where);
        $arr2['data'] ['rows']= $res;
        $arr2['success'] = true;
    }else{
        $arr2['data'] ['rows']= $res;
        $arr2['success'] = false;
    }
    echo  json_encode($arr2);
}
/**
 * 删除logo图片
 * @param int $id
 * @return json
 */
function delMaterialPhoto($id){
    $arr=$_REQUEST;
    $where="id=$id";
    $photoName=getImgByMaterialPhotoId($id);
    $res=delete("pg_materialphoto",$where);
    if($res){
        if(file_exists($photoName['ppath'])){
            unlink($photoName['ppath']);
        }
        $where="id=".$photoName['cid'];
        $sql="select pnum from pg_cate where ".$where;
        $row=fetchOne($sql);
        $arry['pnum']=$row['pnum']-1;
        update("pg_cate", $arry,$where);
        $arr2['data'] ['rows']= $res;
        $arr2['success'] = true;
    }else{
        $arr2['data'] ['rows']= $res;
        $arr2['success'] = false;
    }
    echo  json_encode($arr2);
}

/**
 * 添加logo分类,添加二级分类
 * @return json
 */
function addCate(){
    $arr=$_REQUEST;
    $pid=$arr['pid'];
    $cates=$arr['cates'];
    if($cates){
        $arr2['pid']=$pid;
        $arr2['cname']=$cates;
        $arr2['ptime']=time();
        $id = insert("pg_cate",$arr2);
    }
    if($pid>10){//添加时相应的计数操作
        $where="id=".$pid;
        $sql="select snum from pg_scate where ".$where;
        $row=fetchOne($sql);
        $arry['snum']=$row['snum']+count($cates);
        update("pg_scate", $arry,$where);
    }
    $arr3=[];
    if($id){
        $arr3['data'] ['rows']= $id;
        $arr3['success'] = true;
    }else{
        $arr3['data'] ['rows']= $id;
        $arr3['success'] = false;
        $arr3['msg'] = '操作失败！请检查是否重名！';
    }
   echo  json_encode($arr3);
}

/**
 * 删除logo的分类
 * @param unknown $id
 * @return json
 */
function delLogoCate($id){
    $row=getCatePidById($id);
    $pid=$row['pid'];
    $delids=[];
    $dids=[];
    $res=checkLogoPhotoExist($id);
    if(!empty($res)){//如果有图片
        foreach ($res as $rs){
            $delids[]=$rs['id'];
        }
        foreach ($delids as $delid){
            $where="id=".$delid;
            $did=delete("pg_logophoto",$where);
            array_push($dids,$did);
        }
    }
    $where="id=".$id;
    $did=delete("pg_cate",$where);
    $arr['data'] ['rows']= $dids;
    $arr['success'] = true;
    echo  json_encode($arr);
}

/**
 *删除material的分类
 * @param string $where
 * @return json
 */
function delCate($id){
    $row=getCatePidById($id);
    $pid=$row['pid'];
    $delids=[];
    $dids=[];
    $res=checkMaterialPhotoExist($id);
    if(!empty($res)){
       foreach ($res as $rs){
            $delids[]=$rs['id'];
       }   
       foreach ($delids as $delid){
            $where="id=".$delid;
            $did=delete("pg_materialphoto",$where);  
            array_push($dids,$did);
       }
       $where="id=".$pid;
       $sql="select snum from pg_scate where ".$where;
       $row=fetchOne($sql);
       $arry['snum']=$row['snum']-1;
       update("pg_scate", $arry,$where);
    }
        $where="id=".$id;
        $did=delete("pg_cate",$where);
        $arr['data'] ['rows']= $dids;
        $arr['success'] = true;
   echo  json_encode($arr);
}
/**
 * 修改分类的操作
 * @param string $where
 * @return json
 */
function editCate($where){
    $arr=$_POST;
    if(update("pg_cate", $arr,$where)){
        $arr2['success'] = true;
    }else{
        $arr2['success'] = false;
        $arr2['msg'] = '操作失败！请重新操作！';
    }
    echo  json_encode($arr2);
}


/**
 * 添加架构图素材一级分类
 * @return json
 */
function addScate(){
    $arr=$_POST;
    $scate=$arr['scate'];
    $arr2['sname']=$scate;
    $arr2['stime']=time();
    $id = insert("pg_scate",$arr2);
    if($id){
        $arr3['data'] ['rows']= $id;
        $arr3['success'] = true;
    }else{
        $arr3['data'] ['rows']= $id;
        $arr3['success'] = false;
        $arr3['msg'] = '操作失败！请检查是否重名！';
    }
   echo  json_encode($arr3);
}

/**
 *删除一级分类
 * @param string $where
 * @return json
 */
function delScate($id){
    $rs=checkSubExistInScate($id);
    $res=checkPhotoExistInScate($id);
    
    if(!$res){
        if($rs){//如果有二级空文件夹，删除
            foreach ($rs as $val){
                $delids[]=$val['id'];
            } 
            foreach ($delids as $delid){
                $where="id=".$delid;     
                $did=delete("pg_cate",$where);
            }
        }
        $where="id=".$id;
        $sid=delete("pg_scate",$where);
        if($sid){
            $arr['data'] ['rows']= $sid;
            $arr['success'] = true;
        }else{
            $arr['data'] ['rows']= $sid;
            $arr['success'] = false;
            $arr['msg'] = '操作失败！请重新操作！';
        }
    }else{
        $arr['data'] ['rows']= 0;
        $arr['success'] = false;
        $arr['msg'] = '请先删除该分类下的二级分类图片！';
    }
    echo  json_encode($arr);
}
/**
 * 修改scate分类的操作
 * @param string $where
 * @return json
 */
function editScate($where){
    $arr=$_POST;
    if(update("pg_scate", $arr,$where)){
        $arr2['success'] = true;
    }else{
        $arr2['success'] = false;
        $arr2['msg'] = '操作失败！请重新操作！';
    }
    echo  json_encode($arr2);
}


/**
 * 添加日志
 * @return json
 * */
function addLog(){
    $arr=$_POST;
    $arr2['ptime']=time();
    $arr2['pdesc']=$arr['pdesc'];
    $id=insert("pg_log",$arr2);
    if(!empty($id)){
        $arr3['success'] = true;
    }else{
        $arr3['data'] ['rows']= $id;
        $arr3['success'] = false;
        $arr3['msg'] = '操作失败！请检查是否重名！';
    }
    echo  json_encode($arr3);
}
/**
 * 修改日志
 * @return json
 * */
function editLog($where){
    $arr=$_POST;
    if(update("pg_log", $arr,$where)){
        $arr2['success'] = true;
    }else{
        $arr2['success'] = false;
        $arr2['msg'] = '操作失败！请重新操作！';
    }
    echo  json_encode($arr2);
}

/**
 * 删除日志
 * @return json
 * */
function delLog($id){
    $where="id=".$id;
    $logid=delete("pg_log",$where);
    if($logid){
        $arr['data'] ['rows']= $logid;
        $arr['success'] = true;
    }else{
        $arr['data'] ['rows']= $logid;
        $arr['success'] = false;
        $arr['msg'] = '操作失败！请重新操作！';
    }
   echo  json_encode($arr);

}