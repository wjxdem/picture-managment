<?php
//为产品logo获取图片信息
require_once './include.php';
header("Content-Type: text/html; charset=utf-8");
$pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:null;
$cid=isset($_REQUEST['cid'])?$_REQUEST['cid']:null;
$path= "uploads/".$pid.'/'.$cid.'/';
$sql="select cname from pg_cate where id='{$cid}'";
$rows=fetchOne($sql);
$parentname=strtolower($rows['cname']);

//对图片进行正常,特殊,大中小分类
$arr2=[];
$flag=[];
if(!empty($rows)){
    $sql1="select pstyle,pstate from pg_logophoto where parentname ='{$parentname}' order by pstyle";
    $rows2=fetchAll($sql1);
    $pstylelist=[];
    $pstatelist=[];
    foreach ($rows2 as $r){
        $pstylelist[]=$r['pstyle'];
        $pstatelist[]=$r['pstate'];
    }
    $pstyleflag=array_unique($pstylelist);//1,2,3,4,5,6
    $pstateflag=array('n','r','s');//n,r,s
    foreach ($pstateflag as $k=>$v){
        foreach ($pstyleflag as $key=>$va){        
             if($v=='n'){
                 $rs=getlogoimage($va,$v,$parentname,$path);
                 if(!empty($rs['smallurl'])){
                    $arr2['data']['rows']['normal'][]=$rs; 
                 }   
             }elseif ($v=='r'){
                 $rs2=getlogoimage($va,$v,$parentname,$path);
                 if(!empty($rs2['smallurl'])){
                      $arr2['data']['rows']['reverse'][]=$rs2;
                 }
             }elseif ($v=='s'){
                 $rs1=getlogoimage($va,$v,$parentname,$path);
                 if(!empty($rs1['smallurl'])){
                     $arr2['data']['rows']['special'][]=$rs1;
                 }
             }
         }
     }
    $arr2['success'] = true;
}else{
    $arr2['success'] = false;
    $arr2['msg'] = '查无数据';
}
echo json_encode($arr2);