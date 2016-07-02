
<div class="content_box scenic_spot" id="spot_create">
    <?php
    echo $this->breadcrumbs(array(
        $model->Shops_ShopsClassliy->name=>array('/agent/agent_'.$model->Shops_ShopsClassliy->admin.'/admin'),
        $model->add_time==$model->up_time? $model->Shops_ShopsClassliy->name.'创建':$model->Shops_ShopsClassliy->name.'编辑',
        $model->name,
    ));
    ?>
    <div class="create_nav create_sub_business_nav">
        <div class="create_steap_one">
            <a class="done">1</a>
            <span class="text_done">选择项目</span>
            <div class="line line_first done"></div>
        </div>
        <div class="create_steap_two">
            <a class="done">2</a>
            <span class="text_done">添加标签</span>
            <div class="line done"></div>
        </div>
        <div class="create_steap_five">
            <a class="done last">3</a>
            <span class="text_done">提交审核</span>
            <div class="line line_last done last"></div>
        </div>
    </div>    <!-- .create_nav -->
    <div class="content create_spot">
        <div class="content_five">
            <div class="box_div">
                <div class="box box_one">
                    <img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/success.png" class="head_img">
                    <div class="right">
                        <div class="business_name">
                            <span class="big"><?php echo CHtml::encode($model->Shops_ShopsClassliy->info); ?>编辑成功，已提交平台审核</span>
                            <span class="small">审核进度请致电：<?php echo Yii::app()->params['tmm_400']?>咨询</span>
                        </div>
                    </div> <!-- .right -->
                </div> <!--  .box -->
            </div>
            <div class="row enter">
                <a id="back_business_site_accmangr" href="<?php echo Yii::app()->createUrl('/agent/agent_'.CHtml::encode($model->Shops_ShopsClassliy->admin).'/admin')?>">
                    <span>返回</span>
                </a>
            </div>
        </div>  <!-- .content_five -->
    </div>   <!--  .content -->

</div>  <!--.content_box-->
