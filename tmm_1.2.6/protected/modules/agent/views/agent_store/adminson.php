
<div class="content_box">
	<?php
	echo $this->breadcrumbs(array('商家子账号'));
	?>

	<div class="query">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>


		<?php //echo $form->label($model,'add_time'); ?>
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
				'placeholder'=>"注册起始时间"
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
		<?php echo $form->dropDownList($model,'status',array(''=>'账号状态')+$model::$_status_agent); ?>

		<?php echo $form->textField($model->Store_Store->Store_Content,'name',array('placeholder'=>"归属商家")); ?>

		<?php echo $form->textField($model,'p_id',array('maxlength'=>11,'placeholder'=>"商家账号")); ?>

		<?php echo $form->textField($model,'phone',array('maxlength'=>11,'placeholder'=>"登录手机号")); ?>

		<?php echo CHtml::submitButton('筛选',$htmlOptions=array('id'=>'query','class'=>'sure')); ?>

		<?php $this->endWidget(); ?>

		<?php echo CHtml::link('新增子账号',array('/agent/agent_store/createSon'),$htmlOptions=array('id'=>"add_business",'class'=>'add_business')); ?>

	</div>  <!--.query-->


	<?php
	Yii::app()->clientScript->registerScript('search', "
$('.query form').submit(function(){
	$('#store-son-grid').yiiGridView('update', {
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
	$click_disable=<<<EOD
function(){
	jQuery(".popup_div").css("display","block");
	jQuery(".popup_div .stop_div").css("display","block");
	var th = this,afterDelete = function(){};
	jQuery(document).off('click','.stop_div .confirm');
	jQuery(document).on('click','.stop_div .confirm',function(event){
			jQuery(".popup_div").css("display","none");
			jQuery(".popup_div .stop_div").css("display","none");
			jQuery('#store-son-grid').yiiGridView('update',{
				type: 'POST',
				url: jQuery(th).attr('href'),$csrf
				success: function(data) {
					jQuery('#store-son-grid').yiiGridView('update');
					afterDelete(th, true, data);
				},
				error: function(XHR) {
					return afterDelete(th, false, XHR);
				}
		});
		return false;
	});
	jQuery(document).one('click','.stop_div .cancel',function(){
		jQuery(".popup_div").css("display","none");
		jQuery(".popup_div .stop_div").css("display","none");
	});
	return false;
}
EOD;
	$click_start=<<<"EOD"
function(){
	jQuery(".popup_div").css("display","block");
	jQuery(".popup_div .start_div").css("display","block");
	var th_start=this,afterDelete = function(){};
	jQuery(document).off('click','.start_div .confirm');
	jQuery(document).on('click','.start_div .confirm',function(event){
			jQuery(".popup_div").css("display","none");
			jQuery(".popup_div .start_div").css("display","none");
			jQuery('#store-son-grid').yiiGridView('update', {
				type: 'POST',
				url: jQuery(th_start).attr('href'),$csrf
				success: function(data) {
					jQuery('#store-son-grid').yiiGridView('update');
					afterDelete(th_start, true, data);
				},
				error: function(XHR) {
					return afterDelete(th_start, false, XHR);
				}
		});
		return false;
	});
	jQuery(document).one('click','.start_div .cancel',function(){
		jQuery(".popup_div").css("display","none");
		jQuery(".popup_div .start_div").css("display","none");
	});
	return false;
}
EOD;
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
		'id'=>'store-son-grid',
		'htmlOptions'=>array('class'=>'grid-view table_box'),
		'dataProvider'=>$model->search_agent_son(),
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
				'name'=>'phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'p_id',
				'value'=>'$data->Store_Store->Store_Content->name',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'商家账号',
				'name'=>'Store_Store.phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'商家负责人',
				'name'=>'Store_Store.Store_Content.lx_contacts',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'联系电话',
				'name'=>'Store_Store.Store_Content.lx_phone',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'项目数',
				'value'=>'$data->Store_Items_Count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'header'=>'订单数',
				'value'=>'$data->Store_Items_Count',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'status',
				'value'=>'$data::$_status["$data->status"]',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'name'=>'add_time',
				'type'=>'datetime',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;'),
			),
			array(
				'class'=>'CButtonColumn',
				'header'=>'操 作',
				'template'=>'{view}{update}{disable}{start}',
				'buttons'=>array(
					'view'=>array(
						'label'=>'账号详情',
						'imageUrl'=>false,
						'url'=>'Yii::app()->createUrl("/agent/agent_store/viewSon",array("id"=>$data->id))',
					),
					'update'=>array(
						'label'=>'编辑',
						'imageUrl'=>false,
						'visible'=>'$data->status==0',
						'url'=>'Yii::app()->createUrl("/agent/agent_store/updateSon",array("id"=>$data->id))',
					),
					'disable'=>array(
						'label'=>'禁用',
						'visible'=>'$data->status==1',
						'url'=>'Yii::app()->createUrl("/agent/agent_store/disableSon",array("id"=>$data->id))',
						'click'=>$click_disable,
					),
					'start'=>array(
						'label'=>'启用',
						'visible'=>'$data->status==0',
						'url'=>'Yii::app()->createUrl("/agent/agent_store/startSon",array("id"=>$data->id))',
						'click'=>$click_start,
					),
				),
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
			),
		),
	));
	?>



	<div class="copyright">
		<span>Copyright &copy; TMM365.com All Rights Reserved</span>
	</div>  <!--.copyright-->

	<!-- 启用禁用的弹出层 -->

	<div class="popup_div">

		<div class="start_div">
			<div class="popup_content">
				<div class="popup_img"></div>
				<div class="popup_text">
					<div class="big">是否确认启用该账号？！</div>
					<div class="small">账号启用后可正常登陆并使用功能</div>
				</div>
			</div> 	<!-- .popup_content -->
			<div class="popup_btn">
				<button class="confirm">确定</button>
				<button class="cancel">取消</button>
			</div>
		</div>

		<div class="stop_div">
			<div class="popup_content">
				<div class="popup_img"></div>
				<div class="popup_text">
					<div class="big">是否确认禁用该账号？！</div>
					<div class="small">账号禁用后将不可正常登陆并使用功能</div>
				</div>
			</div>		<!-- .popup_content -->
			<div class="popup_btn">
				<button class="confirm">确定</button>
				<button class="cancel">取消</button>
			</div>
		</div>		<!-- .stop -->
	</div>		<!-- .popup_div -->

</div>	<!--.content_box-->
