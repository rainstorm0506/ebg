	<?php Views::js(array('jquery-tabPlugin'));?>
	<style>.tbox28-2 {width: 170px;}</style>
	<!-- main -->
	<main>
		<section class="merchant-content merchant-content-b" id="merchant">
			<!-- 导航 -->
			<nav class="merchant-nav" id="merchantNav">
				<?php echo CHtml::link('待报价' , $this->createUrl('index'));?>
				<?php echo CHtml::link('已报价' , $this->createUrl('yesOffer'), array('class'=>'current'));?>
			</nav>
			<section class="jican-wrap">
				<!-- 搜索框 -->
				<?php $active = $this->beginWidget('CActiveForm', array('id'=>'append-form', 'htmlOptions'=>array('class'=>'mer-search'))); ?>
					<span>关键字：</span>
					<?php
						$form->keyword = isset($searchPost['keyword']) ? $searchPost['keyword'] : '';
						echo $active->textField($form , 'keyword' , array('style' => 'width:23%' , 'class'=>'sbox30', 'placeholder'=>'订单编号、联系人、联系电话'));
					?>
					<span style="margin-left:10px">发布时间：</span>
					<?php 
						$active->widget ( 'Laydate', array (
								'form' => $form,
								'id' => 'starttime',
								'name' => 'starttime',
								'class' => "tbox28 tbox28-2 mr10px",
								'value' => isset($searchPost['starttime']) ? $searchPost['starttime'] : ''
						) );
					?>
					<i>-</i>
					<?php 
						$active->widget ( 'Laydate', array (
								'form' => $form,
								'id' => 'endtime',
								'name' => 'endtime',
								'class' => "tbox28 tbox28-2 mr10px",
								'value' => isset($searchPost['endtime']) ? $searchPost['endtime'] : ''
						) );
					?>
					<?php echo CHtml::submitButton('搜索' , array('class'=>'btn-1 btn-1-7 submits','style'=>'cursor:pointer')); ?>
				<?php $this->endWidget(); ?>
				<!-- table -->
				<table class="goods-tab">
					<colgroup>
						<col width="15%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
						<col width="15%">
						<col width="auto">
					</colgroup>
					<thead>			
						<tr class="tit-1">
							<th>订单编号</th>
							<th>提交时间</th>
							<th>报价结束时间</th>
							<th>联系人</th>
							<th>联系电话</th>
							<th>报价状态</th>
							<th>操作</th>
						</tr>
						
					</thead>
					<tbody>
						<?php if(isset($yesPurchase)):foreach ($yesPurchase as $key => $val):?>
						<tr>
							<td><?php echo $val['purchase_sn'];?></td>
							<td><?php echo date('Y-m-d',$val['create_time']);?></td>
							<td><?php echo date('Y-m-d',$val['price_endtime']);?></td>
							<td><?php echo $val['link_man'];?></td>
							<td><?php echo $val['phone'];?></td>
							<td class="mc"><?php echo $val['price_endtime'] < time() || $val['is_closed'] == 3 ? '结束报价' : '完成报价';?></td>
							<td class="control">
							<?php echo CHtml::link('详情' , $this->createUrl('showDetail' , array('pid'=>$val['purchase_sn'])))."<br/>";?>
							</td>
						</tr>
						<?php endforeach;endif;?>
					</tbody>
				</table>
				<!-- pager -->
				<?php $this->widget('WebListPager', array('pages' => $page)); ?>
			</section>
		</section>
	</main>
