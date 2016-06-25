<?php
/* @var $this ExpressController */
/* @var $model Express */

$this->breadcrumbs = array(
    '中奖发货管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#express-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理中奖发货</h1>

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
    $.fn.yiiGridView.update('express-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('express-grid');  
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
    jQuery('#express-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#express-grid').yiiGridView('update');
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
    jQuery('#express_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'express-grid',
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
                'name'=>'record_id',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('record_id') . '：' . $data->record_id;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'user_id',
                'value'=>function ($data, $row) {
                    return $data->Express_User->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Express_User.name') . '：' . $data->Express_User->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Express_Record, 'prize_name', array('id'=>false)),
                'name'=>'Express_Record.prize_name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Express_Record.prize_name') . '：' . $data->Express_Record->prize_name . "\n" .
                        $data->getAttributeLabel('Express_Record.prize_info') . '：' . $data->Express_Record->prize_info;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:80px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('name') . '：' . $data->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'phone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('phone') . '：' . $data->phone;
                    }
                ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'address',
            'value'=>function ($data, $row) {
                 return $data->Express_Area_province->name . $data->Express_Area_city->name . $data->Express_Area_district->name.  $data->address;
             },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:300px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('address') . '：' . $data->Express_Area_province->name . $data->Express_Area_city->name . $data->Express_Area_district->name.  $data->address;        
                }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>$model::$_express_status,
            'name'=>'express_status',
            'value'=>function ($data, $row) {
                return $data::$_express_status[$data->express_status];
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                return $data->getAttributeLabel('express_status') . '：' . $data::$_express_status[$data->express_status];
            },
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'express_name',
            'value'=>function ($data, $row) {
                return empty($data->express_name) ? '--' : $data->express_name;
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                return $data->getAttributeLabel('express_name') . '：' . $data->express_name;
            }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'express_code',
            'value'=>function ($data, $row) {
                return empty($data->express_code) ? '--' : $data->express_code;
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                return $data->getAttributeLabel('express_code') . '：' . $data->express_code;
            }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'language'=>'zh-CN',
                'model'=>$model,
                'attribute'=>'express_time',
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
                    'id' =>'express_time_date',
                ),
            ),true),
            'name'=>'express_time',
            'type'=>'datetime',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                return $data->getAttributeLabel('express_time') . '：' . Yii::app()->format->FormatDate($data->express_time);
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
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('status') . '：' . $data::$_status[$data->status];
                    },
                ),
        ),
        array(
            'class'=>'CButtonColumn',
            'header'=>'操 作',
            'template'=>'{view}{update}',
            'buttons'=>array(
                    'view'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'update'=>array(
                        'label'=>'发货',
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('update', 'id'=>$data->id));
                        },
                        'visible'=>function ($row, $data){
                            return $data->express_status == $data::EXPRESS_STATUS_NO;
                        },
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
            ),
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
        ),
    ),
)); ?>
