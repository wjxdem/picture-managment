<?php
 require '../include.php';
 require_once 'head.php';?>
</head>
<body>
<header class="pg-header">
	<div class="pull-left">
		<a href="index.php" class="pg-logo"><img src="images/logo.png" /></a>
		<ul class="top-nav">
			<li ><a href="index.php" >产品LOGO管理</a></li>
			<li><a href="img-manage.php">架构图素材管理</a></li>
			<li><a href="classify-manage.php">分类管理</a></li>
			<li class="current"><a href="log-manage.php">更新日志</a></li>
		</ul>
	</div>
<?php require_once 'header.php';?>
    <div class="row-fluid pg-main">
        <div class="pg-content">
            <div class="panel">
                <div class="panel-body">
                     <a href="javascript:;" class="button js-add-log">添加日志</a>
                    <div class="js-add-wrap form-horizontal hide">
                        <div class="m-header">添加日志</div>
                        <div class="control-group">
                            <div class="controls  control-row-auto">
                                <textarea name="content" class="textarea-large" id="js-content" placeholder="请填写日志"></textarea>
                            </div>
                            <span id="js-error" class="m-error"> </span>
                        </div>
                         <p><a href="javascript:;" class="button m-add-btn js-add-btn">添加</a>
                         <a href="javascript:;" class="button button-cancel js-cancel-btn">取消</a>
                         </p>
                        </div>
                         <form class="form-horizontal" id="search-form" action="./getLog.php" method="post">
                        
                    </form>
                    <div id="js-grid">
                        <!--表格内容-->
                    </div>
                    <div id="js-bottom-bar"></div>
                    </div>
                   
                    
                    <div id="js-dialog-content" class="hide">
                        <form  class="form-horizontal m-add-dialog clearfix" action="./adminAction.php?act=editlog" method="post">
                            <input type="hidden" name="id">
                            <div class="control-group">
                                <label class="control-label">
                                   创建时间：</label>
                                <div class="controls">
                                  <span class="ptime"> </span>
                                    
                                </div>
                            </div>
                            <div class="control-group">
                             <label class="control-label">
                                    日志内容：</label>
                                <div class="controls  control-row-auto">
                                    <textarea name="pdesc" class="input-large"  placeholder="请填写日志"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="../js/jquery-1.8.1.min.js"></script>
    <script src="../js/bui/1.1.21/seed-min.js"></script>
    <script src="../js/list/wsfe-list.js"></script>
    <script type="text/javascript" src="scripts/base.js"></script>
    <script src="scripts/lib/component/bui.dialog.js"></script>
    <script src="scripts/logManagePage.js"></script>
    <script type="text/javascript">
    window.globalConfig = {
        api: {
            dellog: './adminAction.php?act=dellog',
            addlog: './adminAction.php?act=addlog'
        }

    }
    new pg.admin.LogManagePage();
    </script>
</body>

</html>
