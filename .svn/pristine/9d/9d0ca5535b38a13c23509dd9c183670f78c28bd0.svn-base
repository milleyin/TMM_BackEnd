<div class="container-fluid content_box scenic_spot">
    <?php
    echo $this->breadcrumbs(array(
        '景点管理'
    ));
    ?><!--.title-->
    <div class="content_div">
        <ul class="breadcrumb my_breadcrumb">
            <li>
                <?php echo CHtml::link('我的点'.($count['dot']!=0?'（<div class="num">'.$count['dot'].'</div>）':''), array('/agent/agent_dot/admin'), array('target'=>'content')); ?>
                <span></span>
            </li>
            <li class="bottom_line">
                <?php echo CHtml::link('审核未通过'.($count['nopass']!=0?'（<div class="num">'.$count['nopass'].'</div>）':''), array('/agent/agent_dot/admin_no_pass'), array('target'=>'content')); ?>
                <span></span>
            </li>
            <li>
                <?php echo CHtml::link('草稿'.($count['draft']!=0?'（<div class="num">'.$count['draft'].'</div>）':''), array('/agent/agent_dot/admin_draft'), array('target'=>'content')); ?>
                <span></span>
            </li>
        </ul>
        <div class="main_div">
            <div class="content content_one scenic_spot">
                <div class="controls controls-row  query" id="items_search">
                    <?php
                    $form=$this->beginWidget('CActiveForm', array(
                        'action'=>Yii::app()->createUrl($this->route),
                        'method'=>'get',
                    ));
                    ?>
                    <div class="input-append element_group span3">
                        <input type="text" name="search_info" placeholder="景点名称" class="span3">
                        <?php echo CHtml::submitButton('',array('class'=>'add-on element_icon search', 'style'=>'z-index:9999;'));?>
                    </div>
                    <?php $this->endWidget(); ?>
                    <?php echo CHtml::link('创建点',array('/agent/agent_dot/create'),array('class'=>'create pull-right'))?>
                </div>  <!-- .query -->
                <div class="main_content data" id="container1">
                    <?php

Yii::app()->clientScript->registerScript('search', "
$('#items_search form').submit(function(){
		 $.fn.yiiListView.update(
			'items_list',{'data':\$(this).serialize()}
		);
	return false;
});
 jQuery('.items').imagesLoaded( function(){
	jQuery('.items').masonry({
 		itemSelector : '.item_box',
 		gutterWidth : 20,
 		isAnimated: true,
    });
 });	    
function reload_masonry(){
	jQuery('.items').imagesLoaded( function(){
		jQuery('.items').masonry({
	 		itemSelector : '.item_box',
	 		gutterWidth : 20,
	 		isAnimated: true,
   		 });
 	});
}
");
$summaryText=<<<"EOD"
<div class="summary">
    <div class="text">
        <script type="text/javascript">
			jQuery(function($) {
			jQuery(".text").html('<span>'+({end}-{start}+1)+'条数据/共{pages}页</span>');
			})
		</script>
    </div>
</div>
EOD;
                    $this->widget('zii.widgets.CListView', array(
                        'id'=>'items_list',
                        'dataProvider'=>$model,
                        'itemView'=>'_list_no_pass',
						'afterAjaxUpdate'=>'reload_masonry',
                        'template'=>"{items}\n{summary}\n{pager}",
                        'enableHistory'=>true,
                        'summaryText'=>$summaryText,
                        'emptyText'=>'<p>千里之行，始于足下，一切从创建点开始！</p>',
                        'pager'=>array(
                            'class'=>'CLinkPager',
                            'header'=>'',
                            'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
                        ),
                        'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/style.css'
                    ));

                    ?>
                </div>  <!-- .main_content -->
            </div> <!-- .content_one -->
        </div>
    </div>  <!-- .content_div -->
    <div class="copyright">
        <span>Copyright &copy; TMM365.com All Rights Reserved</span>
    </div>  <!--.copyright-->
</div>  <!--.content_box-->
<?php
if(Yii::app()->request->enableCsrfValidation)
{
    $csrfTokenName = Yii::app()->request->csrfTokenName;
    $csrfToken = Yii::app()->request->csrfToken;
    $csrf = "{'$csrfTokenName':'$csrfToken'}";
}else
    $csrf = "{}";

$click=<<<EOD
jQuery(document).on('click','#items_list a.delete',function() {
    if(!confirm('确定要删除这条数据吗?')) return false;
    var th = this,
        afterDelete = function(){};
    $.fn.yiiListView.update('items_list', {
        type: 'POST',
        url: jQuery(th).attr('href'),
        data:$csrf,
        success: function(data) {
        	$.fn.yiiListView.update('items_list');
        }
    });
return false;
});
EOD;
Yii::app()->clientScript->registerScript('delete', $click);
?>