<?php require '../include.php';
 require_once 'head.php';
 ?>
</head>
<body>
<header class="pg-header">
	<div class="pull-left">
		<a href="index.php" class="pg-logo"><img src="images/logo.png" /></a>
		<ul class="top-nav">
			<li ><a href="index.php" >产品LOGO管理</a></li>
			<li><a href="img-manage.php">架构图素材管理</a></li>
			<li class="current"><a href="classify-manage.php">分类管理</a></li>
			<li><a href="log-manage.php">更新日志</a></li>
		</ul>
	</div>
<?php require_once 'header.php';?>
    <div class="row-fluid pg-main">
        <div class="pg-content">
            <div class="panel">
                <div class="panel-body" style="min-height:600px">
                    <div class="row-fluid m-tab-container">
                        <div class="m-tab-title clearbox">
                            <h3 class="current">产品LOGO</h3>
                            <h3>架构图素材</h3>
                        </div>
                        <div class="m-tab-content clearfix">
                            <a href="javascript:;" class="button js-add-btn">添加</a>
                            
                            <div class="form-horizontal  js-add-wrap hide">
                                <div class="m-header">添加产品LOGO分类</div>
                                <div class="control-group">
                                    <label class="control-label">分类：</label>
                                    <div class="controls">
                                        <input type="text" name="name" id="js-classify-name">
                                        <span class="m-error"></span>
                                    </div>
                                </div>
                                <p>
                                <a href="javascript:;" class="button js-add-logo-btn m-add-classify-btn">添加</a>
                                <a href="javascript:;" class="button button-cancel  js-cancel-btn">取消</a>
                                
                                </p>
                            </div>
                            <form class="form-horizontal search-form" id="logoForm" action="./getLogo.php" method="post">
                              <input type="hidden" name="pid" value="1" id="js-pid">
                            </form>
                            <div id="js-logo-grid">
                                <!--表格内容-->
                            </div>
                            <div id="js-logo-bottom-bar"></div>
                        </div>
                        <div class="m-tab-content clearfix hide">
                            <div class="m-tab-btn">
                                <a href="javascript:;" class="button button-info js-tab-classify">一级分类</a>
                                <a href="javascript:;" class="button button-cancel js-tab-classify">二级分类</a>
                            </div>
                            <?php
                                 $sql="select * from pg_scate";
                                 $allRcd=fetchAll($sql);
                             ?>
                            <div class="m-top-content">
                                <a href="javascript:;" class="button js-add-btn">添加一级分类</a>
                                <div class="form-horizontal  js-add-wrap hide">
                                    <div class="m-header">添加一级分类</div>
                                    <div class="control-group">
                                        <label class="control-label">分类：</label>
                                        <div class="controls">
                                            <input type="text" name="topClassify" id="js-topClassify">
                                            <span class="m-error"></span>
                                        </div>
                                    </div>
                                    <p> 
                                    <a href="javascript:;" class="button js-add-top-btn m-add-classify-btn">添加</a>
                                    <a href="javascript:;" class="button button-cancel  js-cancel-btn">取消</a>
                                    </p>
                                </div>
                                <form class="form-horizontal search-form" id="topForm" action="./getScate.php" method="post">
                                </form>
                                <div id="js-top-grid">
                                    <!--表格内容-->
                                </div>
                                <div id="js-top-bottom-bar"></div>
                            </div>
                            <div class="m-sub-content hide">
                                <a href="javascript:;" class="button js-add-btn">添加二级分类</a>
                                <div class="form-horizontal  js-add-wrap hide">
                                    <div class="m-header">添加二级分类</div>
                                    <div class="control-group">
                                        <label class="control-label">一级分类：</label>
                                        <div class="controls">
                                            <select id="pid" name="topClassify" class="js-select-topClassify">
                                                <option value="0" selected>请选择一级分类</option>
                                                <?php foreach ($allRcd as $rcd):?> 
            						              <option value="<?php echo $rcd['id']?>"><?php echo $rcd['sname']?></option>
            						             <?php endforeach;?> 
                                            </select>
                                            <span class="m-error"></span>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">二级分类：</label>
                                        <div class="controls">
                                            <input type="text" name="subClassify" id="js-subClassify">
                                            <span class="m-error"></span>
                                        </div>
                                    </div>
                                    <p> 
                                    <a href="javascript:;" class="button js-add-sub-btn m-add-classify-btn">添加</a>
                                   <a href="javascript:;" class="button button-cancel  js-cancel-btn">取消</a>
                                    </p>
                                </div>
                                <form class="form-horizontal search-form" id="subForm" action="./getCate.php" method="post">
                    	           <input type="hidden" name="pid" id="js-pid">
                                </form>
                                
                                <div id="js-sub-grid">
                                    <!--表格内容-->
                                </div>
                                <div id="js-sub-bottom-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="js-dialog-logo-content" class="hide">
                    <form class="form-horizontal m-add-dialog clearfix" action="./adminAction.php?act=editcate" method="post">
                        <input type="hidden" name="id">
                        <div class="control-group">
                            <label class="control-label">
                                分类名称：</label>
                            <div class="controls">
                                <input type="text" name="cname" data-rules="{required : true}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                素材数量：</label>
                            <div class="controls">
                                <span class="pnum"> </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                创建时间：</label>
                            <div class="controls">
                                <span class="ptime"> </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="js-dialog-top-content" class="hide">
                    <form class="form-horizontal m-add-dialog clearfix" action="./adminAction.php?act=editscate" method="post">
                        <input type="hidden" name="id">
                        <div class="control-group">
                            <label class="control-label">
                                一级分类：</label>
                            <div class="controls">
                                <input type="text" name="sname" data-rules="{required : true}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                素材数量：</label>
                            <div class="controls">
                                <span class="snum"> </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                创建时间：</label>
                            <div class="controls">
                                <span class="stime"> </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="js-dialog-sub-content" class="hide">
                    <form class="form-horizontal m-add-dialog clearfix" action="./adminAction.php?act=editcate" method="post">
                        <input type="hidden" name="id">
                        <div class="control-group">
                            <label class="control-label">
                                二级名称：</label>
                            <div class="controls">
                                <input type="text" name="cname" data-rules="{required : true}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                一级分类数：</label>
                            <div class="controls">
                                <span class="sname"> </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                素材数量：</label>
                            <div class="controls">
                                <span class="pnum"> </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                创建时间：</label>
                            <div class="controls">
                                <span class="ptime"> </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../js/jquery-1.8.1.min.js"></script>
    <script src="../js/bui/1.1.21/seed-min.js"></script>
    <script src="../js/list/wsfe-list.js"></script>
    <script type="text/javascript" src="scripts/base.js"></script>
    <script src="../js/component/tabswitch.js"></script>
    <script src="scripts/lib/component/bui.dialog.js"></script>
    <script src="scripts/classify-conf-list.js"></script>
    <script src="scripts/classifyManagePage.js"></script>
    <script type="text/javascript">
    window.globalConfig = {
        api: {
            addLogo: 'adminAction.php?act=addcate&pid=1',
            addTop: 'adminAction.php?act=addscate',
            addSub: 'adminAction.php?act=addcate',
            deleteLogo: 'adminAction.php?act=dellogocate',
            deleteTop: 'adminAction.php?act=delscate',
            deleteSub: 'adminAction.php?act=delcate',
            editLogo:'adminAction.php?act=editcate',
            editTop:'adminAction.php?act=editscate',
            editsub:'adminAction.php?act=editcate'
        }

    }
    new pg.admin.ClassifyManagePage();
    </script>
</body>

</html>
