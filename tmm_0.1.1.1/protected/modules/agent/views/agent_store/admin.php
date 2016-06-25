
<div class="content_box">
	<?php 	
		echo $this->breadcrumbs(array('商家账号管理'));	
	?>
	<div class="query">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>

		<?php echo $form->dropDownList($model,'status',array(''=>'账号状态')+$model::$_status_agent); ?>

		<?php echo $form->textField($model->Store_Content,'name',array('size'=>11,'maxlength'=>11,'placeholder'=>"公司名称")); ?>

		<?php echo $form->textField($model,'phone',array('size'=>15,'maxlength'=>15,'placeholder'=>"登录手机号")); ?>

		<?php echo CHtml::submitButton('确认',$htmlOptions=array('id'=>'query','class'=>'sure')); ?>

		<?php $this->endWidget(); ?>

		<?php echo CHtml::link('新增商家',array('/agent/agent_store/create'),$htmlOptions=array('id'=>"add_business",'class'=>'add_business')); ?>
	
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
$click_disable=<<<EOD
function(){
	jQuery(".popup_div").css("display","block");
	jQuery(".popup_div .stop_div").css("display","block");
	var th = this,afterDelete = function(){};
	jQuery(document).off('click','.stop_div .confirm');	
	jQuery(document).on('click','.stop_div .confirm',function(){			
			jQuery(".popup_div").css("display","none");
			jQuery(".popup_div .stop_div").css("display","none");
			jQuery('#store-user-grid').yiiGridView('update',{
				type: 'POST',
				url: jQuery(th).attr('href'),$csrf
				success: function(data) {
					jQuery('#store-user-grid').yiiGridView('update');
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
	jQuery(document).on('click','.start_div .confirm',function(){	
			jQuery(".popup_div").css("display","none");
			jQuery(".popup_div .start_div").css("display","none");
			jQuery('#store-user-grid').yiiGridView('update', {
				type: 'POST',
				url: jQuery(th_start).attr('href'),$csrf
				success: function(data) {
					jQuery('#store-user-grid').yiiGridView('update');
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
			'id'=>'store-user-grid',
			'htmlOptions'=>array('class'=>'grid-view table_box'),
			'dataProvider'=>$model->search_agent(),
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
							'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
							'htmlOptions'=>array('style'=>'text-align:center;'),
					),
					array(
							'name'=>'Store_Content.name',
							'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
							'htmlOptions'=>array('style'=>'text-align:center;'),
					),
					array(
							'name'=>'Store_Content.address',
							'value'=>'$data->Store_Content->Content_area_id_p_Area_id->name.$data->Store_Content->Content_area_id_m_Area_id->name.$data->Store_Content->Content_area_id_c_Area_id->name.$data->Store_Content->address',
							'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
							'htmlOptions'=>array('style'=>'text-align:center;'),
					),
					array(		
							'header'=>'项目数',
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
//					array(
//							'header'=>'我的分成',
//							'type'=>'raw',
//							'value'=>'CHtml::link("0",array("#"))',
//							'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
//							'htmlOptions'=>array('style'=>'text-align:center;'),
//					),
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
					),
					array(
							'class'=>'CButtonColumn',
							'header'=>'操 作',
							'template'=>'{view}{disable}{start}',
							'buttons'=>array(
									'view'=>array(
											'label'=>'账号详情',
											'imageUrl'=>false,
									),
									'disable'=>array(
											'label'=>'禁用',
											'visible'=>'$data->status==1',
											'url'=>'Yii::app()->createUrl("/agent/agent_store/disable",array("id"=>$data->id))',
											'click'=>$click_disable,
									),
									'start'=>array(
											'label'=>'启用',
											'visible'=>'$data->status==0',
											'url'=>'Yii::app()->createUrl("/agent/agent_store/start",array("id"=>$data->id))',
											'click'=>$click_start,
									),
							),
							'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
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
