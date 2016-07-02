<?php
/* @var $this Tmm_eatController */
/* @var $model Eat */
/* @var $form CActiveForm */
?>

<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'eat-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>
	<?php
		echo $form->errorSummary($model->Eat_Items);
		if(is_array($model->Eat_ItemsImg))
           		$Eat_ItemsImg=isset($model->Eat_ItemsImg[0])?$model->Eat_ItemsImg[0]:new ItemsImg;
		else
			$Eat_ItemsImg=$model->Eat_ItemsImg;
		?>
    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'manager_id'); ?>
        <?php  echo $form->dropDownList($model->Eat_Items,'manager_id',StoreContent::manager_items($model->Eat_Items->Items_StoreContent)); ?>
        <?php echo $form->error($model->Eat_Items,'manager_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'name'); ?>
        <?php echo $form->textField($model->Eat_Items,'name',array('size'=>30,'maxlength'=>100)); ?>
        <?php echo $form->error($model->Eat_Items,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'map'); ?>
        <?php echo CHtml::button('地图标记',array('onclick'=>'$("#amap").dialog("open"); return false;'));?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'lng'); ?>
        <?php echo $form->textField($model->Eat_Items,'lng',array("readonly"=>true)); ?>
        <?php echo $form->error($model->Eat_Items,'lng'); ?>
    </div>   
    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'lat'); ?>
        <?php echo $form->textField($model->Eat_Items,'lat',array("readonly"=>true)); ?>
        <?php echo $form->error($model->Eat_Items,'lat'); ?>
    </div>

    <?php
	    if(file_exists($model->Eat_Items->map)){
	        echo '<div class="row"><label>'.$model->getAttributeLabel('Eat_Items.map').'</label>';
	        echo $this->show_img($model->Eat_Items->map);
	        echo '</div>';
	    }
    ?>
    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'free_status'); ?>
        <?php echo $form->dropDownList($model->Eat_Items,'free_status',array(''=>'--请选择--')+Items::$_free_status,array('ajax'=>array(
        									'type'=>'POST',
											'data'=>new CJavaScriptExpression('jQuery(this).parents("form").serialize()+"&'.Yii::app()->request->csrfTokenName.'='.Yii::app()->request->csrfToken.'&cut=true'
											.'&uploadify="+jQuery("#'.CHtml::activeId($Eat_ItemsImg, 'tmp').'-queue").html()'),
											'success'=>'function(html){
                                                     jQuery("html").html(html);
                                                     $("#page").wrap("<body/>");
											}',
   				 ))); 
        ?>
        <?php echo $form->error($model->Eat_Items,'free_status'); ?>
    </div>
    <div class="row" style="width:700px;" id="fare_list">
        <?php echo $form->labelEx($model,'fare'); ?>
        <?php  foreach ($model->Eat_Fare as $key=>$Eat_Fare){?>
            <div style="margin-left: 110px;">
                <div style="float: left;width:140px;">
                    <?php echo $form->textField($Eat_Fare,"[$key]name",array('style'=>'width:120px;','placeholder'=>'名称',"readonly"=>$model->Eat_Items->free_status==Items::free_status_yes)); ?>
                    <?php echo $form->error($Eat_Fare,"[$key]name",array('style'=>'padding:0;')); ?>
                </div>
                <div style="float: left;width:140px;">
                    <?php echo $form->textField($Eat_Fare,"[$key]info",array('style'=>'width:120px;','placeholder'=>'类型',"readonly"=>$model->Eat_Items->free_status==Items::free_status_yes)); ?>
                    <?php echo $form->error($Eat_Fare,"[$key]info",array('style'=>'padding:0;')); ?>
                </div>
                <div style="float: left;width:140px;">
                    <?php echo $form->textField($Eat_Fare,"[$key]price",array('style'=>'width:120px;','placeholder'=>'价格',"readonly"=>$model->Eat_Items->free_status==Items::free_status_yes)); ?>
                    <?php echo $form->error($Eat_Fare,"[$key]price",array('style'=>'padding:0;')); ?>
                </div>
                	<?php 
                		if($model->Eat_Items->free_status != Items::free_status_yes && count($model->Eat_Fare) != Yii::app()->params['items_fare_number'] || (count($model->Eat_Fare) == Yii::app()->params['items_fare_number'] && $key != 0))
						{
                	?>
					<span class="buttons" style="padding:0;">
						<?php
                        echo CHtml::ajaxButton($key>0?'-':'+',Yii::app()->request->getUrl(),array(
											'type'=>'POST',
											'data'=>new CJavaScriptExpression('jQuery(this).parents("form").serialize()+"&'.Yii::app()->request->csrfTokenName.'='.Yii::app()->request->csrfToken.'&'.($key>0?'cut=true':'add=true')
											.'&uploadify="+jQuery("#'.CHtml::activeId($Eat_ItemsImg, 'tmp').'-queue").html()'),
											'success'=>'function(html){
                                                     jQuery("html").html(html);
                                                     $("#page").wrap("<body/>");
											}',
                        ));
                        ?>
					</span>
					<?php 
						}
					?>
                <div style="clear:both;"></div>
            </div>
        <?php  } ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'area_id_p'); ?>
        <?php echo $form->dropDownList($model->Eat_Items,'area_id_p',Area::data_array_id(),array(
            'ajax'=>array(
                'type'=>'POST',
                'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
                'dataType'=>'json',
                'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),

                'success'=>'function(data){
							jQuery("#'.CHtml::activeId($model->Eat_Items,'area_id_m').'").html(data[0]);
							jQuery("#'.CHtml::activeId($model->Eat_Items,'area_id_c').'").html(data[1]);
					}',
            ),
        ));
        ?>
        <?php echo $form->error($model->Eat_Items,'area_id_p'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'area_id_m'); ?>
        <?php echo $form->dropDownList($model->Eat_Items,'area_id_m',Area::data_array_id($model->Eat_Items->area_id_p),array(
            'ajax'=>array(
                'type'=>'POST',
                'url'=>Yii::app()->createUrl('/admin/tmm_home/area'),
                'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                'update'=>'#'.CHtml::activeId($model->Eat_Items,'area_id_c'),
            ),
        )); ?>
        <?php echo $form->error($model->Eat_Items,'area_id_m'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'area_id_c'); ?>
        <?php echo $form->dropDownList($model->Eat_Items,'area_id_c',Area::data_array_id($model->Eat_Items->area_id_m)); ?>
        <?php echo $form->error($model->Eat_Items,'area_id_c'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'address'); ?>
        <?php echo $form->textField($model->Eat_Items,'address',array('size'=>60,'maxlength'=>100)); ?>
        <?php echo $form->error($model->Eat_Items,'address'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'start_work'); ?>
        <?php
        $this->widget('ext.juidatetimepicker.EJuiDateTimePicker', array(
            'model'     => $model->Eat_Items,
            'attribute'=>'start_work',
            'language'=>Yii::app()->language,
            'mode'    => 'time',
            'options'   => array(
                'flat'=>true,
                'showOn' => 'focus',
                'showSecond'=>true,
                'timeFormat' => 'HH:mm:ss',
                'controlType' =>  'select',
                'defaultValue'=>'00:00:00',
            ),
        ));
        ?>
        <?php echo $form->error($model->Eat_Items,'start_work'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'end_work'); ?>
        <?php
        $this->widget('ext.juidatetimepicker.EJuiDateTimePicker', array(
            'model'     => $model->Eat_Items,
            'attribute'=>'end_work',
            'language'=>Yii::app()->language,
            'mode'    => 'time',
            'options'   => array(
                'flat'=>true,
                'showOn' => 'focus',
                'showSecond'=>true,
                'timeFormat' => 'HH:mm:ss',
                'controlType' =>  'select',
                'defaultValue'=>'23:59:59',
            ),
        ));
        ?>
        <?php echo $form->error($model->Eat_Items,'end_work'); ?>
    </div>

	<?php
	//更新上传的图片
		if(!empty($model->Eat_ItemsImg) && is_array($model->Eat_ItemsImg)){
	?>
	<div class="row">
		<?php echo $form->labelEx($Eat_ItemsImg,'img'); ?>
		<div style="float: left;">
			<ul style="list-style-type: none;width: 450px;padding: 0;">
		<?php
			foreach ($model->Eat_ItemsImg as $key_img=>$ItemsImg){
				echo '<li style="position:relative;float:left;">';
				echo $this->show_img($ItemsImg->img,'','',
							array('width'=>'80px', 'height'=>'80px', 'style'=>'padding:5px 5px;cursor: pointer;',
								'onclick'=>'if(confirm("确定要删除吗？")){jQuery(this).parent().remove();}'));
				echo $form->hiddenField($ItemsImg,"[$key_img]id",array());
				echo '</li>';
			}
		?>
			<div style="clear: both;"></div>
			</ul>
		</div>
		<p class="hint" style="display: inline;">点击图片可删除</p>
	</div>
	<?php }?>

	<div class="row">
		<?php echo $form->labelEx($Eat_ItemsImg,'tmp'); ?>
		<div style="margin-left: 110px;">
			<?php echo $form->fileField($Eat_ItemsImg,'tmp',array('style'=>'width:300px;')); ?>
			<?php echo $form->error($Eat_ItemsImg,'tmp'); ?>
		</div>
	</div>
    <?php
    $this->renderPartial('/_common/_html',array(
        'form'=>$form,
        'width'=>'98%',
        'height'=>280,
        'model'=>$model->Eat_Items,
        'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
        'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
        'attribute'=>'content',
    ));
    ?>


    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'phone'); ?>
        <?php echo $form->textField($model->Eat_Items,'phone',array('size'=>20,'maxlength'=>20)); ?>
        <?php echo $form->error($model->Eat_Items,'phone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->Eat_Items,'weixin'); ?>
        <?php echo $form->textField($model->Eat_Items,'weixin',array('size'=>20,'maxlength'=>20)); ?>
        <?php echo $form->error($model->Eat_Items,'weixin'); ?>
    </div>



	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); /**/ ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$csrfToken='';
