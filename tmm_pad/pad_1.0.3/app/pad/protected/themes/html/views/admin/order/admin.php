<?php
/* @var $this OrderController */
/* @var $model Order */

$this->breadcrumbs = array(
    '订单管理（全）页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#order-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理 订单（全）</h1>

<div>
    <span>
        <?php echo CHtml::link('高级搜索', '#', array('class'=>'search-button')); ?>    
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
    $.fn.yiiGridView.update('order-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('order-grid');  
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
    jQuery('#order-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#order-grid').yiiGridView('update');
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
    jQuery('#trade_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#up_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
    jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'order-grid',
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
            'value'=>function ($data, $row) {
                return $data::$_type[$data->type];
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('type') . '：' . $data::$_type[$data->type];
                },
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'order_no',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:130px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('order_no') . '：' . $data->order_no;
                }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'role_id',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('role_id') . '：' . $data->role_id;
                }
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
                    return $data->getAttributeLabel('money') . '：' . $data->viewMoney($data->money);
                }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'trade_money',
            'value' => function ($data, $row) {
                return $data->viewMoney($data->trade_money);
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('trade_money') . '：' . $data->viewMoney($data->trade_money);
                }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'trade_no',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('trade_no') . '：' . $data->trade_no;
                }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'trade_id',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('trade_id') . '：' . $data->trade_id;
                }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            //'filter'=>,
            'name'=>'trade_name',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('trade_name') . '：' . $data->trade_name;
                }
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>$model::$_trade_type,
            'name'=>'trade_type',
            'value'=>function ($data, $row) {
                return $data::$_trade_type[$data->trade_type];
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('trade_type') . '：' . $data::$_trade_type[$data->trade_type];
                },
            ),
        ),
        array(
            'class'=>'DataColumn',
            'evaluateHtmlOptions'=>true,
            'filter'=>$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'language'=>'zh-CN',
                'model'=>$model,
                'attribute'=>'trade_time',
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
                        'id' =>'trade_time_date',
                ),
            ),true),
            'name'=>'trade_time',
            'type'=>'datetime',
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('trade_time') . '：' . Yii::app()->format->FormatDate($data->trade_time);
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
            'filter'=>$model::$_pay_status,
            'name'=>'pay_status',
            'value'=>function ($data, $row) {
                return $data::$_pay_status[$data->pay_status];
            },
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
            'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('pay_status') . '：' . $data::$_pay_status[$data->pay_status];
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
            'template'=>'{view}',
            'buttons'=>array(
                'view'=>array(
                    'options'=>array('style'=>'padding:0 8px 0 0;'),
                ),
            ),
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
        ),
    ),
)); ?>
