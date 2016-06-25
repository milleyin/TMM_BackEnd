<div class="content_box">
    <?php
    echo $this->breadcrumbs(
        array(
            '我的收益'=>array('/agent/agent_agent/income'),
            '结算',
        )
    );
    ?>
    <?php
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'agent-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array('enctype'=>'multipart/form-data','class'=>'modify_project_info_form'),
    ));
    ?>
    <div class="cantent">
        <p>提现金额:<?php echo $agent_account->money; ?>元</>
        <div class="box_div">
            <div style="visibility:hidden"><span>提现账户</span></div>
            <div class="title2"><span>提现账户</span></div>
            <div class="box box_one">
                <?php
                    if ($model->bank_id && $model->bank_name && $model->bank_branch && $model->bank_code)
                    {

                ?>
                    <table border="0" class="de">
                        <tr>
                            <td>开户银行:</td>
                            <td>
                                <?php echo CHtml::encode($model->Agent_Bank->name); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>开户支行:</td>
                            <td>
                                <?php echo CHtml::encode($model->bank_branch); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>开户姓名:</td>
                            <td>
                                <?php echo CHtml::encode($model->bank_name); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>银行账户:</td>
                            <td>
                                <?php echo CHtml::encode($model->bank_code); ?>
                            </td>
                        </tr>
                    </table>
                        <?php echo $form->hiddenField($cash_model,'bank_code'); ?>
                        <?php echo $form->error($cash_model,'bank_code'); ?>
<!--                        <input type="hidden" value="--><?php //echo CHtml::encode($model->bank_id); ?><!--" name="bank_id" />-->

                <?php
                    } else {
                        $no_bank = true;
                ?>
                        <p>未绑定银行账户，<span><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/bank');?>">立即绑定</a>。</span></p>
                <?php
                    }
                ?>
            </div> <!--  .box -->
        </div>   <!-- .box_div -->
        <input class="call" type="button" value="继续累积" onclick="javascrtpt:window.location.href='<?php echo Yii::app()->createUrl('/agent/agent_agent/income');?>'" />
        <?php
            if (! isset($no_bank)) {
        ?>
        <input class="call" type="submit" value="提交申请"/>
        <?php
            }
        ?>
    </div><!--.cantent -->
    <?php $this->endWidget(); ?>
</div>
<style>
    .errorMessage {
        color: #FF0000;
    }
</style>