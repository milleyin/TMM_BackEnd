<div class="content_box scenic_spot line_detail_pack" id="spot_detail_pack">
    <?php
    echo $this->breadcrumbs(array(
        '结伴游'=>array('/agent/agent_group/admin'),
        '结伴游详情',
        CHtml::encode($model->Group_Shops->name)
    ));
    ?><!--.title-->
    <div class="content_div">
        <div class="box_div">
            <div style="visibility:hidden"><span>详情介绍</span></div>
            <div class="title"><span>详情介绍</span></div>
            <div class="box">
                <div class="account_table">
                    <table border="0" class="account_info">
                        <tbody>
                        <tr>
                            <td>结伴游名称：</td>
                            <td><?php echo CHtml::encode($model->Group_Shops->name); ?></td>
                        </tr>
                        <tr>
                            <td>组织者：</td>
                            <td><?php echo CHtml::encode($model->Group_User->nickname); ?></td>
                        </tr>
                        <tr>
                            <td>报名截止时间：</td>
                            <td><?php echo CHtml::encode(date('Y年m月d日', $model->end_time)); ?></td>
                        </tr>
                        <tr>
                            <td>出团时间：</td>
                            <td><?php echo CHtml::encode(date('Y年m月d日', $model->group_time)); ?></td>
                        </tr>
                        <tr>
                            <td>服务费：</td>
                           <td><?php echo CHtml::encode($model->price); ?> 元</td>
                        </tr>
                        <tr>
                            <td>下单量：</td>
                            <td>待查...</td>
                        </tr>
                        <tr>
                            <td>浏览量：</td>
                            <td>
                                <?php echo CHtml::encode($model->Group_Shops->brow); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>分享量：</td>
                            <td><?php echo CHtml::encode($model->Group_Shops->share); ?></td>
                        </tr>
                        <tr>
                            <td>点赞量：</td>
                            <td><?php echo CHtml::encode($model->Group_Shops->praise); ?></td>
                        </tr>
                        <tr>
                            <td>审核状态：</td>
                            <td class="<?php echo $model->Group_Shops->audit == Shops::audit_pass ? 'state_normal' : 'state'  ?>">
                                <?php echo CHtml::encode(Shops::$_audit[$model->Group_Shops->audit]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>出团状态：</td>
                            <td class="<?php echo $model->group == Group::group_cancel ? 'state' : 'state_normal'  ?>">
                                <?php echo CHtml::encode(Group::$_group_status[$model->group]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>发布状态：</td>
                            <td class="<?php echo $model->Group_Shops->status == Shops::status_online ? 'state_normal' : 'state'  ?>">
                                <?php echo CHtml::encode(Shops::$_status[$model->Group_Shops->status]); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>结伴游标签：</td>
                            <td>
                                <?php  foreach($model->Group_TagsElement as $v){ ?>
                                    <div class="tag_img one">
                                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/tag_bg.png"><span><?php echo CHtml::encode($v->TagsElement_Tags->name); ?></span>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text_top">列表页显示：</td>
                            <td class="brief_intro">
                                <div class="brief_intro_img">
                                    <?php
                                    if($model->Group_Shops->list_img)
                                        echo $this->show_img($model->Group_Shops->list_img);
                                    else
                                        echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/items/brief_intro.png');
                                    ?>
                                </div>
                                <div class="intro">
                                    <?php echo CHtml::encode($model->Group_Shops->list_info); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text_top">详情页显示：</td>
                            <td class="brief_intro">
                                <div class="brief_intro_img">
                                    <?php
                                    if($model->Group_Shops->page_img)
                                        echo $this->show_img($model->Group_Shops->page_img);
                                    else
                                        echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/items/brief_intro.png');
                                    ?>
                                </div>
                                <div class="intro">
                                    <?php echo CHtml::encode($model->Group_Shops->page_info); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>备注：</td>
                            <td>
                                <?php echo CHtml::encode($model->remark); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>发布时间：</td>
                            <td><?php echo CHtml::encode(date('Y-m-d H:i:s' ,$model->Group_Shops->pub_time)); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--  .box -->
        </div>
        <!-- .box_div -->
        <?php
        if(isset($model->Group_Pro))
            $this->renderPartial('_list_view', array(
                'model'=>$model,
            ));
        ?>
        <div class="copyright">
            <span>Copyright &copy; TMM365.com All Rights Reserved</span>
        </div>
        <!--.copyright-->
    </div>
    <!--.content_div-->