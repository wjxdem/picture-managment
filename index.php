<?php require 'header.php';
require 'include.php';
$cates=getCateByPid(1);
$rs=getLogoPhoto();
//产品logo展示页面
?>
   <div class="wrap">
       <div class="header-bg">
            <header class="header">
              <a class="logo" href="index.php"><img src="img/logo.png" /></a>
              <ul class="top-nav">
                <li><a class="active" href="index.php">产品LOGO</a></li>
                <li><a href="productmaterial.php">架构图素材</a></li>
              </ul>
            </header>
       </div>
      
      <div class="logo-picture-list">
        <ul>
        <?php   $count= count($cates);
                foreach($cates as $key=>$row):             
                 $photo=getlogoPhotoById($row['id'],$row['cname']);  
                 if($key>=5):
        ?>
          <li class="hide">
          <?php else :?>
          <li>
          <?php endif;?>
              <div class="picture-wrap js-logo"  cid="<?php echo $row['id'];?>" pid="<?php echo $row['pid'];?>">
                <img src="<?php echo $photo['ppath'];?>" alt="<?php echo $photo['pname'];?>"/>
                <div class="down">
                  <img src="img/down.png" alt="download"/>
                </div>
              </div>
              <div class="download-btn"><?php echo $row['cname'];?></div>
          </li>
          <?php 
          endforeach;
          ?> 
        </ul>
      </div>
      <div class="load-more">
        <div class="load-more-bg">
        <?php if($count>=5) :?>
          <p>加载更多</p>
          <?php endif;?>
        </div>
      </div>
    </div>

<?php require 'footer.php';?>