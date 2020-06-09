<?php
require_once './include.php';
//图片批量下载
// $pathArr=$_POST['url'];
$idstring=$_REQUEST['id'];
if(empty($idstring)){
    $arr['success']=false;
    $arr['msg']='请选择下载项!';
}else{
    $act=$_REQUEST['atc'];
    $ids=explode("-", $idstring);
    $ids = array_merge(array_diff($ids ,array('0')));   
    //获取图片的路径
    $pathArr=[];
    foreach ($ids as $id){
        if($act=="logo"){
            $pidcid=getLogoPhotoPidCid($id);
        }else{
            $pidcid=getMaterialPhotoPidCid($id);
        }
        array_push($pathArr, "uploads/".$pidcid['pid'].'/'.$pidcid['cid'].'/'.$pidcid['pname']);
    }
    $filename =md5(time()).".zip"; // 最终生成的文件名（含路径）date ( 'YmdH' )
    $zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
    if ($zip->open ( $filename, ZIPARCHIVE::CREATE  ) !== TRUE) {
        exit ( '无法打开文件，或者文件创建失败' );
    }
    foreach ( $pathArr as $val ) {
        $zip->addFile(iconv('utf-8', 'gb2312', $val) , basename( iconv('utf-8', 'gb2312', $val) ));
    }
    $zip->close ();
    
    //下面是输出下载;
    header ( "Cache-Control: max-age=0" );
    header('Cache-Control:no-cache,must-revalidate');
    header ( "Content-Type: application/zip" ); // zip格式的force-download
    header ( 'Content-Disposition: attachment; filename=' . basename ( $filename ) ); // 用于提供一个推荐的文件名并强制浏览器显示保存对话框
    header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
    header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小
    @readfile ( $filename );//输出文件;
    //设置zip权限，删除根目录下生成的zip
    chmod($filename,0777);
    if(file_exists($filename)){
        unlink($filename);
        $arr['success']=true;
    }
    // header('Content-type: text/json;');
       
}
echo json_encode($arr); 