$csrfTokenName='';
if(Yii::app()->request->enableCsrfValidation)
{
    $csrfTokenName = Yii::app()->request->csrfTokenName;
    $csrfToken = Yii::app()->request->csrfToken;
    $csrf = "$csrfTokenName=$csrfToken";
}else
    $csrf = '';
?>
<script type="text/javascript">
    /*<![CDATA[*/
	var num = <?php
			if(isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp'])){
				krsort($_POST['ItemsImg']['tmp']);
				foreach ($_POST['ItemsImg']['tmp'] as $k=>$v)
				{
					echo $k+1;
					break;
				}
			}else
				echo 0;
	?>;
	var eq_num=<?php echo (isset($_POST['ItemsImg']['tmp']) && is_array($_POST['ItemsImg']['tmp']))?count($_POST['ItemsImg']['tmp']):0; ?>;
    jQuery(function($){
        $("#<?php echo CHtml::activeId($Eat_ItemsImg, 'tmp') ?>").uploadify({
            'fileObjName': "<?php echo CHtml::activeName($Eat_ItemsImg, 'tmp') ?>",
            'buttonText': '上传图片',
            'swf': '<?php echo Yii::app()->request->baseUrl;?>/css/admin/ext/uploadify/uploadify.swf',
            'formData': {'<?php echo $csrfTokenName?>':'<?php echo $csrfToken?>'},
            'fileTypeExts':'*.jpg; *.png',
            'uploader': '<?php echo Yii::app()->createUrl('/admin/tmm_eat/uploads')?>&<?php echo $csrf; ?>',
            'auto': true,
            'multi': true,
			'removeCompleted': false,
			'itemTemplate' : '<div id="${fileID}" class="uploadify-queue-item" style="max-width:400px;line-height: 50px;">\
                    <div class="cancel">\
                        <a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a>\
                    </div>\
                    <span class="fileName">${fileName} (${fileSize})</span><span class="data"></span>\
                </div>',
			'onInit'   : function(instance) {
				<?php if(isset($_POST['uploadify']) && $_POST['uploadify'] !=''){?>
				jQuery("#<?php echo CHtml::activeId($Eat_ItemsImg, 'tmp'); ?>-queue").html(<?php var_export($_POST['uploadify']);?>);
				<?php }?>
				delete_file();
			},
			'onuploadStart':function(file){
				$('#<?php echo CHtml::activeId($Eat_ItemsImg, 'tmp') ?>').uploadifySettings('formData', {'<?php echo $csrfTokenName?>':'<?php echo $csrfToken?>'});
			},
			'onUploadSuccess': function(file, data, response) {
				console.log('The data ' + data + ' .');
				var data = eval('(' + data + ')');
                if(data.img_name !='none'){
					var inputHtml = '<input value=' + data.img_name + ' name="ItemsImg[tmp]['+num+']" type="hidden" />';
					inputHtml += '<img src="'+ data.litimg +'" width="50" height="50" style="float:right;"/>';
					inputHtml += '<div style="clear:both;"></div>';
					jQuery(".uploadify-queue-item").eq(eq_num).append(inputHtml);
					jQuery(".uploadify-queue-item").eq(eq_num).attr('id', 'uploadify_'+num);
					// 上传完成后，重写取消按钮（X）的监听事件
					jQuery(".uploadify-queue-item a").eq(eq_num).attr('href', 'javascript:;');
					jQuery(".uploadify-queue-item a").eq(eq_num).attr('title', data.img_name);
					//jQuery(".uploadify-queue-item a").eq(num).off('click');
					eq_num++;
					num++;
					delete_file();
                }
				//console.log('file--', file);
				//console.log('data--', data);
                //console.log('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
            }
        });
		function delete_file() {
			jQuery(".uploadify-queue-item a").on('click', function () {
				var thisObj = jQuery(this);
				// 取到id值
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo Yii::app()->createUrl('/admin/tmm_eat/uploads')?>&<?php echo $csrf; ?>',
					data: {'file_name': thisObj.attr('title'),'<?php echo $csrfTokenName?>':'<?php echo $csrfToken?>'},
					success: function (data) {
						if(data==1) {
							var queueId = thisObj.parent().parent().attr('id');
							// 移除整个div
							$('#<?php echo CHtml::activeId($Eat_ItemsImg, 'tmp');?>').uploadify('cancel', queueId);
							eq_num--;
						}
					}
				});
			});
		}
	});
	//地图点击回调函数
	function callback_function(e)
	{
		console.log('after e----',e);
		console.log('after lng----',e.lnglat.getLng());
		console.log('after lat----',e.lnglat.getLat());
		$("#<?php echo CHtml::activeId($model->Eat_Items,'lng')?>").val(e.lnglat.getLng()); 
		$("#<?php echo CHtml::activeId($model->Eat_Items,'lat')?>").val(e.lnglat.getLat()); 		
		//http://restapi.amap.com/v3/staticmap?location=114.083544,22.547&zoom=13&size=440*280&markers=mid,,A:114.083544,22.547&key=ee95e52bf08006f63fd29bcfbcf21df0
	}
    /*]]>*/
</script>
<?php	
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'amap',//弹窗ID
			'options'=>array(//传递给JUI插件的参数
					'title'=>'地图标记',
					'autoOpen'=>false,//是否自动打开
					'width'=>'75%',//宽度
					'height'=>'800',//高度
					'modal' => 'true',
			),
	));
	$this->renderPartial('/_common/_amap',array('lng'=>$model->Eat_Items->lng,'lat'=>$model->Eat_Items->lat));
	$this->endWidget();
?>
