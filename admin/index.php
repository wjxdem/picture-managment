<?php
require '../include.php';
require_once 'head.php';
checkLogined();

$sql="select id,cname from pg_cate where pid=1";
$allRcd=fetchAll($sql);

?>
</head>

<body>
<header class="pg-header">
	<div class="pull-left">
		<a href="index.php" class="pg-logo"><img src="images/logo.png" /></a>
		<ul class="top-nav">
			<li class="current"><a href="index.php" >产品LOGO管理</a></li>
			<li><a href="img-manage.php">架构图素材管理</a></li>
			<li><a href="classify-manage.php">分类管理</a></li>
			<li ><a href="log-manage.php">更新日志</a></li>
		</ul>
	</div>
    <?php require_once 'header.php';?>
    <div class="row-fluid pg-main">
        <div class="pg-content">
            <div class="panel">
                
                <div class="panel-body">
                     <p><a href="javascript:;" class="button js-add-uploader">图片上传</a></p>
                    <div class="js-add-wrap form-horizontal hide"> 
                        <div class="m-header">图片上传</div>
                        <div class="control-group">
                            <label class="control-label">选择分类：</label>
                            <div class="controls">
                                <select id="cid" name="cid" class="js-select-cate">
                                    <option value="0">-请选择-</option>
					              <?php foreach ($allRcd as $rcd):?> 
					              <option value="<?php echo $rcd['id']?>"><?php echo $rcd['cname']?></option>
					              <?php endforeach;?> 
                                </select>
                            </div>
                        </div>

                        <div id="uploader">
                            <div class="queueList">
                                <div id="dndArea" class="placeholder">
                                    <div id="filePicker" class="webuploader-container">
                                        <div class="webuploader-pick">请选择图片</div>

                                    </div>
                                </div>
                                <ul class="filelist"></ul>
                            </div>
                            <div class="statusBar hide">
                                <div class="progress hide">
                                    <span class="text">0%</span>
                                    <span class="percentage" style="width: 0%;"></span>
                                </div>
                                <div class="info">共0张（0B），已上传0张</div>
                                <div class="btns">
                                    <div id="filePicker2" class="webuploader-container">
                                        <div class="webuploader-pick">继续添加</div>
                                        
                                    </div>
                                    <div class="uploadBtn state-pedding">开始上传</div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:;" class="button button-cancel m-cancel-btn js-cancel-btn">取消</a>
                </div>
                <form  id="search-form" action="./logopage.php" method="post"> 
                   <input type="hidden" name="pid" value="1" id="js-pid">
                   <input type="hidden" name="cid" id="js-cid" >
                </form>
                
                <div id="js-grid">
                    <!--表格内容-->
                </div>
                <div id="js-bottom-bar"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    var DEV = false;
    var language = 'zh_CN';
    </script>
    <script type="text/javascript" src="../js/jquery-1.8.1.min.js"></script>
    <script src="../js/bui/1.1.21/seed-min.js"></script>
    <script src="../js/list/wsfe-list.js"></script>
    <script type="text/javascript" src="scripts/base.js"></script>
    <script src="scripts/logManagePage.js"></script>
    <script src="../js/webuploader.js"></script>
    <script src="scripts/ui-uploader.js"></script>
    <script src="scripts/logoManagePage.js"></script>
    <script type="text/javascript">
    window.globalConfig = {
        api: {
            deleteImg: 'adminAction.php?act=dellogophoto'
        }
     
    }
    new pg.admin.LogoManagePage();
    </script>

</body>

</html>
