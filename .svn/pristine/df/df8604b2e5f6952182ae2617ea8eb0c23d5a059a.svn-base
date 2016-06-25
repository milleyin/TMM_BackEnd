<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '安全信息'=>array('safe'),
        '安全手机号'
    ));
    ?>
    <div class="content_top">
        <ul>
            <li class="top1"><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/safe')?>">安全手机号</a><span></span></li>
            <li><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/pwd')?>">修改密码</a></li>
            <div class="clear"></div>
        </ul>
        <hr />
    </div>  <!-- .content_top -->
    <div class="content_up">
        <font style="font-size: 13px;">
            您已绑定的手机号:
            <?php
              echo substr(Yii::app()->agent->phone,0,3).'***'.substr(Yii::app()->agent->phone,7,4);
            ?></font><br />
        <div class="up"><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/safeInput')?>">更换手机号</a></div>
    </div>
</div>  <!--.content_box-->