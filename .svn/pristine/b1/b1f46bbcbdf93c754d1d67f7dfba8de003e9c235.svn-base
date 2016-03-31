<?php
Yii::app ()->getClientScript ()->registerCoreScript ( 'layer' );
Views::css ( array (
		'page/default' 
) );
?>
<?php $this->renderPartial('navigation'); ?>
<style>
	table th{background: #fffbe3; border: solid #ccc 1px; text-align: center; height: 38px;}
	table td{ border: solid #ccc 1px; height: 38px; text-align: center;}
#tab {
	position: relative;
}

#tab .tabList ul li {
	float: left;
	background: #fefefe;
	background: -moz-linear-gradient(top, #fefefe, #ededed);
	background: -o-linear-gradient(left top, left bottom, from(#fefefe),
		to(#ededed));
	background: -webkit-gradient(linear, left top, left bottom, from(#fefefe),
		to(#ededed));
	border: 1px solid #ccc;
	padding: 5px 0;
	width: 100px;
	text-align: center;
	margin-left: -1px;
	position: relative;
	cursor: pointer;
}

#tab .tabCon {
	position: absolute;
	left: -1px;
	top: 32px;
	border: 1px solid #ccc;
	border-top: none;
	width: 100%;
	height: 800px;
}

#tab .tabCon div {
	position: absolute;
	padding: 10px;
	opacity: 0;
	filter: alpha(opacity = 0);
}

#tab .tabList li.cur {
	border-bottom: none;
	background: #fff;
}

#tab .tabCon div.cur {
	opacity: 1;
	filter: alpha(opacity = 100);
}

.seacrhform{
	width: 99%;padding:30px 0px 50px 0px;
}
#reco{color: #ffffff; border: solid #4c4c4c 1px; border-radius: 10px; background: #009F95; padding: 4px 15px;}
.seacrhform p{
	padding-bottom:15px;
}
.seacrhform p span{
	padding-right:15px;
}
.tbox38{width: 120px;}
.sbox32{margin-right: 10px;}
.gname{width: 300px; float: left; margin-right: 10px;}
</style>
<fieldset class="public-wraper">
	<h1 class="title">添加零元购</h1>
	<ul class="form-wraper">
		<li><span>活动标题：</span><?php echo $info['title']; ?></li>
		<li><span>会员级别：</span>
			个人：<?php echo $info['userexp']==-1?'所有个人会员都可参加':($info['userexp']==-2?'所有个人会员都不可参加':(GlobalUser::userLayerName($info['userexp'],1))); ?>及以上
			&nbsp;&nbsp;&nbsp;&nbsp;企业：<?php echo $info['companyexp']==-1?'所有企业会员都可参加':($info['companyexp']==-2?'所有企业会员都不可参加':(GlobalUser::userLayerName($info['companyexp'],2))); ?>及以上
		</li>
		<li style="padding-bottom: 10px;"><span>限领次数：</span>
			<i>活动期间每人限领 <i><?php echo $info['purlimit']; ?> 次&nbsp;&nbsp;&nbsp;&nbsp;
			<i>活动期间每天每人限领 <i><?php echo $info['day_limit']; ?> 次
		</li>
		<li>
			<span>排序：</span>
			<?php echo $info['rank']; ?>
		</li>
		<li><span>时间：</span>
			<span style="width:300px; margin-right: 8px;">
				<?php
					foreach($info['times'] as $v)
					{
						echo $v['start_time'].'-'.$v['end_time'];
					}
				?>
			</span>
			<a href="javascript:;" onclick="addactivedate(this);">[+]</a>
		</li>
		<li>
			<span>活动商品：</span>
			<aside>
			<table class='gooditemtb'>
				<thead>
					<tr class='item'>
						<th width="300">商品名称</th>
						<th width="180">活动库存</th>
						<th width="180">每人限领数量</th>
						<th width="180">需要推荐数</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($info['goods'] as $v): ?>
				<tr>
					<td width="300"><?php echo $v['title']; ?></td>
					<td width="180"><?php echo $v['stock']; ?></td>
					<td width="180"><?php echo $v['user_limit']; ?></td>
					<td width="180"><?php echo $v['condition']; ?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			</aside>
		</li>
		<li><span>备注：</span>
			<textarea ><?php echo $info['remark']; ?></textarea>
		</li>
	</ul>
	<div class="clear" style="padding-bottom: 30px;"></div>
	<?php echo CHtml::link('<i></i><span class="btn-4">返回</span>' , $this->createUrl('list' )); ?>
</fieldset>