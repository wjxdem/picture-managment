<?php require 'header.php';
require 'include.php';
$sql="select id, ptime,pdesc from pg_log order by ptime desc";
$rows=fetchAll($sql);


?>
    <div class="wrap">
        <div class="header-bg">
            <header class="header">
              <a class="logo" href="index.php"><img src="img/logo.png" /></a>
              <ul class="top-nav">
                <li><a class="active" href="#">更新日志</a></li>
              </ul>
              </header>
        </div>
      <div class="update-log-content">
        <?php  foreach($rows as $row):?>
          <p class="sub-title">更新时间：<?php echo date('Y-m-d H:i:s',$row['ptime']);?></p>
          <div class="update-detail">
            <p>更新内容：</p>
            <ul>
              <li><?php echo $row['pdesc'];?></li>
            </ul>
          </div>
         <?php endforeach;?> 
          
      </div>
    </div>
<?php require 'footer.php';?>