 <div class="content_box page_project_redact page_project_redact_again" id="page_project_create">
        <?php
    echo $this->breadcrumbs(array(
        '项目'=>array('/agent/agent_items/admin'),
        '项目编辑',
    	$model->Eat_Items->name,
    ));
    ?>
    <!--.title-->
    <?php 
    	if($model->Eat_Items->audit==Items::audit_nopass){    
    ?>
    <div class="box check_failed">
      <div><span>审核不通过原因</span></div>
      <div><span><?php echo AuditLog::get_audit_log(AuditLog::items_eat,$model->id,AuditLog::nopass)->info;?></span></div>
    </div>
    <?php 
		}
	?>
    <!-- .box -->
    <div class="create_nav modify_project_info">
      <div class="create_steap_one three_steaps_width">
        <a class="done">1</a>
        <span class="text_done">修改项目信息</span>
        <div class="line line_first done"></div>
      </div>
      <div class="create_steap_two three_steaps_width">
        <a class="undone">2</a>
        <span class="text_undone">选择标签</span>
        <div class="line undone"></div>
      </div>
      <div class="create_steap_five three_steaps_width">
        <a class="undone">3</a>
        <span class="text_undone">提交审核</span>
        <div class="line line_last undone"></div>
      </div>
    </div>
    <!-- .create_nav -->
    <div class="content">
      <div class="content_two">
      <?php 
	      if(isset($model->Eat_ItemsImg[0]))
	      	$Eat_ItemsImg=$model->Eat_ItemsImg[0];
	      else
	      	$Eat_ItemsImg=new ItemsImg;
      ?>
      <?php 
	      $form=$this->beginWidget('CActiveForm', array(
				'id'=>'eat-form',
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			    'htmlOptions'=>array('enctype'=>'multipart/form-data','class'=>'modify_project_info_form'),
			)); 
	?>
	      <div class="row-fluid">
            <label>项目名称：</label>
            <div class="col_proname">
              <?php echo $form->textField($model->Eat_Items,'name',array('maxlength'=>100,'class'=>'p_name')); ?>
               <?php echo $form->error($model->Eat_Items,'name'); ?>
            </div>
          </div>
          <div class="row-fluid address_group">
            <label>详细地址：</label>
            <div class="col-province">
		<?php echo $form->dropDownList($model->Eat_Items,'area_id_p',Area::data_array_id(0,array(''=>'省')),array(
				'class'=>'province',
				'ajax'=>array(
				'type'=>'POST',
				'url'=>Yii::app()->createUrl('/agent/agent_home/area'),
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
            <div class="col-city">
		<?php 
			echo $form->dropDownList($model->Eat_Items,'area_id_m',Area::data_array_id($model->Eat_Items->area_id_p,array(''=>'市')),array(
			'class'=>'city',
			'ajax'=>array(
				'type'=>'POST',
				'url'=>Yii::app()->createUrl('/agent/agent_home/area'),
				'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
				'update'=>'#'.CHtml::activeId($model->Eat_Items,'area_id_c'),
			),
		)); ?>
		        	<?php echo $form->error($model->Eat_Items,'area_id_m'); ?>
            </div>
            <div class="col-area">
		<?php echo $form->dropDownList($model->Eat_Items,'area_id_c',Area::data_array_id($model->Eat_Items->area_id_m,array(''=>'区/县')),array(
			'class'=>'area',
		)); ?>
		  <?php echo $form->error($model->Eat_Items,'area_id_c'); ?>
            </div>
            <div class="col-address">
		<?php echo $form->textField($model->Eat_Items,'address',array('maxlength'=>40,'class'=>'address')); ?>      
		<?php echo $form->error($model->Eat_Items,'address'); ?>
            </div>
          </div>
          <div class="row-fluid address_uploading_group">
            <div class="address_img">
                <?php
   						 if(file_exists($model->Eat_Items->map))
							echo $this->show_img($model->Eat_Items->map);
   						 else{
    			?>
              <img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/items/modify_map.png">
              <?php }?>
            </div>
            <div class="file_normal">
			        <?php echo $form->fileField($model->Eat_Items,'map',array('class'=>'uploading')); ?>
			        <?php echo $form->error($model->Eat_Items,'map'); ?>
            </div>
          </div>
          <div class="row-fluid controls controls-fluid info_group">
            <label class="text_top">商品信息：</label>
            <div class="good_info">
              <table class="good_info_table">
                <tr>
                  <th>名称</th>
                  <th>类型</th>
                  <th colspan="2">价格(元)</th>
                </tr>
                <?php  foreach ($model->Eat_Fare as $key=>$Eat_Fare){?>	
								<tr>
								<td>
								 <div class="row-fluid col_name">
					<?php echo $form->textField($Eat_Fare,"[$key]name",array('placeholder'=>'商品名称','class'=>'table_box one')); ?>
                    <?php echo $form->error($Eat_Fare,"[$key]name"); ?>
                    </div>
								</td>
								<td>
				<div class="row-fluid col_type">
					<?php echo $form->textField($Eat_Fare,"[$key]info",array('placeholder'=>'类型','class'=>'table_box two')); ?>
                    <?php echo $form->error($Eat_Fare,"[$key]info"); ?>
                    </div>
								</td>
								<td>
 								 <div class="row-fluid col_price">
										<?php echo $form->textField($Eat_Fare,"[$key]price",array('placeholder'=>'价格','class'=>'table_box last')); ?>
										<button class="<?php echo $key>0?'cut_sub':'add_and_sub';?>" >
											<?php echo $key>0?'-':'+';?>
										</button>  
										<?php echo $form->error($Eat_Fare,"[$key]price"); ?>
									</div>
								</td>							
							</tr>
        	<?php  } ?>
              </table>
            </div>
          </div>
          <div class="row-fluid do_time">
            <label>营业时间：</label>
            <div class="col-start">
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
			'htmlOptions'=>array('class'=>'do_time'),
        ));
        ?><?php echo $form->error($model->Eat_Items,'start_work'); ?>

            </div>
            <div class="division"></div>
            <div class="col-stop">
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
        ?><?php echo $form->error($model->Eat_Items,'end_work'); ?>
            </div>
          </div>
          <div class="row-fluid original_general_group">
            <label>原概况图：</label>
            <div class="span7 general_img">
            	<?php
	//更新上传的图片
		if(!empty($model->Eat_ItemsImg) && is_array($model->Eat_ItemsImg))
		{
			foreach ($model->Eat_ItemsImg as $key_img=>$ItemsImg){
			?>
			  <div class="detail_img">
			  		<?php 
			  			echo $this->show_img($ItemsImg->img);
			  			echo $form->hiddenField($ItemsImg,"[$key_img]id");
					?>
                	<a  href="#" class="delete_img"><img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/items/delete.png"></a>
              </div>
	<?php
			 } 
		 }
		 ?>
            </div>
          </div>
          <div class="row-fluid general_group">
            <label>概况图：</label>
            <div class="general_uploading">
              <div class="general_btn">
                <div>
                	<?php echo $form->fileField($Eat_ItemsImg,'tmp',array('class'=>'btn')); ?>
					<?php echo $form->error($Eat_ItemsImg,'tmp'); ?>
                  <span>请上传不超过5M的png、jpg、jpeg、bmp图片</span>
                </div>
              </div>
            </div>
          </div>
          <input id="old_uploadify" type="hidden" value=""  name="uploadify">
          <div class="row-fluid intro_group">
            <label>餐厅简介：</label>
            <div class="rich_text_editor">
            <?php
				    $this->renderPartial('/_common/_html',array(
				        'form'=>$form,
				        'width'=>'100%',
				        'height'=>'220px',
				        'model'=>$model->Eat_Items,
				        'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
				        'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
				        'attribute'=>'content',
				    ));
    		?>            
            </div>
          </div>
          <div class="clear"></div>
          <div class="row-fluid">
            <label class="text_top">联系方式：</label>
            <div class="contact_group">
              <div class="row-fluid phone_icon">
                     <?php echo $form->textField($model->Eat_Items,'phone',array('maxlength'=>20)); ?>
      				 <?php echo $form->error($model->Eat_Items,'phone'); ?>
              </div>
              <div class="weixin_icon">
             	     <?php echo $form->textField($model->Eat_Items,'weixin',array('maxlength'=>20)); ?>
      				 <?php echo $form->error($model->Eat_Items,'weixin'); ?>
              </div>
            </div>
          </div>
          <div class="row-fluid-fluid enter">
            <div class="span1"></div>
            <?php echo CHtml::submitButton('下一步'); ?>
          </div>
     <?php $this->endWidget(); ?>
      </div>
      <!-- .content_two -->
    </div>
    <!--  .content -->
    <div class="copyright">
      <span>Copyright &copy; TMM365.com All Rights Reserved</span>
    </div>
    <!--.copyright-->
  </div>
  
  <table id="fare_tpl" style="display: none">
	<tr>
		<td>
			<div class="row-fluid col_name">
				<input placeholder="商品名称" class="table_box one" name="Fare[1][name]" id="Fare_1_name" type="text" maxlength="24" value="">
			</div>
		</td>
		<td>
			<div class="row-fluid col_type">
				<input placeholder="类型" class="table_box two" name="Fare[1][info]" id="Fare_1_info" type="text" maxlength="64" value="">
			</div>
		</td>
		<td>
			<div class="row-fluid col_price">
				<input placeholder="价格" class="table_box last" name="Fare[1][price]" id="Fare_1_price" type="text" maxlength="13" value="0.00">
				<button class="cut_sub">
					-
				</button>
			</div>
		</td>
	</tr>
</table>
<?php
$csrfTokenName='';
$csrfToken='';
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
    var limit_number=<?php 
	$nunber=Yii::app()->params['items_image_number']-count($model->Eat_ItemsImg);
    echo $nunber>0?$nunber:0; 
    ?>;
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
            'uploader': '<?php echo Yii::app()->createUrl('/agent/agent_eat/uploads')?>&<?php echo $csrf; ?>',
            'auto': true,
            'multi': true,
			'uploadLimit' : <?php echo Yii::app()->params['items_image_number']; ?>,
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
				//console.log('The data ' + data + ' .');
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
					old_uploadify();
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
					url: '<?php echo Yii::app()->createUrl('/agent/agent_eat/uploads')?>&<?php echo $csrf; ?>',
					data: {'file_name': thisObj.attr('title'),'<?php echo $csrfTokenName?>':'<?php echo $csrfToken?>'},
					success: function (data) {
						if(data==1) {
							var queueId = thisObj.parent().parent().attr('id');
							// 移除整个div
							$('#<?php echo CHtml::activeId($Eat_ItemsImg, 'tmp');?>').uploadify('cancel', queueId);
							eq_num--;
							old_uploadify();
							update_number(++limit_number);				
						}
					}
				});
			});
		}
		function update_number(limit_number){
			$("#<?php echo CHtml::activeId($Eat_ItemsImg, 'tmp');?>").uploadify('settings','uploadLimit',limit_number);							
		}
		function old_uploadify(){
			setTimeout(function(){
				jQuery("#old_uploadify").val(jQuery("#<?php echo CHtml::activeId($Eat_ItemsImg, 'tmp');?>-queue").html());
			}, 2000);
		}

		function sortFareEle() {
			jQuery('.good_info_table tbody').find('tr:gt(0)').each(function(index, ele) {
				var tdList = jQuery(ele).find('td');
				tdList.eq(0).find('input').eq(0).attr('name', 'Fare[' + index + '][name]').attr('id', 'Fare_' + index + '_name');
				tdList.eq(1).find('input').eq(0).attr('name', 'Fare[' + index + '][info]').attr('id', 'Fare_' + index + '_info');
				tdList.eq(2).find('input').eq(0).attr('name', 'Fare[' + index + '][price]').attr('id', 'Fare_' + index + '_price');
			});
		}
		jQuery(document).on('click', '.good_info_table .add_and_sub', function() {
			var tableObj = jQuery('.good_info_table tbody');
			if(tableObj.find('tr').length-1 >=<?php echo Yii::app()->params['items_fare_number']; ?>) {
				alert('项目价格的数量最多只能有<?php echo Yii::app()->params['items_fare_number']; ?>个');
				return false;
			}
			jQuery('#fare_tpl').find('tr').eq(0).clone().appendTo(tableObj);
			sortFareEle();
			return false;
		});
		jQuery(document).on('click', '.good_info_table .cut_sub', function() {
			jQuery(this).parent().parent().parent().remove();
			sortFareEle();
			return false;
		});
		jQuery(document).on('click', '.general_img .delete_img', function() {
			if(confirm("确定要删除吗？")){
				jQuery(this).parent().remove();
				update_number(++limit_number);
			}
			return false;
		});
	});
    /*]]>*/
</script>