<?php require 'header.php';
require 'include.php';
//导航信息
$pcates=getPcate();
$scates=getScate();
$scatenum=count($scates);
$sql="select id,cname from pg_cate where pid=1";
$allRcd=fetchAll($sql);

?>
<div class="wrap">
    <div class="header-bg">
        <header class="header">
          <a class="logo" href="index.php"><img src="img/logo.png" /></a>
          <ul class="top-nav">
            <li><a href="index.php">产品LOGO</a></li>
            <li><a class="active" href="productmaterial.php">架构图素材</a></li>
          </ul>
          </header>
    </div>
      
       <nav class="sub-nav">
          <ul id="sub-nav">
          <?php  foreach($scates as $key=>$row):?>
            <li <?php if($key == 0){ echo "class='current'";} ?>><a class="nav-bar1" href="#sections-<?php echo $key+1?>"><?php echo $row['sname'];?></a></li>
          <?php endforeach;?> 
          </ul>
       </nav>
      <div class="picture-list">
      <?php  foreach($scates as $key=>$row):?>
        <div id="sections-<?php echo $key+1?>">
          <p id="equipment" class="sub-title"><?php echo $row['sname'];?></p>
          <ul>
          <?php  $cates=getCateByPid2($row['id']); 
                if($cates &&is_array($cates)):
                foreach ($cates as $cate):
                $pic=getmaterialPhotoById($cate['id']);
            ?>
	            <li>
	                <div class="picture-wrap js-picture" cid="<?php echo $cate['id'];?>" pid="<?php echo $cate['pid'];?>">
	                  <img src="<?php echo  $pic['ppath']?>" alt=""/>
	                  <div class="down">
	                    <img src="img/down.png" alt="download"/>
	                  </div>
	                </div>
	                <div class="download-btn"><?php echo $cate['cname'];?></div>
	            </li>
	       <?php endforeach;
	               endif;?>
        	</ul>
        </div>
       <?php endforeach;?>  
    </div>
</div>
<?php require 'footer.php';?>