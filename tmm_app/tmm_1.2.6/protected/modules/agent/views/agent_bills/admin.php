<div class="content_box">
    <?php
    echo $this->breadcrumbs(
        array(
            '我的收益'=>array('/agent/agent_agent/income'),
            '结算历史信息'=>array('/agent/agent_cash/admin'),
            '收益明细'
        )
    );
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
                'placeholder'=>"申请起始时间"
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
<!--        --><?php //echo $form->textField($model,'store_id',array('placeholder'=>"商家名称")); ?>
        <?php echo $form->textField($model,'store_id',array('placeholder'=>"商家账号")); ?>
        <?php echo $form->hiddenField($model,'cash_id',array('value'=>$id)); ?>
        <?php echo CHtml::submitButton('筛选',$htmlOptions=array('id'=>'query','class'=>'sure')); ?>

        <?php $this->endWidget(); ?>
    </div>  <!--.query-->

    <?php
    Yii::app()->clientScript->registerScript('search', "
$('.query form').submit(function(){
	$('#agent_bills_grid').yiiGridView('update', {
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
			jQuery('#agent_bills_grid').yiiGridView('update',{
				type: 'POST',
				url: jQuery(th).attr('href'),$csrf
				success: function(data) {
					jQuery('#agent_bills_grid').yiiGridView('update');
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
			jQuery('#agent_bills_grid').yiiGridView('update', {
				type: 'POST',
				url: jQuery(th_start).attr('href'),$csrf
				success: function(data) {
					jQuery('#agent_bills_grid').yiiGridView('update');
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
        'id'=>'agent_bills_grid',
        'htmlOptions'=>array('class'=>'grid-view table_box'),
        'dataProvider'=>$model->search_agent($id,''),
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
                'header'=>'日期',
                'name'=>'add_time',
                'type'=>'datetime',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'商家名称',
                'value'=>'$data->Bills_StoreUser->Store_Content->name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'商家地址',
                'value'=>'$data->Bills_StoreUser->Store_Content->Content_area_id_p_Area_id->name.$data->Bills_StoreUser->Store_Content->Content_area_id_m_Area_id->name.$data->Bills_StoreUser->Store_Content->Content_area_id_c_Area_id->name.$data->Bills_StoreUser->Store_Content->address',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'商家帐号',
                'value'=>'$data->Bills_StoreUser->phone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'我的分成收益（元）',
                'value'=>'$data->items_money_agent',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
        ),
    ));
    ?>

    <div class="copyright">
        <span>Copyright © TMM365.com All Rights Reserved</span>
    </div>  <!--.copyright-->