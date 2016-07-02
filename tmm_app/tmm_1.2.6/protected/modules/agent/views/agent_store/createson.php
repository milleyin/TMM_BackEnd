<div class="content_box">

    <?php
    echo $this->breadcrumbs(array(
        '商家子账号'=>array('adminSon'),
        '新增商家子账号',
    ));
    ?>
    <!--.title-->
    <div class="create_nav create_sub_business_nav">
        <div class="create_steap_one">
            <a class="done">1</a>
            <span class="text_done">选择归属商家</span>
            <div class="line line_first done"></div>
        </div>
        <div class="create_steap_two">
            <a class="undone">2</a>
            <span class="text_undone">填写账号信息</span>
            <div class="line undone"></div>
        </div>
        <div class="create_steap_five">
            <a class="undone">3</a>
            <span class="text_undone">完成</span>
            <div class="line line_last undone"></div>
        </div>
    </div>    <!-- .create_nav -->
    <div class="content create_business_content">
        <div class="content_one">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'store-user-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true
                ),
                'htmlOptions'=>array('class'=>'business_info_form'),
            ));
            ?>
            <div class="box_div">
                <div class="choose">

                    <label>选择归属商家：</label>
                    <?php
                    //$dropDownList_array=StoreContent::data(true,array());
                    //$dropDownList_array_one=current($dropDownList_array);
                    ?>
                    <?php echo $form->dropDownList($model,'id',StoreContent::data(true,array())); ?>
                </div>
                <div class="box">
                    <div class="choose_business_table">

                    </div>
                </div>
            </div>   <!-- .box_div -->
            <div class="row enter">
                <?php echo CHtml::submitButton('下一步'); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>  <!-- .content_one -->
    </div>   <!--  .content -->

    <div class="copyright">
        <span>Copyright &copy; TMM365.com All Rights Reserved</span>
    </div>  <!--.copyright-->
</div>  <!--.content_box-->

<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
        jQuery('body').on('change','#StoreContent_id',function(){jQuery.ajax({'type':'POST','url':'<?php echo Yii::app()->createUrl('/agent/agent_store/store_info'); ?>','dataType':'json','data':{'id':this.value,'<?php echo Yii::app()->request->csrfTokenName; ?>':'<?php echo Yii::app()->request->csrfToken;?>'},'success':function(data){
            jQuery(".choose_business_table").html(data);
        },'cache':false});return false;});

        jQuery('body #StoreContent_id').trigger('change');

    });
    /*]]>*/
</script>