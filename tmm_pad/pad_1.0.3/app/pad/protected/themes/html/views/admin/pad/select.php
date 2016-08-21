<?php
/* @var $this PadController */
/* @var $model Pad */

$this->breadcrumbs = array(
    '广告管理页'=>array('ad/admin'),
    $select->name=>array('ad/view', 'id'=>$select->id),
    '选择展示屏',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function() {
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function() {
    $('#pad-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>
<h1>选择展示屏 <font color='#eb6100'><?php echo CHtml::encode($select->name); ?></font></h1>

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
                'selectableRows' => 2,
                'class' => 'CCheckBoxColumn',
                'name'=>'id',
                'id'=>'select',
                'disabled'=>function ($data, $row) {
                     if ($this->_model->type == Ad::AD_TYPE_VIDEO)
                         return Select::model()->checkedPadVideo($data->id, $this->_model->id); 
                     return false;
                },
                'checked'=>function ($data, $row) {
                      return Select::model()->checkedPad($data->id, $this->_model->id);
                },
                'htmlOptions'=>array('style'=>'text-align:center;width:50px;', 'title'=>'点击选中添加'),
                'headerHtmlOptions' => array('style'=>'text-align:center;width:50px;', 'class'=>'select', 'title'=>'点击全选'),
                'headerTemplate'=>'{item}',
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
                        return $data->getAttributeLabel('number') . '：' . $data->number;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'mac',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('mac') . '：' . $data->mac;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                //'filter'=>,
                'name'=>'store_id',
                'value'=>'$data->Pad_Store->store_name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
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
                'filter'=>CHtml::activeTextField($model->Pad_Store, 'name', array('id'=>false)),
                'name'=>'Pad_Store.name',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return  $data->getAttributeLabel('Pad_Store.name') . '：' . $data->Pad_Store->name;
                    }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Pad_Store, 'telephone', array('id'=>false)),
                'name'=>'Pad_Store.telephone',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return  $data->getAttributeLabel('Pad_Store.telephone') . '：' . $data->Pad_Store->telephone;
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
                        return $data->getAttributeLabel('Pad_Store.district') . '：' . $data->Pad_Store->Store_Area_district->name . "\n" . $data->getAttributeLabel('Pad_Store.address') . '：' . $data->Pad_Store->address;
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
        )
    ),
));

if (Yii::app()->request->enableCsrfValidation)
{
    $csrfTokenName = Yii::app()->request->csrfTokenName;
    $csrfToken = Yii::app()->request->csrfToken;
    $csrf = "\n\t\tdata:{'$csrfTokenName':'$csrfToken', 'Select': {'pad_id':ids}},\n";
}
else
    $csrf = "\n\t\tdata:{'Select': {'pad_id':ids}},\n";
$createUrl = CHtml::normalizeUrl(array('select/create', 'id'=>$select->id));
$updateUrl = CHtml::normalizeUrl(array('select/update', 'id'=>$select->id));

$ajax=<<<EOD
jQuery(function($) {
    var createUrl = '$createUrl';
    var updateUrl = '$updateUrl';
    //获取当前页的所有的
    function getAll() {
        return jQuery('#pad-grid').yiiGridView('getChecked', 'select');
    }
    function ajaxCheckbox(ids, url) {
        //console.log('ids', ids);
        $.ajax({
            type:"POST",$csrf         url:url,
            success: function(data) {
                 jQuery('#pad-grid').yiiGridView('update');
            }
        });
        return false;
    }
    jQuery(document).on('change', '#select_all', function() {
        if ($(this).is(':checked')) {
            ajaxCheckbox(getAll(), createUrl);
        } else {
            ajaxCheckbox(getAll(), updateUrl);
        }
    });
    jQuery(document).on('change', "input:checkbox[name='select[]']", function() {
        if ($(this).is(':checked')) {
            ajaxCheckbox($(this).val(), createUrl);
        } else {
            ajaxCheckbox($(this).val(), updateUrl);
        }
     });
});
EOD;
Yii::app()->clientScript->registerScript('ad_select_pad',
$ajax
);
?>
