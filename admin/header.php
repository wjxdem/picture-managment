	<div class="pull-right">
		<div class="top-nav-right pg-nav-pop">
			<div class="user-info">
				<a href="javascript:void(0);" title=""> <span class="uname">欢迎您！
				<?php 
    				if(isset($_SESSION['adminName'])){
    					echo $_SESSION['adminName'];
    				}elseif(isset($_COOKIE['adminName'])){
    					echo $_COOKIE['adminName'];
    				}
                ?>
				</span>
				</a>
			</div>
			<div class="pop-hd">
				<div class="pop-bd">
					<ul class="pop-nav">
						<li><a href="adminAction.php?act=logout">登出</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</header>