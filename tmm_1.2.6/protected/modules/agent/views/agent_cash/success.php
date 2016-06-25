<div class="content_box">
    <?php
    echo $this->breadcrumbs(
        array(
            '我的收益'=>array('/agent/agent_agent/income'),
            '结算',
        )
    );
    ?>
    <div class="cantent_ment">
        <div class="ment_left">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/success.png" />
        </div>
        <div class="ment_right">
            收益结算申请成功，<a href="<?php echo Yii::app()->createUrl('/agent/agent_cash/admin');?>">查看结算信息</a>。<br />
        </div>
        <div class="clear"></div>
    </div><!--.cantent_ment -->
</div>
