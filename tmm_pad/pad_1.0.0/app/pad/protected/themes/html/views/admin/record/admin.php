<?php
/* @var $this RecordController */
/* @var $model Record */

$this->breadcrumbs = array(
    '抽奖记录管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#record-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理抽奖记录</h1>

<div>
    <span>
        <?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>    
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
    $.fn.yiiGridView.update('record-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('record-grid');  
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
    jQuery('#record-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#record-grid').yiiGridView('update');
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
    jQuery('#exchange_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'record-grid',
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
                        return $data->getAttributeLabel('id') . '：' . $data->id . "\n" . 
                             $data->getAttributeLabel('config_id') . '：' . $data->config_id . "\n" . 
                             $data->getAttributeLabel('chance_id') . '：' . $data->chance_id . "\n" . 
                             $data->getAttributeLabel('manager_id') . '：' . $data->manager_id;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'user_id',
                'value'=>function ($data, $row) {
                    return $data->Record_User->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('user_id') . '：' . $data->Record_User->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'prize_id',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('prize_id') . '：' . $data->prize_id;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'prize_name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('prize_name') . '：' . $data->prize_name . "\n" .
                            $data->getAttributeLabel('prize_info') . "：\n" . $data->prize_info . "\n" . 
                            $data->getAttributeLabel('url') . '：' . $data->url;
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
                //'filter'=>,
                'name'=>'pad_id',
                'value'=>function ($data, $row) {
                    return $data->Record_Pad->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Record_Pad.name') . '：' . $data->Record_Pad->name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Record_Pad, 'number', array('id'=>false)),
                'name'=>'Record_Pad.number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Record_Pad.number') . '：' . $data->Record_Pad->number . "\n" .
                            $data->getAttributeLabel('Record_Pad.mac') . '：' . $data->Record_Pad->mac;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'store_id',
                'value'=>function ($data, $row) {
                    return $data->Record_Store->store_name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Record_Store.store_name') . '：' . $data->Record_Store->store_name . "\n" .
                            $data->getAttributeLabel('Record_Store.name') . '：' . $data->Record_Store->name . "\n" .
                            $data->getAttributeLabel('Record_Store.telephone') . '：' . $data->Record_Store->store_name;
                    },
                ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>CHtml::activeTextField($model->Record_Store, 'phone', array('id'=>false)),
            'name'=>'Record_Store.phone',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('Record_Store.phone') . '：' . $data->Record_Store->phone;
                },
            ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Record_Store, 'province', array(''=>'') + Area::model()->getAreaArray(), array('id'=>false)),
                'name'=>'Record_Store.province',
                'value'=>function ($data, $row) {
                    return $data->Record_Store->Store_Area_province->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Record_Store.province') . '：' . $data->Record_Store->Store_Area_province->name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Record_Store, 'city', array(''=>'') + Area::model()->getAreaArray($model->Record_Store->province), array('id'=>false)),
                'name'=>'Record_Store.city',
                'value'=>function ($data, $row) {
                    return $data->Record_Store->Store_Area_city->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Record_Store.city') . '：' . $data->Record_Store->Store_Area_city->name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Record_Store, 'district', array(''=>'') + Area::model()->getAreaArray($model->Record_Store->city), array('id'=>false)),
                'name'=>'Record_Store.district',
                'value'=>function ($data, $row) {
                    return $data->Record_Store->Store_Area_district->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Record_Store.district') . '：' . $data->Record_Store->Store_Area_district->name .  "\n" .
                            $data->getAttributeLabel('Record_Store.address') . '：' . $data->Record_Store->address;
                    },
                ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>Prize::$_receive_type,
            'name'=>'receive_type',
            'value'=>function ($data, $row) {
                return Prize::$_receive_type[$data->receive_type];
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('receive_type') . '：' . Prize::$_receive_type[$data->receive_type];
                },
            ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'code',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('code') . '：' . $data->code;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>$model::$_exchange_status,
                'name'=>'exchange_status',
                'value'=>function ($data, $row) {
                    return $data::$_exchange_status[$data->exchange_status];
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('exchange_status') . '：' . $data::$_exchange_status[$data->exchange_status];
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'language'=>'zh-CN',
                        'model'=>$model,
                        'attribute'=>'exchange_time',
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
                                'id' =>'exchange_time_date',
                        ),
                ),true),
                'name'=>'exchange_time',
                'type'=>'datetime',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('exchange_time') . '：' . Yii::app()->format->FormatDate($data->exchange_time) . "\n" .
                        $data->getAttributeLabel('up_time') . '：' . Yii::app()->format->FormatDate($data->up_time);
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
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:55px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('status') . '：' . $data::$_status[$data->status];
                },
            ),
        ),
        array(
            'class'=>'CButtonColumn',
            'header'=>'操 作',
            'template'=>'{view}{exchange}{express_create}{express}',
            'buttons'=>array(
                    'view'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'exchange'=>array(
                        'label'=>'兑换',
                        'visible'=>function ($row, $data) {
                            return $data->status = $data::_STATUS_NORMAL && $data->exchange_status == $data::RECORD_EXCHANGE_STATUS_NO && $data->receive_type == Prize::PRIZE_RECEIVE_TYPE_EXCHANGE;
                        },
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('exchange', 'id'=>$data->id));
                        },
                        'click'=>$click,
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'express_create'=>array(
                        'label'=>'填写地址',
                        'visible'=>function ($row, $data) {
                            return $data->status = $data::_STATUS_NORMAL && $data->exchange_status == $data::RECORD_EXCHANGE_STATUS_NO && $data->receive_type == Prize::PRIZE_RECEIVE_TYPE_EXPRESS;
                        },
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('express/create', 'id' => $data->id));
                        },
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'express'=>array(
                            'label'=>'查看快递',
                            'visible'=>function ($row, $data) {
                                return $data->status = $data::_STATUS_NORMAL && $data->exchange_status == $data::RECORD_EXCHANGE_STATUS_ING && $data->receive_type == Prize::PRIZE_RECEIVE_TYPE_EXPRESS;
                            },
                            'url'=>function ($data, $row) {
                                return  CHtml::normalizeUrl(array('express/admin', 'Express[record_id]'=>'=' . $data->id));
                            },
                            'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
            ),
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:130px;'),
        ),
    ),
)); ?>
