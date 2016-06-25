<div id="mainmenu">
    <?php
    if(isset($this->navbar))
        $this->widget('zii.widgets.CMenu',array(
            'items'=>$this->navbar, //导航栏
            //	'firstItemCssClass'=>'active',
            'activeCssClass'=>'active',
        ));
    ?>
    <div id="out">
        <span>欢迎,</span>
        <span><?php echo CHtml::link(Yii::app()->agent->phone,array('/agent/agent_admin/own'),array('target'=>'agent_right','title'=>'我的信息'))?></span>
        <span><?php echo CHtml::link('退出',array('/agent/agent_login/out'));?></span>
    </div>
</div>
