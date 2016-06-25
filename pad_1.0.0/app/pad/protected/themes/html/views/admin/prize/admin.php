<?php
/* @var $this PrizeController */
/* @var $model Prize */

$this->breadcrumbs = array(
    '奖品管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#prize-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理奖品</h1>

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
    $.fn.yiiGridView.update('prize-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('prize-grid');  
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
    jQuery('#prize-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#prize-grid').yiiGridView('update');
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
    'id'=>'prize-grid',
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
                'name'=>'position',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('position') . '：' . $data->position;
                }
                    ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('name') . '：' . $data->name . "\n" . 
                              $data->getAttributeLabel('info') . '：' . $data->info;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Prize_Upload, 'path', array('id'=>false)),
                'name'=>'Prize_Upload.path',
                'type'=>'raw',
                'value'=>function ($data, $row) {
                     if ( !$data->Prize_Upload)
                         return '';
                    return CHtml::image($data->Prize_Upload->getUrlPath(), CHtml::encode($data->Prize_Upload->info), array('title'=>$data->Prize_Upload->info, 'style'=>'width:80px;height:60px;'));
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        if ( !$data->Prize_Upload)
                            return '';
                        return $data->getAttributeLabel('Prize_Upload.info') . '：' . $data->Prize_Upload->info;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('count') . '：' . $data->count . "\n" .
                        $data->getAttributeLabel('number') . '：' . $data->number;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'odds',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('odds') . '：' . $data->odds;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'pad_id',
                'value'=>function ($data, $row) {
                      return $data->Prize_Pad->name;
                 },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Prize_Pad.name') . '：' . $data->Prize_Pad->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Prize_Pad, 'number', array('id'=>false)),
                'name'=>'Prize_Pad.number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Prize_Pad.number') . '：' . $data->Prize_Pad->number . "\n" .
                              $data->getAttributeLabel('Prize_Pad.mac') . '：' . $data->Prize_Pad->mac;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'store_id',
                'value'=>function ($data, $row) {
                    return $data->Prize_Store->store_name;
                 },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Prize_Store.store_name') . '：' . $data->Prize_Store->store_name . "\n" .
                           $data->getAttributeLabel('Prize_Store.name') . '：' . $data->Prize_Store->name . "\n" .
                        $data->getAttributeLabel('Prize_Store.telephone') . '：' . $data->Prize_Store->telephone;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Prize_Store, 'phone', array('id'=>false)),
                'name'=>'Prize_Store.phone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Prize_Store.phone') . '：' . $data->Prize_Store->phone;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'config_id',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('manager_id') . '：' . $data->config_id;
                    }
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
                'filter'=>$model::$_receive_type,
                'name'=>'receive_type',
                'value'=>function ($data, $row) {
                    return $data::$_receive_type[$data->receive_type];
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('receive_type') . '：' . $data::$_receive_type[$data->receive_type] . "\n" .
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
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('status') . '：' . $data::$_status[$data->status];
                    },
                ),
        ),
        array(
            'class'=>'CButtonColumn',
            'header'=>'操 作',
            'template'=>'{view}{update}{thanks}{delete}{disable}{start}',
            'buttons'=>array(
                    'view'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'update'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'thanks'=>array(
                        'label'=>'更新谢谢参与',
                        'visible'=>function ($row, $data) {
                            return $data->status == $data::_STATUS_DELETED;
                        },
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('thanks', 'id'=>$data->id));
                        },
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'delete'=>array(
                        'label'=>'重置谢谢参与',
                        'visible'=>function ($row, $data) {
                            return $data->status == $data::_STATUS_DISABLE;
                        },
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
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
                        'label'=>'启用',
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
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
        ),
    ),
)); ?>
