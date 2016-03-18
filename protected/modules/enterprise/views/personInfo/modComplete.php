<?php
	Views::css(array('merchant'));
?>
	<!-- main -->
	<main>
		<section class="company-content">
			<header class="company-tit">企业信息
				<nav>
					<?php
						echo CHtml::link('设置新密码' , $this->createUrl('personInfo/showVerity'),array('class'=>'current'));
						echo CHtml::link('基本资料' , $this->createUrl('index'));
					?>
				</nav>
			</header>
			<!-- 流程 -->
			<ul class="shop-process shop-process-1">
				<li class="current first pass"><b></b><em>1</em><i></i><p>验证身份</p></li>
				<li class="current pass"><b></b><em>2</em><i></i><p>修改登录密码</p></li>
				<li class="current"><b></b><em>3</em><i></i><p>完成</p></li>
			</ul>
			<div class="mod-success">
				<div></div>
				<p>恭喜您，修改密码成功</p>
				<footer><span>安全等级</span><i class="bg-1"></i><i class="bg-2"></i><i class="bg-3"></i><em>高</em></footer>
			</div>
		</section>
	</main>

