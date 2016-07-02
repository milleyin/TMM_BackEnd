
<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '账号信息'=>array('account'),
        '财务信息'
    ));
    ?>
    <div class="content_top">
        <ul>
            <li><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/account')?>">账号详情</a></li>
            <li class="top2"><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/bank')?>">财务信息</a><span></span></li>
            <li><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/deposit')?>">保证金</a></li>
            <div class="clear"></div>
        </ul>
        <hr />
    </div>  <!-- .content_top -->

        <?php
            if( isset($model->bank_id) && $model->bank_id ){
          ?>
                <div class="box_div">
                    <div style="visibility:hidden"><span>银行卡银行</span></div>
                    <div class="title2"><span>银行卡银行</span></div>
                    <div class="box1 box_one">
                        <table border="0" class="de">
                            <tr>
                                <td>开户银行：</td>
                                <td><?php echo CHtml::encode($model->Agent_Bank->name); ?></td>
                            </tr>
                            <tr>
                                <td>开户支行：</td>
                                <td><?php echo CHtml::encode($model->bank_branch); ?></td>
                            </tr>
                            <tr>
                                <td>开户姓名：</td>
                                <td><?php echo CHtml::encode($model->bank_name); ?></td>
                            </tr>
                            <tr>
                                <td>开户银行账号：</td>
                                <td><?php echo CHtml::encode($model->bank_code); ?></td>
                            </tr>
                        </table>
                    </div> <!--  .box -->
                </div>   <!-- .box_div --><!--虚线框架-->
        <?php
            }else{
        ?>
    <div class="content_up">
        <font style="font-size: 13px;">您还未绑定银行账户，绑定后可结算收益。</font><br /><br />
        <div class="up"><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/bind_bank')?>">立即绑定</a></div>
    </div>
        <?php } ?>

</div>  <!--.content_box-->

