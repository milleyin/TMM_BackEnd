
<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '账号详情'=>Yii::app()->createUrl('/agent/agent_agent/account'),
        '区域权限详情'
    ));
    ?>
<br>
    <div class="query1">
    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'action'=>Yii::app()->createUrl($this->route),
        'method'=>'get',
        'htmlOptions'=>array('class'=>'modify_project_info_form'),
    ));
    ?>
            <?php echo $form->dropDownList($model,'area_id_p',Area::data_array_id(0,array(''=>'省')),array(
                'class'=>'province',
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/agent/agent_home/area'),
                    'dataType'=>'json',
                    'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    'success'=>'function(data){
									jQuery("#'.CHtml::activeId($model,'area_id_m').'").html(data[0]);
									jQuery("#'.CHtml::activeId($model,'area_id_c').'").html(data[1]);
							}',
                ),
            ));
            ?>
            <?php
            echo $form->dropDownList($model,'area_id_m',Area::data_array_id($model->area_id_p,array(''=>'市')),array(
                'class'=>'city',
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/agent/agent_home/area'),
                    'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    'update'=>'#'.CHtml::activeId($model,'area_id_c'),
                ),
            )); ?>
            <?php echo $form->dropDownList($model,'area_id_c',Area::data_array_id($model->area_id_m,array(''=>'区/县')),array(
                'class'=>'area',
            )); ?>
    <?php echo CHtml::submitButton('筛选',$htmlOptions=array('class'=>'button')); ?>
    <?php $this->endWidget(); ?>
    </div>
    <br>
    <?php
    Yii::app()->clientScript->registerScript('search', "
$('.query1 form').submit(function(){
	$('#store-area-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
    $summaryText=<<<"EOD"
<div class="summary">
	<div class="text">
	<span>当前显示 
    		<span class="num_span" id="items_count">
				<script type="text/javascript">
					jQuery(function($) {
						jQuery("#items_count").html({end}-{start}+1);
					})
				</script>	
			</span>
    		条数据 ｜ 共
			<span class="num_span">{pages}</span>
    		 页
    </span>
    </div>
</div>
EOD;
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'store-area-grid',
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
                'name'=>'Area_Area_M.Area_Area_P.name',
                'header'=>'省',
                'headerHtmlOptions'=>array('style'=>'text-align:center;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'name'=>'Area_Area_M.name',
                'header'=>'市',
                'headerHtmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'header'=>'区(县)',
                'name'=>'name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
        ),
    ));

    ?>
</div>  <!--.content_box-->
