<div class="container-fluid content_box scenic_spot together_tour">
    <?php
    echo $this->breadcrumbs(array(
        '结伴游'
    ));
    ?><!--.title-->
    <div class="content_div">
        <div class="main_div">
            <div class="content scenic_spot line_all">
                <div class="controls controls-row  query" id="items_search">
                    <?php
                    $form=$this->beginWidget('CActiveForm', array(
                        'action'=>Yii::app()->createUrl($this->route),
                        'method'=>'get',
                    ));
                    ?>
                        <select class="span2" name="search_status">
                            <option value="99">全部</option>
                            <option value="<?php echo Group::group_already_peer; ?>">已结伴</option>
                            <option value="<?php echo Group::group_no_peer; ?>">未结伴</option>
                            <option value="<?php echo Group::group_peering; ?>">结伴中</option>
                            <option value="<?php echo Group::group_user_confirm; ?>">用户确认中</option>
                            <option value="<?php echo Group::group_store_confirm; ?>">商家确认中</option>
                            <option value="<?php echo Group::group_cancel; ?>">已取消</option>
                        </select>
                        <div class="input-append element_group span3">
                            <input type="text" name="search_info" placeholder="店铺/商家/地址" class="span3">
                            <?php echo CHtml::submitButton('',array('class'=>'add-on element_icon search'));?>
                        </div>
                    <?php $this->endWidget(); ?>
                </div>
                <!-- .query -->
                <div class="main_content data">
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
    <div class="page_text">
       	<script type="text/javascript">
     	jQuery(function($) {
	     	 jQuery(".page_text").html('<span>'+({end}-{start}+1)+'条数据/共{pages}页</span>');
	      })
    	</script>
    </div>
</div>
EOD;
                    $this->widget('zii.widgets.CListView', array(
                        'id'=>'items_list',
                        'dataProvider'=>$model,
                        'itemView'=>'_list',
						'afterAjaxUpdate'=>'reload_masonry',
                        'template'=>"{items}\n{summary}\n{pager}",
                        'enableHistory'=>true,
                        'summaryText'=>$summaryText,
                        'emptyText'=>'暂无数据',
                        'pager'=>array(
                            'class'=>'CLinkPager',
                            'header'=>'',
                            'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
                        ),
                        'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/style.css'
                    ));

                    ?>
                </div>
                <!-- .main_content -->
            </div>
            <!-- .content_one -->
        </div>
        <!-- .main_div -->
    </div>
    <!-- .content_div -->
    <div class="copyright">
        <span>Copyright &copy; TMM365.com All Rights Reserved</span>
    </div>
    <!--.copyright-->
</div>
<!--.content_box-->