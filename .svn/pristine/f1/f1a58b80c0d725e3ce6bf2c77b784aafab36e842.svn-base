<?php
/* @var $this PadController */
/* @var $model Pad */

$this->breadcrumbs = array(
    '展示屏管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#pad-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理展示屏</h1>

<div>
    <span>
        <?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>    
    </span>
    <span>
        <?php echo CHtml::link('绑定展示屏', array('store/admin')); ?>    
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
    var afterDelete = function(link, success, data){ if (success && data) alert(data);};  
    $.fn.yiiGridView.update('pad-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('pad-grid');  
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
    jQuery('#pad-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#pad-grid').yiiGridView('update');
            afterDelete(th, true, data);
        },
        error: function(XHR) {
            return afterDelete(th, false, XHR);
        }
});
    return false;
}
EOD;

$state_click = <<<"EOD"
function() {  
    if ( !confirm("$Confirmation")) return false;
    var th = this,
    afterDelete = function () {};
    jQuery('#pad-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#pad-grid').yiiGridView('update');
            setTimeout("jQuery('#pad-grid').yiiGridView('update')", 5000);
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
    'id'=>'pad-grid',
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
                'name'=>'name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                    return $data->getAttributeLabel('name') . '：' . $data->name;
                }
                    ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('number') . '：' . $data->number . "\n" . 
                                      $data->getAttributeLabel('mac') . '：' . $data->mac;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'store_id',
                'value'=>function ($data, $row) {
                      return $data->Pad_Store->store_name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:180px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Pad_Store.phone') . '：' . $data->Pad_Store->phone . "\n" .
                                $data->getAttributeLabel('Pad_Store.store_name') . '：' . $data->Pad_Store->store_name . "\n" .
                                $data->getAttributeLabel('Pad_Store.name') . '：' . $data->Pad_Store->name . "\n" .
                                $data->getAttributeLabel('Pad_Store.telephone') . '：' . $data->Pad_Store->telephone . "\n" .
                                $data->getAttributeLabel('Pad_Store.pad_count') . '：' . $data->Pad_Store->pad_count;
                      }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Pad_Store, 'phone', array('id'=>false)),
                'name'=>'Pad_Store.phone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                      return  $data->getAttributeLabel('Pad_Store.phone') . '：' . $data->Pad_Store->phone;
                  }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Pad_Store, 'province', array(''=>'') + Area::model()->getAreaArray(), array('id'=>false)),
                'name'=>'Pad_Store.province',
                'value'=>function ($data, $row) {
                    return $data->Pad_Store->Store_Area_province->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                          return $data->getAttributeLabel('Pad_Store.province') . '：' . $data->Pad_Store->Store_Area_province->name;
                      }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Pad_Store, 'city', array(''=>'') + Area::model()->getAreaArray($model->Pad_Store->province), array('id'=>false)),
                'name'=>'Pad_Store.city',
                'value'=>function ($data, $row) {
                    return $data->Pad_Store->Store_Area_city->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Pad_Store.city') . '：' . $data->Pad_Store->Store_Area_city->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Pad_Store, 'district', array(''=>'') + Area::model()->getAreaArray($model->Pad_Store->city), array('id'=>false)),
                'name'=>'Pad_Store.district',
                'value'=>function ($data, $row) {
                    return $data->Pad_Store->Store_Area_district->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Pad_Store.district') . '：' . $data->Pad_Store->Store_Area_district->name .  "\n" . 
                        $data->getAttributeLabel('Pad_Store.address') . '：' . $data->Pad_Store->address;
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
                'filter'=>$model::$_state,
                'name'=>'state',
                'value'=>function ($data, $row) {
                    return $data::$_state[$data->state];
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('state') . '：' . $data::$_state[$data->state];
                    }
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
            'template'=>'{view}{update}{state}{shop}{view_shop}{config}{update_config}{view_config}{delete}{disable}{start}{restore}',
            'buttons'=>array(
                    'view'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'update'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'state'=>array(
                            'label'=>'实时检测',
                            'visible'=>function ($row, $data) {
                                return $data->status == $data::_STATUS_NORMAL && $data->state == $data::PAD_STATE_NORMAL;
                            },
                            'url'=>function ($data, $row) {
                                return  CHtml::normalizeUrl(array('state', 'id'=>$data->id));
                            },
                            'click'=>$state_click,
                            'options'=>array('style'=>'padding:0 10px 0 0;'),
                    ),
                    'shop'=>array(
                            'label'=>'添加商品',
                            'visible'=>function ($row, $data) {
                                return $data->status == $data::_STATUS_NORMAL;
                            },
                            'options'=>array('style'=>'padding:0 10px 0 0;'),
                            'url'=>function ($data, $row) {
                                return  CHtml::normalizeUrl(array('shop/create', 'id'=>$data->id));
                            },
                    ),
                    'view_shop'=>array(
                            'label'=>'查看商品',
                            'options'=>array('style'=>'padding:0 10px 0 0;'),
                            'url'=>function ($data, $row) {
                                return  CHtml::normalizeUrl(array('shop/admin', 'Shop[pad_id]'=>'=' . $data->id));
                            },
                    ),
                    'config'=>array(
                            'label'=>'设置抽奖配置',
                            'visible'=>function ($row, $data) {
                                return $data->status == $data::_STATUS_NORMAL && !$data->Pad_Config;
                            },
                            'options'=>array('style'=>'padding:0 10px 0 0;'),
                            'url'=>function ($data, $row) {
                                return  CHtml::normalizeUrl(array('config/create', 'id'=>$data->id));
                            },
                    ),
                    'update_config'=>array(
                            'label'=>'更新抽奖配置',
                            'visible'=>function ($row, $data) {
                                return $data->status == $data::_STATUS_NORMAL && $data->Pad_Config;
                            },
                            'options'=>array('style'=>'padding:0 10px 0 0;'),
                            'url'=>function ($data, $row) {
                                return  CHtml::normalizeUrl(array('config/update', 'id'=>$data->Pad_Config->id));
                            },
                    ),
                    'view_config'=>array(
                        'label'=>'查看抽奖配置',
                        'visible'=>function ($row, $data) {
                            return !!$data->Pad_Config;
                        },
                        'options'=>array('style'=>'padding:0 10px 0 0;'),
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('config/admin', 'Config[pad_id]'=>'=' . $data->id));
                        },
                    ),
                    'delete'=>array(
                        'label'=>'解绑',
                        'visible'=>function ($row, $data){
                            return $data->status == $data::_STATUS_DISABLE;
                        },
                        'options'=>array('style'=>'padding:0 10px 0 0;'),
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
                    'restore'=>array(
                        'label'=>'还原',
                        'visible'=>function ($row, $data) {
                            return $data->status == $data::_STATUS_DELETED;
                        },
                        'url'=>function ($data, $row) {
                            return  CHtml::normalizeUrl(array('restore', 'id'=>$data->id));
                        },
                        'click'=>$click_alert,
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
            ),
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:450px;'),
        ),
    ),
)); ?>
