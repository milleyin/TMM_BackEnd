<div class="content_box">
	<?php 	
		echo $this->breadcrumbs(array(
			'商家账号管理'=>array('admin'),
			$model->Content_Store->phone=>array('view','id'=>$model->id),
			'保证金记录',
		));	
	?>
  <div class="deposit_leave_money">当前保证金剩余：
      <span id="deposit_leave_money"><?php echo Chtml::encode($model->deposit);?></span>元
  </div>
  <?php 
  $summaryText=<<<"EOD"
<div class="summary">
	<div class="text">
	<span>当前显示 <sapn class="num_span" id="items_count">
		<script type="text/javascript">
			jQuery(function($) {
			jQuery("#items_count").html({end}-{start}+1);
			})
		</script>
	</sapn>条数据 ｜ 共
	<span class="num_span">{pages}</sapn> 页</span></div>
</div>
EOD;
  $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'store-deposit-grid',
			'htmlOptions'=>array('class'=>'grid-view table_box deposit_table'),
			'dataProvider'=>$dataProvider,
			'enableHistory'=>true,
			'summaryText'=>$summaryText,
			'template'=>"{items}\n{summary}\n{pager}",
			'pager'=>array(
					'class'=>'CLinkPager',			
					'header'=>'',
					'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
			),
			'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/query.css',
			'columns'=>array(
					array(
							'name'=>'add_time',
							'header'=>'时间',
							'type'=>'datetime',
							'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
							'htmlOptions'=>array('style'=>'text-align:center;'),
					),
					array(
							'class'=>'DataColumn',
							'evaluateHtmlOptions'=>true,
							'name'=>'deposit',
							'header'=>'操作',
							'value'=>'($data->	deposit_status==DepositLog::type_add?"+":"-").$data->deposit',
							'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
							'htmlOptions'=>array('style'=>'text-align:center;','class'=>'$data->deposit_status==DepositLog::type_add?"shared_revenue":"state"'),
					),
					array(
							'header'=>'事由',
							'name'=>'reason',
							'headerHtmlOptions'=>array('style'=>'text-align:center;'),
							'htmlOptions'=>array('style'=>'text-align:center;'),
					),
			),
	));
	?>
  <div class="copyright">
    <span>Copyright &copy; TMM365.com All Rights Reserved</span>
  </div>  <!--.copyright--> 
</div>  <!--.content_box-->
