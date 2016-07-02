<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '公司认证'=>array('company'),
        '公司信息'
    ));
    ?>
    <div class="box_div">
        <div style="visibility:hidden"><span>公司信息</span></div>
        <div class="title2"><span>公司信息</span></div>
        <div class="box1 box_one">
            <table border="0" class="de">
                <tr>
                    <td>公司名称：</td>
                    <td><?php echo CHtml::encode($model->firm_name); ?></td>
                </tr>
                <tr>
                    <td>公司地址：</td>
                    <td><?php echo CHtml::encode($model->address); ?></td>
                </tr>
                <tr>
                    <td>公司电话：</td>
                    <td><?php echo CHtml::encode($model->firm_tel); ?></td>
                </tr>
                <tr>
                    <td>公司邮编：</td>
                    <td><?php echo CHtml::encode($model->firm_postcode); ?></td>
                </tr>
                <tr>
                    <td>营业执照编码：</td>
                    <td><?php echo CHtml::encode($model->bl_code); ?></td>
                </tr>
                <tr class="top">
                    <td ><span>营业执照扫描件：</span></td>
                    <td>
                        <div class="photo">
                            <?php
                            if(isset($model->bl_img) && $model->bl_img)
                               echo $this->show_img($model->bl_img);
                            else
                                echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/business/business.png');
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div> <!--  .box -->
    </div>   <!-- .box_div --><!--虚线框架-->
    <div class="box_div">
        <div style="visibility:hidden"><span>公司法人</span></div>
        <div class="title2"><span>公司法人</span></div>
        <div class="box1 box_one">
            <table border="0" class="de">
                <tr>
                    <td>公司法人：</td>
                    <td><?php echo CHtml::encode($model->com_contacts); ?></td>
                </tr>
                <tr>
                    <td>证份证号码：</td>
                    <td><?php echo CHtml::encode($model->com_identity); ?></td>
                </tr>
                <tr>
                    <td>联系电话：</td>
                    <td><?php echo CHtml::encode($model->com_phone); ?></td>
                </tr>
            </table>
        </div> <!--  .box -->
    </div>   <!-- .box_div --><!--虚线框架-->
    <div class="box_div">
        <div style="visibility:hidden"><span>公司负责人</span></div>
        <div class="title2"><span>公司负责人</span></div>
        <div class="box1 box_one">
            <table border="0" class="de">
                <tr>
                    <td>公司负责人：</td>
                    <td><?php echo CHtml::encode($model->manage_name); ?></td>
                </tr>
                <tr>
                    <td>联系电话：</td>
                    <td><?php echo CHtml::encode($model->manage_phone); ?></td>
                </tr>
                <tr>
                    <td>证份证号码：</td>
                    <td><?php echo CHtml::encode($model->manage_identity); ?></td>
                </tr>
                <tr class="top">
                    <td><span>负责人身份证照片：</span></td>
                    <td>
                        <div class="photo">
                            <?php
                            if(isset($model->identity_before) && $model->identity_before)
                                echo $this->show_img($model->identity_before);
                            else
                                echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/business/identity_card_1.png');
                            ?>
                        </div>
                        <span class="text">正面</span>
                    </td>
                    <td>
                        <div class="photo">
                            <?php
                            if(isset($model->identity_after) && $model->identity_after)
                                echo $this->show_img($model->identity_after);
                            else
                                echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/business/identity_card_2.png');
                            ?>
                        </div>
                        <span class="text">反面</span>
                    </td>
                </tr>
                <tr class="top">
                    <td><span>负责人手执身份证照片：</span></td>
                    <td>
                        <div class="photo">
                            <?php
                            if(isset($model->identity_hand) && $model->identity_hand)
                                echo $this->show_img($model->identity_hand);
                            else
                                echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/business/person.png');
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div> <!--  .box -->
    </div>   <!-- .box_div --><!--虚线框架-->
</div>