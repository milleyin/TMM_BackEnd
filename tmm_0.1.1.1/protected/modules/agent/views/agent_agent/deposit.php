
<div class="content_box">
    <?php
    echo $this->breadcrumbs(array(
        '账号信息'=>array('/agent/agent_agent/account'),
        '保证金',
    ));
    ?>
    <div class="content_top">
        <ul>
            <li><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/account')?>">账号详情</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/bank')?>">财务信息</a></li>
            <li class="top3"><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/deposit')?>">保证金</a><span></span></li>
            <div class="clear"></div>
        </ul>
        <hr />
    </div>  <!-- .content_top -->

    <div class="money">
        剩余保证金:<span style="color: red;"><?php echo Chtml::encode($model->deposit);?></span>元
    </div>
    </br>
    <?php
    $summaryText=<<<"EOD"
<div class="summary">
	<div class="text">
	<span>当前显示 <sapn class="num_span" id="items_count">
		<script type="text/javascript">
			jQuery(function($) {
			jQuery("#items_count").html({end}-{start}+1);
			})
		</script>
	</sapn>条数据 ｜ 共
	<span class="num_span">{pages}</sapn> 页</span></div>
</div>
EOD;
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'store-deposit-grid',
        'htmlOptions'=>array('class'=>'grid-view  deposit_table'),
        'dataProvider'=>$dataProvider,
        'enableHistory'=>true,
        'summaryText'=>$summaryText,
        'template'=>"{items}\n{summary}\n{pager}",
        'pager'=>array(
            'class'=>'CLinkPager',
            'header'=>'',
            'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/pager.css',
        ),
        'cssFile'=>Yii::app()->request->baseUrl.'/css/agent/css/query.css',
        'columns'=>array(
            array(
                'name'=>'add_time',
                'header'=>'时间',
                'type'=>'datetime',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
            array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'name'=>'deposit',
                'header'=>'操作',
                'value'=>'($data->	deposit_status==DepositLog::type_add?"+":"-").$data->deposit',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
                'htmlOptions'=>array('style'=>'text-align:center;','class'=>'$data->deposit_status==DepositLog::type_add?"shared_revenue":"state"'),
            ),
            array(
                'header'=>'事由',
                'name'=>'reason',
                'headerHtmlOptions'=>array('style'=>'text-align:center;'),
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
        ),
    ));
    ?>



</div>  <!--.content_box-->
