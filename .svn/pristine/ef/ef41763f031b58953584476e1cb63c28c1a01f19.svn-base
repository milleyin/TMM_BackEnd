<?php
$this->breadcrumbs=array(
    '管理页',
);
?>
<h1 style="width: 800px">导航菜单分组管理</h1>
<div id="tree">
    <?php
    echo CHtml::beginForm(Yii::app()->createUrl('/agent/agent_adminlink/sort'),'post');
    ?>
    <ul class="tree">
        <?php
        $html='';
        foreach($model as $nav){
            $html .='<li><input type="checkbox"  value="'.$nav->id.'" /><label>'.$nav->name.'</label>
 		['.CHtml::link('查看导航',array("/agent/agent_adminlink/view","id"=>$nav->id)).']
		['.CHtml::link('修改导航',array("/agent/agent_adminlink/update","id"=>$nav->id)).']
		['.CHtml::link('添加分组',array("/agent/agent_adminlink/group","id"=>$nav->id)).']
		['.($nav->show==1 ? CHtml::link('隐藏导航',array("/agent/agent_adminlink/hide","id"=>$nav->id)) :
                    CHtml::link('显示导航',array("/agent/agent_adminlink/show","id"=>$nav->id))).']
		['.($nav->status==1 ? CHtml::link('禁用导航',array("/agent/agent_adminlink/disable","id"=>$nav->id)) :
                    CHtml::link('激活导航',array("/agent/agent_adminlink/start","id"=>$nav->id))).']
		['.CHtml::link('删除导航',array("/agent/agent_adminlink/delete","id"=>$nav->id)).']
		[排序]'.CHtml::textField('name['.$nav->id.']',$nav->sort,array('class'=>'name'));
            if(!empty($nav->Link_Link)){
                $html.='<ul>';
                foreach($nav->Link_Link as $group){
                    $html .= '<li><input type="checkbox" value="'.$group->id.'" /><label>'.$group->name.'</label>
					['.CHtml::link('查看分组',array("/agent/agent_adminlink/view","id"=>$group->id)).']
					['.CHtml::link('修改分组',array("/agent/agent_adminlink/upgroup","id"=>$group->id)).']
					['.CHtml::link('添加链接',array("/agent/agent_adminlink/menu","id"=>$group->id)).']
				 	['.CHtml::link('移动分组',array("/agent/agent_adminlink/movegroup","id"=>$group->id)).']
					['.($nav->status==1 ? CHtml::link('禁用本组',array("/agent/agent_adminlink/disable","id"=>$group->id)) :
                            CHtml::link('激活本组',array("/agent/agent_adminlink/start","id"=>$group->id))).']
					['.CHtml::link('删除本组',array("/agent/agent_adminlink/delete","id"=>$group->id)).']
					[排序]'.CHtml::textField('name['.$group->id.']',$group->sort,array('class'=>'name'));
                    if(!empty($group->Link_Link_Link)){
                        $html.='<ul>';
                        foreach ($group->Link_Link_Link as $menu){
                            $html .= '<li><input type="checkbox" value="'.$menu->id.'" /><label>'.$menu->name.'</label>
						['.CHtml::link('查看链接',array("/agent/agent_adminlink/view","id"=>$menu->id)).']
						['.CHtml::link('修改链接',array("/agent/agent_adminlink/upaction","id"=>$menu->id)).']
						['.CHtml::link('移动链接',array("/agent/agent_adminlink/moveaction","id"=>$menu->id)).']
						['.($nav->status==1 ? CHtml::link('禁用链接',array("/agent/agent_adminlink/disable","id"=>$menu->id)) :
                                    CHtml::link('激活链接',array("/agent/agent_adminlink/start","id"=>$menu->id))).']
						['.CHtml::link('删除链接',array("/agent/agent_adminlink/delete","id"=>$menu->id)).']
						[排序]'.CHtml::textField('name['.$menu->id.']',$menu->sort,array('class'=>'name')).'</li>';
                        }
                        $html .= '</ul></li>';
                    }else
                        $html .= '</li>';
                }
                $html .= '</ul></li>';
            }else
                $html .= '</li>';
        }
        echo $html;
        ?>
    </ul>
    <div class="row">
	<span class="buttons">
	<?php
    echo CHtml::submitButton('更新排序');
    ?>
	</span>
    </div>
    <?php
    echo CHtml::endForm();
    ?>
</div>
<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($){
        $('.tree').checkTree({});
        $('li .name').change(function(){
// 		alert($(this).val());
            var name=$(this).attr('name');
            if(!name.match(/^namename\[[\d]\]$/))
                $(this).attr('name','name'+$(this).attr('name'));
        });
    });
    /*]]>*/
</script>
