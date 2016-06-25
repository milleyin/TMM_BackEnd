<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '商家子账号'=>array('adminSon'),
        '新增商家子账号',
    	$model->phone,
    ));
    ?>  <!--.title-->
    <div class="create_nav create_sub_business_nav">
        <div class="create_steap_one">
            <a class="done">1</a>
            <span class="text_done">选择归属商家</span>
            <div class="line line_first done"></div>
        </div>
        <div class="create_steap_two">
            <a class="done">2</a>
            <span class="text_done">填写账号信息</span>
            <div class="line done"></div>
        </div>
        <div class="create_steap_five">
            <a class="done last">3</a>
            <span class="text_done">完成</span>
            <div class="line line_last done last"></div>
        </div>
    </div>    <!-- .create_nav -->
    <div class="content create_business_content">
        <div class="content_five">
            <div class="box_div">
                <div class="box box_one">
                    <img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/success.png" class="head_img">
                    <div class="right">
                        <div class="business_name">
                            <span class="big">提交成功，账户启用后方可使用</span>
                            <span class="small">如有疑问请致电：<?php echo Yii::app()->params['tmm_400'] ?>咨询</span>
                        </div>
                    </div> <!-- .right -->
                </div> <!--  .box -->
            </div>
            <div class="row enter">
                <?php echo CHtml::link('返回子账号',array("/agent/agent_store/adminSon")); ?>
                <?php echo CHtml::link('继续添加',array("/agent/agent_store/createSon")); ?>
            </div>
        </div>  <!-- .content_five -->
    </div>   <!--  .content -->

    <div class="copyright">
        <span>Copyright &copy; TMM365.com All Rights Reserved</span>
    </div>  <!--.copyright-->
</div>  <!--.content_box-->