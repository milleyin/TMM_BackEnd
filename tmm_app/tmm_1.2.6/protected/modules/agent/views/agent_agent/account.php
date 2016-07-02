
<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '账号信息'=>array('account'),
        '账号详情'
    ));
    ?>
    <div class="content_top">
        <ul>
            <li class="top1"><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/account')?>">账号详情</a><span></span></li>
            <li><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/bank')?>">财务信息</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/deposit')?>">保证金</a></li>
            <div class="clear"></div>
        </ul>
        <hr />
    </div>  <!-- .content_top -->
    <div class="content_up">
        <table>
            <tr>
                <td>商家量:</td>
                <td><?php echo CHtml::encode($model->merchant_count); ?></td>
            </tr>
            <tr>
                <td>我的分成比例:</td>
                <td><?php echo CHtml::encode($model->push); ?>%</td>
            </tr>
            <tr>
                <td>区域权限:</td>
                <td>
                    <?php
                        $area_info = Area::all_area(array(),$model->id);
                        echo count($area_info).'个县';
                    ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/areainfo')?>">查看区域详情</a>
                </td>
            </tr>
            <tr>
                <td>注册时间:</td>
                <td><?php echo CHtml::encode( date('Y-m-d H:i:s',$model->add_time)); ?></td>
            </tr>
            <tr>
                <td>登录次数:</td>
                <td><?php echo CHtml::encode($model->count); ?></td>
            </tr>
            <tr>
                <td>最后一次登录:</td>
                <td><?php echo CHtml::encode( date('Y-m-d H:i:s',$model->up_time)); ?></td>
            </tr>
        </table>
    </div>
</div>  <!--.content_box-->
