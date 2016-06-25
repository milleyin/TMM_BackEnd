<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '我的收益'
    ));
    ?>
    <div class="cantent_zhu">
        <div class="cantent_top1">
            <div class="box_div">
                <div style="visibility:hidden"><span>收益总额</span></div>
                <div class="title2"><span>收益总额</span></div>
                <div class="box1 box_one">
                    <p align="center"><?php echo $model->total; ?><span>元</span></p>
                    &nbsp;
                </div> <!--  .box -->
            </div>   <!-- .box_div -->
        </div><!--cantent_top1-->
        <div class="cantent_top2">
            <div class="box_div">
                <div style="visibility:hidden"><span>已提现收益</span></div>
                <div class="title2"><span>已提现收益</span></div>
                <div class="box1 box_one">
                    <p align="center"><?php echo $model->cash_count; ?><span>元</span></p>
                    <div class="a">

                    </div>
                </div> <!--  .box -->
            </div>   <!-- .box_div -->
        </div><!--cantent_top2-->
        <div class="clear"></div>
        <div class="cantent_top3">
            <div class="box_div">
                <div style="visibility:hidden"><span>可提现收益</span></div>
                <div class="title2"><span>可提现收益</span></div>
                <div class="box2 box_one">
                    <div class="ke">
                        <span><?php echo $model->money; ?>元</span>
                        <span></span>
                    </div>
                </div> <!--  .box -->
            </div>   <!-- .box_div -->
        </div><!--cantent_top3-->
        <div>
         <?php
            if ($model->money >= Yii::app()->params['order_deposit_price_agent']) {
        ?>
                <input class="cantent_zhu_call" type="submit" value="立即结算"
                       onclick="javascrtpt:window.location.href='<?php echo Yii::app()->createUrl('/agent/agent_cash/create') ?>';return false;"/>
        <?php
            }else {
        ?>
                <input class="cantent_zhu_call" type="submit" value="立即结算"
                       onclick="javascrtpt:;"/>
                <span style="color:red;">
                    可提现金额不足 <?php echo Yii::app()->params['order_deposit_price_agent']; ?>
                </span>
        <?php
            }
        ?>
            <a href="<?php echo Yii::app()->createUrl('/agent/agent_cash/admin')?>">查看结算历史信息</a>
        </div>

    </div><!--.cantent_zhu -->
</div>