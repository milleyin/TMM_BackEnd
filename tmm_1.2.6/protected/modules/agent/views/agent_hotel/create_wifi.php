<div class="content_box" id="page_project_create">
    <?php
    echo $this->breadcrumbs(array(
        '项目'=>array('admin'),
        '项目创建',
        $model->name,
    ));
    ?>
    <!--.title-->
    <div class="create_nav modify_project_info">
        <div class="create_steap_one five_steaps_width">
            <a class="done">
                1
            </a>
			<span class="text_done">
				选择归属商家
			</span>
            <div class="line line_first done">
            </div>
        </div>
        <div class="create_steap_two five_steaps_width">
            <a class="done">
                2
            </a>
			<span class="text_done">
				填写项目信息
			</span>
            <div class="line done">
            </div>
        </div>
        <div class="create_steap_three five_steaps_width">
            <a class="done">
                3
            </a>
			<span class="text_done">
				选择服务
			</span>
            <div class="line done">
            </div>
        </div>
        <div class="create_steap_four five_steaps_width">
            <a class="undone">
                4
            </a>
			<span class="text_undone">
				选择标签
			</span>
            <div class="line undone">
            </div>
        </div>
        <div class="create_steap_five five_steaps_width">
            <a class="undone">
                5
            </a>
			<span class="text_undone">
				提交审核
			</span>
            <div class="line line_last undone">
            </div>
        </div>
    </div>
    <!-- .create_nav -->
    <div class="content">
        <div class="content_three">
            <div class="row-fluid choose_tag">
                <div class="span3">
                </div>
                <table border="0" class="span7 choose_tag">
                    <tbody>
                    <tr>
                        <td>
                            项目名称：
                        </td>
                        <td class="no_first">
                            <?php echo CHtml::encode($model->name);?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            项目服务：
                        </td>
                        <td class="tag_img_group already_tags">
                            <?php
                            foreach($model->Items_Hotel->Hotel_ItemsWifi as $v) {
                                ?>
                                <div class="tag_img selected" title="点击移除" style="cursor:pointer;">
                                    <?php echo $this->show_img($v->ItemsWifi_Wifi->icon,'','',array('style'=>'height:40px;width:40px;','title'=>$v->ItemsWifi_Wifi->name));?>
                                    <span class="wifi_id" style="display:none"><?php echo CHtml::encode($v->wifi_id);?></span>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="row-fluid">
                </div>
                <div class="row-fluid box_div">
                    <div class="span3">
                    </div>
                    <div class="title">
						<span>
							选择服务
						</span>
                    </div>
                    <div class="span7 box box_two">
                        <?php
                        $this->widget('zii.widgets.CListView', array(
                            'id'=>'items_list',
                            'dataProvider'=>$wifi_model,
                            'itemView'=>'_wifi',
                            'template'=>"{items}\n{summary}\n{pager}",
                            'viewData'=>array('select_id'=>$model->id),
                            'pager'=>array(
                                'class'=>'CLinkPager',
                                'header'=>'',
                                'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
                            ),
                        ));
                        ?>
                    </div>
                    <!-- .box -->
                </div>
                <!-- .box_div -->
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id'=>'items-form',
                    'enableClientValidation'=>true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true
                    ),
                ));
                echo $form->hiddenField($model,'id');
                ?>
                <div class="row-fluid enter choose_tag_group">
                    <?php echo CHtml::submitButton('下一步',array('id'=>'choose_tag')); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
    <!-- .content_three -->
    <!-- .content -->
    <div class="copyright">
		<span>
			Copyright &copy; TMM365.com All Rights Reserved
		</span>
    </div>
    <!--.copyright-->
</div>

<?php
if(Yii::app()->request->enableCsrfValidation)
{
    $csrfTokenName = Yii::app()->request->csrfTokenName;
    $csrfToken = Yii::app()->request->csrfToken;
    $csrf = "\n\t\tdata:{'$csrfTokenName':'$csrfToken','wifi_ids':wifi_ids,'type':type},\n";
}else
    $csrf = "\n\t\tdata:{'wifi_ids':wifi_ids,'type':type},\n";
$url=Yii::app()->createUrl('/agent/agent_hotel/upwifi',array('id'=>$model->id));

Yii::app()->clientScript->registerScript('search', "
jQuery('body').on('click','.optional',function(){
	var type='yes';
	var wifi_ids=jQuery(this).find('.wifi_id').html();
	this_click=jQuery(this);
	html=this_click.html();
	jQuery.ajax({
		'cache':true,
		'type':'POST',$csrf 		'url':'$url',
		'success':function(data){
			if(data !=1)
				alert(data);
			else{
				jQuery('.already_tags').append('<div class=\"tag_img one selected\" style=\"cursor:pointer;\" title=\"点击移除\">'+html+'</div>');
				this_click.removeClass('optional');  
        	 	this_click.addClass('selected');  
        	 	this_click.attr('title','已选择');
        	 	this_click.find('img').css('opacity',0.5);
        	 	this_click.find('img').attr('src',this_click.find('img').attr('src'));
			}
		},
	});
	return false;
});     
jQuery('body').on('click','.already_tags .selected',function(){
	var type='no';
	var wifi_ids=jQuery(this).find('.wifi_id').html();
	this_click=jQuery(this);
	jQuery.ajax({
		'cache':true,
		'type':'POST',$csrf 		'url':'$url',
		'success':function(data){
			if(data !=1)
				alert(data);
			else{
				this_click.remove();
				var ids=new Array();
				jQuery('.items .wifi_id').each(function (){
						ids.push($(this).html());
				});
				if(jQuery.inArray(wifi_ids, ids) != -1){
					selected=jQuery('.items .tag_img').eq(jQuery.inArray(wifi_ids, ids));
					selected.removeClass('selected');  
        	 		selected.addClass('optional');  
        	 		selected.attr('title','点击添加');
        	 	    this_click.find('img').css('opacity', 1);
				}
			}
		},
	});
	return false;
});   
		");

?>