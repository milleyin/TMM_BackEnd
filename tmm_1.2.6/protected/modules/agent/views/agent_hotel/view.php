<div class="content_box" id="page_project_detail">
    <?php
        echo $this->breadcrumbs(array(
            '项目'=>array('/agent/agent_items/admin'),
            '项目详情',
            $model->Hotel_Items->name
        ));
    ?>
    <div class="content_div">
        <div class="box">
            <div class="account_table">
                <table border="0" class="account_info">
                    <tbody>
                    <tr>
                        <td>项目名称：</td>
                        <td>
                            <?php echo CHtml::encode($model->Hotel_Items->name); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>所属商家：</td>
                        <td>
                            <?php echo CHtml::encode($model->Hotel_Items->Items_StoreContent->name); ?>
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="text_top">详细地址：</td>
                        <td>
                            <?php echo CHtml::encode(
                            	$model->Hotel_Items->Items_area_id_p_Area_id->name .
                                $model->Hotel_Items->Items_area_id_m_Area_id->name .
                                $model->Hotel_Items->Items_area_id_c_Area_id->name .
                                $model->Hotel_Items->address); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="detail_addr_img">
                                <?php echo $this->show_img($model->Hotel_Items->map);?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>项目类型：</td>
                        <td>
                            <?php echo CHtml::encode($model->Hotel_ItemsClassliy->name);?>
                        </td>
                    </tr>
                    <tr>
                        <td>审核状态：</td>
                        <td class="<?php echo $model->Hotel_Items->audit == Items::audit_pass ? 'state_normal' : 'state' ?>">
                            <?php echo Items::$_audit[$model->Hotel_Items->audit];?>
                        </td>
                    </tr>
                    <tr>
                        <td>项目标签：</td>
                        <td>
                        <?php if(empty($model->Hotel_TagsElement)) { ?>
                            <span class="state">未设置</span>
                        <?php } else { ?>
                            <?php foreach($model->Hotel_TagsElement as $tags) { ?>
                                <div class="tag_img one">
                                    <img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/tag_bg.png">
                                    <span><?php echo CHtml::encode($tags->TagsElement_Tags->name); ?></span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text_top">商品信息：</td>
                        <td>
                            <ul>
                            <?php foreach($model->Hotel_Fare as $fare) { ?>
                                <li>
                                    <span><?php echo CHtml::encode($fare->name); ?></span>
                                    <span><?php echo CHtml::encode($fare->info); ?>平方</span>
                                    <span><?php echo CHtml::encode($fare->number); ?>人间</span>
                                    <span class="last"><?php echo CHtml::encode($fare->price); ?>元</span>
                                </li>
                            <?php } ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>项目服务：</td>
                        <td>
                            <?php if(empty($model->Hotel_ItemsWifi)) { ?>
                                <span class="state">未设置</span>
                            <?php } else { ?>
                                <?php foreach($model->Hotel_ItemsWifi as $wifi) { ?>
                                    <div class="tag_img one">
                                        <?php echo $this->show_img($wifi->ItemsWifi_Wifi->icon,'','',array('style'=>'height:30px;width:30px;','title'=>$wifi->ItemsWifi_Wifi->name));?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>营业时间：</td>
                        <td>
                            <?php echo CHtml::encode($model->Hotel_Items->start_work);?>
                            -
                            <?php echo CHtml::encode($model->Hotel_Items->end_work);?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text_top">概况图 ：</td>
                        <td>
                            <div class="general_img">

                            <?php foreach($model->Hotel_ItemsImg as $val) { ?>
                                <div class="detail_img">
                                    <?php echo $this->show_img($val->img);?>
                                </div>
                            <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text_top">餐厅简介：</td>
                        <td class="brief_intro">
                            <?php echo $model->Hotel_Items->content; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text_top">联系方式：</td>
                        <td>
                            <div class="phone_icon"><span><?php echo CHtml::encode($model->Hotel_Items->phone) ; ?></span></div>
                            <div class="weixin_icon"><span><?php echo CHtml::encode($model->Hotel_Items->weixin) ; ?></span> </div>
                        </td>
                    </tr>
                    <tr>
                        <td>发布时间：</td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$model->Hotel_Items->pub_time) ;?>
                        </td>
                    </tr>
                    <tr>
                        <td>最后编辑时间 ：</td>
                        <td>
                            <?php echo date('Y-m-d H:i:s',$model->Hotel_Items->up_time) ;?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div> <!--  .box -->
    </div>   <!-- .box_div -->
    <div class="copyright">
        <span>Copyright &copy; TMM365.com All Rights Reserved</span>
    </div>  <!--.copyright-->
</div>  <!--.content_div-->