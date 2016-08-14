<?php
/* @var $this ConfigController */
/* @var $model Config */

$this->breadcrumbs = array(
    '抽奖配置管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#config-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理 抽奖配置</h1>

<div>
    <span>
        <?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>    
    </span>
    <span>
        <?php echo CHtml::link('设置抽奖配置', array('pad/admin')); ?>
    </span>
</div>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
    'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
    $Confirmation = "你确定执行此项操作？";
    if (Yii::app()->request->enableCsrfValidation)
    {
        $csrfTokenName = Yii::app()->request->csrfTokenName;
        $csrfToken = Yii::app()->request->csrfToken;
        $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken'},";
    }
    else
        $csrf = '';
        
$click_alert = <<<"EOD"
function() { 
    if ( !confirm("$Confirmation")) return false; 
    var th = this;  
    var afterDelete = function(link, success, data){ if (success) alert(data);};  
    $.fn.yiiGridView.update('config-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('config-grid');  
       afterDelete(th,true,data);  
    },
    error:function(XHR) {
       return afterDelete(th,false,XHR);
    }
  });
    return false;
}
EOD;

$click = <<<"EOD"
function() {  
    if ( !confirm("$Confirmation")) return false;
    var th = this,
    afterDelete = function () {};
    jQuery('#config-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#config-grid').yiiGridView('update');
            afterDelete(th, true, data);
        },
        error: function(XHR) {
            return afterDelete(th, false, XHR);
        }
});
    return false;
}
EOD;

Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'config-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'enableHistory'=>true,
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'columns'=>array(
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'id',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:30px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('id') . '：' . $data->id;
                    }
                ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>$model::$_type,
            'name'=>'type',
            'value' => function ($data, $row) {
                return $data::$_type[$data->type];
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('type') . '：'  . $data::$_type[$data->type];
                },
             ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'money',
            'value' => function ($data, $row) {
                return $data->viewMoney($data->money);
             },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('money') . '：'  . $data->viewMoney($data->money);
                },
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'chance_number',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;', 'title' => '每天获取抽奖机会的次数'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('chance_number') . '：'  . $data->chance_number;
                },
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'number',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;', 'title' => '每次抽奖机会获取的抽奖次数'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('number') . '：'  . $data->number . "\n" . 
                        //$data->getAttributeLabel('ad_url') . "：\n" . $data->ad_url . "\n" .
                        $data->getAttributeLabel('info') . "：\n" . $data->info;
                        
                }
            ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'pad_id',
                'value'=>function ($data, $row) {
                    return $data->Config_Pad->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Config_Pad.name') . '：' . $data->Config_Pad->name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Config_Pad, 'number', array('id'=>false)),
                'name'=>'Config_Pad.number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Config_Pad.mac') . '：' . $data->Config_Pad->mac;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'store_id',
                'value'=>function ($data, $row) {
                    return $data->Config_Store->store_name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('store_id') . '：' . $data->store_id . "\n" .
                        $data->getAttributeLabel('Config_Store.phone') . '：' . $data->Config_Store->phone . "\n" .
                        $data->getAttributeLabel('Config_Store.store_name') . '：' . $data->Config_Store->store_name . "\n" .
                        $data->getAttributeLabel('Config_Store.name') . '：' . $data->Config_Store->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Config_Store, 'phone', array('id'=>false)),
                'name'=>'Config_Store.phone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Config_Store.phone') . '：' . $data->Config_Store->phone;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'manager_id',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('manager_id') . '：' . $data->manager_id;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'language'=>'zh-CN',
                        'model'=>$model,
                        'attribute'=>'up_time',
                        'value'=>date('Y-m-d'),
                        'options'=>array(
                                'maxDate'=>'new date()',
                                'dateFormat'=>'yy-mm-dd',
                                'showOn' => 'focus',
                                'showOtherMonths' => true,
                                'selectOtherMonths' => true,
                                'changeMonth' => true,
                                'changeYear' => true,
                                'showButtonPanel' => true,
                        ),
                        'htmlOptions'=>array(
                                'id' =>'up_time_date',
                        ),
                    ),true),
                'name'=>'up_time',
                'type'=>'datetime',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('up_time') . '：' . Yii::app()->format->FormatDate($data->up_time);
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'language'=>'zh-CN',
                        'model'=>$model,
                        'attribute'=>'add_time',
                        'value'=>date('Y-m-d'),
                        'options'=>array(
                                'maxDate'=>'new date()',
                                'dateFormat'=>'yy-mm-dd',
                                'showOn' => 'focus',
                                'showOtherMonths' => true,
                                'selectOtherMonths' => true,
                                'changeMonth' => true,
                                'changeYear' => true,
                                'showButtonPanel' => true,
                        ),
                        'htmlOptions'=>array(
                                'id' =>'add_time_date',
                        ),
                    ),true),
                'name'=>'add_time',
                'type'=>'datetime',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('add_time') . '：' . Yii::app()->format->FormatDate($data->add_time);
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>$model::$_status,
                'name'=>'status',
                'value'=>function ($data, $row) {
                    return $data::$_status[$data->status];
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('status') . '：' . $data::$_status[$data->status];
                    },
                ),
        ),
        array(
            'class'=>'CButtonColumn',
            'header'=>'操 作',
            'template'=>'{view}{update}{update_prize}{disable}{start}',
            'buttons'=>array(
                    'view'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'update'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'update_prize'=>array(
                        'label'=>'配置奖品',
                        'visible'=>function ($row, $data) {
                            return $data->status == $data::_STATUS_NORMAL;
                        },
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                        'url'=>function($data, $row) {
                            return  CHtml::normalizeUrl(array('prize/admin', 'Prize[pad_id]'=>'='.$data->pad_id));
                        }
                    ),
                    'disable'=>array(
                        'label'=>'禁用',
                        'visible'=>function ($row, $data) {
                            return $data->status == $data::_STATUS_NORMAL;
                        },
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('disable', 'id'=>$data->id));
                        },
                        'click'=>$click,
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'start'=>array(
                        'label'=>'激活',
                        'visible'=>function ($row, $data) {
                            return $data->status == $data::_STATUS_DISABLE;
                        },
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('start', 'id'=>$data->id));
                        },
                        'click'=>$click,
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
            ),
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:200px;'),
        ),
    ),
)); ?>
