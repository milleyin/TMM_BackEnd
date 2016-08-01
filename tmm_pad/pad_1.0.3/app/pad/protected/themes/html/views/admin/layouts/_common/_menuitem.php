<?php
    $menus['我的账号管理'] = array(
        array(
            'label'=>'我的信息',
            'url'=>array('/admin/index/index'),
            'linkOptions'=>array('title'=>'我的信息'),
        ),
        array(
            'label'=>'修改信息',
            'url'=>array('/admin/index/update'),
            'linkOptions'=>array('title'=>'修改我的信息'),
        ),
        array(
            'label'=>'修改密码',
            'url'=>array('/admin/index/pwd'),
            'linkOptions'=>array('title'=>'修改我的密码'),
        ),
    );
    $menus['体验店管理'] = array(
        array(
            'label'=>'账号管理',
            'url'=>array('/admin/store/admin'),
            'linkOptions'=>array('title'=>'账号管理'),
        ),
        array(
            'label'=>'创建账号',
            'url'=>array('/admin/store/create'),
            'linkOptions'=>array('title'=>'创建账号'),
        ),
    );
    $menus['展示屏管理'] = array(
        array(
            'label'=>'展示屏管理',
            'url'=>array('/admin/pad/admin'),
            'linkOptions'=>array('title'=>'展示屏管理'),
        ),
    );
    $menus['商品管理'] = array(
        array(
            'label'=>'商品管理',
            'url'=>array('/admin/shop/admin'),
            'linkOptions'=>array('title'=>'商品管理'),
        ),
    );
    $menus['广告管理'] = array(
        array(
            'label'=>'广告管理',
            'url'=>array('/admin/ad/admin'),
            'linkOptions'=>array('title'=>'广告管理'),
        ),
        array(
            'label'=>'创建广告',
            'url'=>array('/admin/ad/create'),
            'linkOptions'=>array('title'=>'创建广告'),
        ),
        array(
            'label'=>'选择关系',
            'url'=>array('/admin/select/admin'),
            'linkOptions'=>array('title'=>'选择关系'),
        ),
    );
    $menus['抽奖配置管理'] = array(
        array(
            'label'=>'抽奖配置管理',
            'url'=>array('/admin/config/admin'),
            'linkOptions'=>array('title'=>'抽奖配置管理'),
        ),
        array(
            'label'=>'抽奖机会管理',
            'url'=>array('/admin/chance/admin'),
            'linkOptions'=>array('title'=>'抽奖机会管理'),
        ),
    );
    $menus['奖品管理'] = array(
        array(
            'label'=>'奖品管理',
            'url'=>array('/admin/prize/admin'),
            'linkOptions'=>array('title'=>'奖品管理'),
        ),
    );
    $menus['抽奖记录管理'] = array(      
        array(
            'label'=>'抽奖记录管理',
            'url'=>array('/admin/record/admin'),
            'linkOptions'=>array('title'=>'抽奖记录管理'),
        ),
        array(
            'label'=>'中奖发货管理',
            'url'=>array('/admin/express/admin'),
            'linkOptions'=>array('title'=>'中奖发货管理'),
        ),
    );
    $menus['资源文件管理'] = array(
        array(
            'label'=>'资源文件管理',
            'url'=>array('/admin/upload/admin'),
            'linkOptions'=>array('title'=>'抽奖机会管理'),
        ),
    );
    $menus['订单管理'] = array(
        array(
            'label'=>'订单管理（全）',
            'url'=>array('/admin/order/admin'),
            'linkOptions'=>array('title'=>'订单管理（全）'),
        ),
        array(
            'label'=>'抢菜订单管理',
            'url'=>array('/admin/orderfood/admin'),
            'linkOptions'=>array('title'=>'抢菜订单管理'),
        ),
    );
    $array = array();
    $active = '';
    if (!empty($menus)) {
        $i = 0;
        foreach ($menus as $name=>$menu) {
            $array[$name] = $menu;
            if ($active == '') {
                foreach ($menu as $url) {
                    if ($active == '' && strcasecmp(trim($url['url'][0], '/'), $this->route) == 0) {
                        Yii::app()->user->setState('__menuitemRoute', $this->route);
                        $active = $i;
                    }
                }
            }
            $i++;
        }
        if ($active == '') {
            $i = 0;
            foreach ($menus as $name=>$menu) {
                foreach ($menu as $x => $url) {
                    if ($active == '' && strcasecmp(trim($url['url'][0], '/'), Yii::app()->user->getState('__menuitemRoute', $this->route)) == 0) {
                        $active = $i;
                        $array[$name][$x]['active'] = true;
                    }
                }
                $i++;
            }
        }
        foreach ($array as $name=>$menu) {
            $array[$name] = $this->renderPartial('/layouts/_common/__menuitem', array('menu'=>$menu), true);
        }
    }
$this->widget('zii.widgets.jui.CJuiAccordion', array(
    'panels'=>$array,
    'htmlOptions'=>array(
        'style'=>'width:189px;height:auto;'
    ),
    'options'=>array(
        'style'=>'height:auto;',
        'animated'=>'bounceslide',
        'collapsible'=>true,
        'active'=>$active == '' ? 0 : $active,
    ),
));