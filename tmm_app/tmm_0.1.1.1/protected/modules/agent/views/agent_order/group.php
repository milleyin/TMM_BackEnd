
<div class="content_box">
	<?php
	echo $this->breadcrumbs(array('结伴游订单'));
	?>
	<div class="query">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'language'=>Yii::app()->language,
			'model'=>$model,
			'attribute'=>'search_start_time',
			'value'=>date('Y-m-d'),
			'options'=>array(
				'maxDate'=>'new date()',
				'dateFormat'=>'yy-mm-dd',
				'showOn' => 'focus',
				'showOtherMonths' => true,
				'selectOtherMonths' => true,
				'changeMonth' => true,
				'changeYear' => true,
				'showButtonPanel' => true,
			),
			'htmlOptions'=>array(
				'placeholder'=>"订单起始时间"
				//'maxlength'=>10,
				//'size'=>10,
			),
		));
		?>
		<label>-</label>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'language'=>Yii::app()->language,
			'model'=>$model,
			'attribute'=>'search_end_time',
			'value'=>date('Y-m-d'),
			'options'=>array(
				'maxDate'=>'new date()',
				'dateFormat'=>'yy-mm-dd',
				'showOn' => 'focus',
				'showOtherMonths' => true,
				'selectOtherMonths' => true,
				'changeMonth' => true,
				'changeYear' => true,
				'showButtonPanel' => true,
			),
			'htmlOptions'=>array(
				'placeholder'=>"结束时间"
				//'maxlength'=>10,
				//'size'=>10,
			),
		));
		?>


		<?php echo $form->dropDownList($model,'status',array(''=>'订单状态')+$model::$_order_status); ?>

		<?php echo $form->textField($model,'order_no',array('maxlength'=>11,'placeholder'=>"订单号")); ?>

		<?php echo $form->textField($model,'user_id',array('maxlength'=>11,'placeholder'=>"下单用户")); ?>

		<?php echo CHtml::submitButton('筛选',$htmlOptions=array('id'=>'query','class'=>'sure')); ?>

		<?php $this->endWidget(); ?>
	</div>  <!--.query-->

	<?php
	Yii::app()->clientScript->registerScript('search', "
$('.query form').submit(function(){
	$('#store-user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

	if(Yii::app()->request->enableCsrfValidation)
	{
		$csrfTokenName = Yii::app()->request->csrfTokenName;
		$csrfToken = Yii::app()->request->csrfToken;
		$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
	}else
		$csrf = '';

	$summaryText=<<<"EOD"
<div class="summary">
	<div class="text">
	<span>当前显示 <span class="num_span" id="items_count">
		<script type="text/javascript">
			jQuery(function($) {
			jQuery("#items_count").html({end}-{start}+1);
			})
		</script>
	</span>条数据 ｜ 共
	<span class="num_span">{pages}</span> 页</span></div>
</div>
EOD;
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'store-user-grid',
		'htmlOptions'=>array('class'=>'grid-view table_box'),
		'dataProvider'=>$model->search_group(),
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
				'name'=>'order_no',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'下单手机',
				'name'=>'Order_User.phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'order_status',
				'value'=>'Order::$_pay_status[$data->order_status]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'order_price',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'我的收益',
				'name'=>'price',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'Order_OrderShops.group_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),/*
			array(
				'header'=>'内容名称',
				'value'=>'$data->Store_Items_Count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'订单数',
				'value'=>'0',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'我的分成',
				'type'=>'raw',
				'value'=>'CHtml::link("0",array("#"))',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'status',
				'value'=>'$data::$_status["$data->status"]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),/**/
			array(
				'class'=>'CButtonColumn',
				'header'=>'操 作',
				'template'=>'{view}',
				'buttons'=>array(
					'view'=>array(
						'label'=>'账号详情',
						'url'=>'Yii::app()->createUrl("/agent/agent_order/group_view",array("id"=>$data->id))',
						'imageUrl'=>false,
					),
				),
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
			),
		),
	));
	?>

	<div class="copyright">
		<span>Copyright © TMM365.com All Rights Reserved</span>
	</div>  <!--.copyright-->
	<script type="text/javascript">
		$(function(){
			/*alert("IE不支持find？？");*/
			/*项目量 订单量 的样式*/
			$("table").find("td").each(function(i){
				if(i%11 == 5 || i%11 == 6){
					$(this).css("color","#04b200"); /*绿色*/
				}
			});
		})
	</script>