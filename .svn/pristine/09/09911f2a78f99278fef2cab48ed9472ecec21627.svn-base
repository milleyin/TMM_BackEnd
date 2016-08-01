<?php
/* @var $this ChanceController */
/* @var $model Chance */

$this->breadcrumbs = array(
    '抽奖机会管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#chance-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理抽奖机会</h1>

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
    $.fn.yiiGridView.update('chance-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('chance-grid');  
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
    jQuery('#chance-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#chance-grid').yiiGridView('update');
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
    jQuery('#date_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'chance-grid',
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
                //'filter'=>,
                'name'=>'user_id',
                'value'=>function ($data, $row) {
                      return $data->Chance_User->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_User.name') . '：' . $data->Chance_User->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'number',
                'value' => function ($data, $row) {
                    return $data->number . ' / ' . $data->count;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('number') . '：' . $data->number . "\n" .
                        $data->getAttributeLabel('count') . '：' . $data->count;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'pad_id',
                'value'=>function ($data, $row) {
                    return $data->Chance_Pad->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_Pad.name') . '：' . $data->Chance_Pad->name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Chance_Pad, 'number', array('id'=>false)),
                'name'=>'Chance_Pad.number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_Pad.number') . '：' . $data->Chance_Pad->number . "\n" .
                        $data->getAttributeLabel('Chance_Pad.mac') . '：' . $data->Chance_Pad->mac;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'store_id',
                'value'=>function ($data, $row) {
                    return $data->Chance_Store->store_name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_Store.store_name') . '：' . $data->Chance_Store->store_name . "\n" .
                        $data->getAttributeLabel('Chance_Store.name') . '：' . $data->Chance_Store->name . "\n" .
                        $data->getAttributeLabel('Chance_Store.telephone') . '：' . $data->Chance_Store->store_name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Chance_Store, 'phone', array('id'=>false)),
                'name'=>'Chance_Store.phone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_Store.phone') . '：' . $data->Chance_Store->phone;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Chance_Store, 'province', array(''=>'') + Area::model()->getAreaArray(), array('id'=>false)),
                'name'=>'Chance_Store.province',
                'value'=>function ($data, $row) {
                    return $data->Chance_Store->Store_Area_province->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_Store.province') . '：' . $data->Chance_Store->Store_Area_province->name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Chance_Store, 'city', array(''=>'') + Area::model()->getAreaArray($model->Chance_Store->province), array('id'=>false)),
                'name'=>'Chance_Store.city',
                'value'=>function ($data, $row) {
                    return $data->Chance_Store->Store_Area_city->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_Store.city') . '：' . $data->Chance_Store->Store_Area_city->name;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Chance_Store, 'district', array(''=>'') + Area::model()->getAreaArray($model->Chance_Store->city), array('id'=>false)),
                'name'=>'Chance_Store.district',
                'value'=>function ($data, $row) {
                    return $data->Chance_Store->Store_Area_district->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Chance_Store.district') . '：' . $data->Chance_Store->Store_Area_district->name .  "\n" .
                            $data->getAttributeLabel('Chance_Store.address') . '：' . $data->Chance_Store->address;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'config_id',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('config_id') . '：' . $data->config_id;
                    }
                ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>Config::$_type,
            'name'=>'type',
            'value' => function($data, $row) {
                return Config::$_type[$data->type];
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('type') . '：' . Config::$_type[$data->type];
                }
            ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'language'=>'zh-CN',
                        'model'=>$model,
                        'attribute'=>'date_time',
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
                                'id' =>'date_time_date',
                        ),
                    ),true),
                'name'=>'date_time',
                'type'=>'date',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('date_time') . '：' . Yii::app()->format->FormatDate($data->date_time);
                    },
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
            'template'=>'{view}',
            'buttons'=>array(
                    'view'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
            ),
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
        ),
    ),
)); ?>
