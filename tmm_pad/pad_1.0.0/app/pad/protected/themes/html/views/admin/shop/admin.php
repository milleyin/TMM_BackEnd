<?php
/* @var $this ShopController */
/* @var $model Shop */

$this->breadcrumbs = array(
    '商品管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#shop-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>管理商品</h1>

<div>
    <span>
        <?php echo CHtml::link('高级搜索','#',array('class'=>'search-button')); ?>    
    </span>
    <span>
        <?php echo CHtml::link('创建商品', array('pad/admin')); ?>    
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
    $.fn.yiiGridView.update('shop-grid', {  
    type:'POST',
    url:$(this).attr('href'),$csrf
    success:function(data) {
    $.fn.yiiGridView.update('shop-grid');  
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
    jQuery('#shop-grid').yiiGridView('update', {
        type: 'POST',
        url: jQuery(this).attr('href'),$csrf
        success: function(data) {
            jQuery('#shop-grid').yiiGridView('update');
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
    'id'=>'shop-grid',
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
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:250px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('name') . '：' . $data->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Shop_Upload, 'path', array('id'=>false)),
                'name'=>'Shop_Upload.path',
                'type'=>'raw',
                'value'=>function ($data, $row) {
                        return CHtml::image($data->Shop_Upload->getUrlPath(), CHtml::encode($data->Shop_Upload->info), array('title'=>$data->Shop_Upload->info,'style'=>'width:80px;height:60px;'));
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Upload.info') . '：' . $data->Shop_Upload->info;
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Shop_Upload, 'size', array('id'=>false)),
                'name'=>'Shop_Upload.size',
                'value'=>function ($data, $row) {
                    return $data->Shop_Upload->getSize();
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Upload.size') . '：' . $data->Shop_Upload->getSize();
                    },
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'store_id',
                'value'=>function ($data, $row) {
                    return $data->Shop_Store->store_name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Store.store_name') . '：' . $data->Shop_Store->store_name . "\n" .
                        $data->getAttributeLabel('Shop_Store.phone') . '：' . $data->Shop_Store->phone . "\n" .
                        $data->getAttributeLabel('Shop_Store.name') . '：' . $data->Shop_Store->name . "\n" .
                        $data->getAttributeLabel('Shop_Store.telephone') . '：' . $data->Shop_Store->telephone;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Shop_Store, 'phone', array('id'=>false)),
                'name'=>'Shop_Store.phone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Store.phone') . '：' . $data->Shop_Store->phone;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Shop_Store, 'province', array(''=>'') + Area::model()->getAreaArray(), array('id'=>false)),
                'name'=>'Shop_Store.province',
                'value'=>function ($data, $row) {
                    return $data->Shop_Store->Store_Area_province->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Store.province') . '：' . $data->Shop_Store->Store_Area_province->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Shop_Store, 'city', array(''=>'') + Area::model()->getAreaArray($model->Shop_Store->province), array('id'=>false)),
                'name'=>'Shop_Store.city',
                'value'=>function ($data, $row) {
                    return $data->Shop_Store->Store_Area_city->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Store.city') . '：' . $data->Shop_Store->Store_Area_city->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Shop_Store, 'district', array(''=>'') + Area::model()->getAreaArray($model->Shop_Store->city), array('id'=>false)),
                'name'=>'Shop_Store.district',
                'value'=>function ($data, $row) {
                    return $data->Shop_Store->Store_Area_district->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Store.district') . '：' . $data->Shop_Store->Store_Area_district->name .  "\n" .
                                $data->getAttributeLabel('Shop_Store.address') . '：' . $data->Shop_Store->address;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'pad_id',
                'value'=>function ($data, $row) {
                      return $data->Shop_Pad->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Pad.name') . '：' . $data->Shop_Pad->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Shop_Pad, 'number', array('id'=>false)),
                'name'=>'Shop_Pad.number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Shop_Pad.number') . '：' . $data->Shop_Pad->number . "\n" .
                              $data->getAttributeLabel('Shop_Pad.mac') . '：' . $data->Shop_Pad->mac;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'manager_id',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:60px;'),
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
            'template'=>'{view}{update}{delete}{disable}{start}{restore}',
            'buttons'=>array(
                    'view'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'update'=>array(
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
                    'delete'=>array(
                        'visible'=>function ($row, $data){
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
                        'click'=>$click,
                        'options'=>array('style'=>'padding:0 8px 0 0;'),
                    ),
            ),
            'headerHtmlOptions'=>array('style'=>'text-align:center;width:120px;'),
        ),
    ),
)); ?>
