<?php
/* @var $this SelectController */
/* @var $model Select */

$this->breadcrumbs = array(
	'选择关系管理页',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#select-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>管理选择关系</h1>

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
	$.fn.yiiGridView.update('select-grid', {  
	type:'POST',
	url:$(this).attr('href'),$csrf
	success:function(data) {
	$.fn.yiiGridView.update('select-grid');  
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
	jQuery('#select-grid').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#select-grid').yiiGridView('update');
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
	jQuery('#add_time_date').datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['zh-CN'],{'maxDate':'new date()','dateFormat':'yy-mm-dd','showOn':'focus','showOtherMonths':true,'selectOtherMonths':true,'changeMonth':true,'changeYear':true,'showButtonPanel':true}));
}
");

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'select-grid',
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
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:20px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('id') . '：' . $data->id;
					}
				),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'ad_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:50px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('ad_id') . '：' . $data->ad_id;
					}
				),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeTextField($model->Select_Ad, 'name', array('id'=>false)),
		        'name'=>'Select_Ad.name',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
		            return $data->getAttributeLabel('Select_Ad.name') . '：' . $data->Select_Ad->name;
		        }
		            ),
		),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				'filter'=>Ad::$_type,
				'name'=>'ad_type',
				'value'=>function ($data, $row) {
					return Ad::$_type[$data->ad_type];
				},
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('ad_type') . '：' . Ad::$_type[$data->ad_type];
					},
				),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		       // 'filter'=>CHtml::activeTextField($model->Select_Ad->Ad_Upload, 'path', array('id'=>false)),
		        'name'=>'Select_Ad.Ad_Upload.path',
		        'type'=>'raw',
		        'value'=>function ($data, $row) {
		            if ($data->Select_Ad->Ad_Upload->upload_type == Upload::UPLOAD_UPLOAD_TYPE_IMAGE)
		                return CHtml::image($data->Select_Ad->Ad_Upload->getUrlPath(), CHtml::encode($data->Select_Ad->Ad_Upload->info), array('title'=>$data->Select_Ad->Ad_Upload->info,'style'=>'width:80px;height:60px;'));
		            return CHtml::link(CHtml::encode($data->Select_Ad->name), $data->Select_Ad->Ad_Upload->getUrlPath(), array('target'=>'_blank'));
		        },
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:90px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Ad.Ad_Upload.info') . '：' . $data->Select_Ad->Ad_Upload->info;
    		        },
		        ),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        //'filter'=>,
		        'name'=>'pad_id',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('pad_id') . '：' . $data->pad_id;
    		        }
	            ),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeTextField($model->Select_Pad, 'name', array('id'=>false)),
		        'name'=>'Select_Pad.name',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Pad.name') . '：' . $data->Select_Pad->name;
    		        }
                ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeTextField($model->Select_Pad, 'number', array('id'=>false)),
                'name'=>'Select_Pad.number',
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:100px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Select_Pad.number') . '：' . $data->Select_Pad->number . "\n" .
                                    $data->getAttributeLabel('Select_Pad.mac') . '：' . $data->Select_Pad->mac;
                    }
                ),
        ),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'store_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
				'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
						return $data->getAttributeLabel('store_id') . '：' . $data->store_id;
					}
				),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeTextField($model->Select_Store, 'store_name', array('id'=>false)),
		        'name'=>'Select_Store.store_name',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:150px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Store.store_name') . '：' . $data->Select_Store->store_name;
    		        }
		        ),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeTextField($model->Select_Store, 'phone', array('id'=>false)),
		        'name'=>'Select_Store.phone',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Store.phone') . '：' . $data->Select_Store->phone;
    		        }
		        ),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeTextField($model->Select_Store, 'name', array('id'=>false)),
		        'name'=>'Select_Store.name',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Store.name') . '：' . $data->Select_Store->name;
    		        }
	            ),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeTextField($model->Select_Store, 'telephone', array('id'=>false)),
		        'name'=>'Select_Store.telephone',
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Store.telephone') . '：' . $data->Select_Store->telephone;
    		        }
	            ),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeDropDownList($model->Select_Store, 'province', array(''=>'') + Area::model()->getAreaArray(), array('id'=>false)),
		        'name'=>'Select_Store.province',
		        'value'=>function ($data, $row) {
		            return $data->Select_Store->Store_Area_province->name;
		        },
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Store.province') . '：' . $data->Select_Store->province;
    		        }
	            ),
		),
		array(
		        'class'=>'DataColumn',
		        'evaluateHtmlOptions'=>true,
		        'filter'=>CHtml::activeDropDownList($model->Select_Store, 'city', array(''=>'') + Area::model()->getAreaArray($model->Select_Store->province), array('id'=>false)),
		        'name'=>'Select_Store.city',
		        'value'=>function ($data, $row) {
		            return $data->Select_Store->Store_Area_city->name;
		        },
		        'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
		        'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
    		            return $data->getAttributeLabel('Select_Store.city') . '：' . $data->Select_Store->city;
    		        }
	            ),
        ),
        array(
                'class'=>'DataColumn',
                'evaluateHtmlOptions'=>true,
                'filter'=>CHtml::activeDropDownList($model->Select_Store, 'district', array(''=>'') + Area::model()->getAreaArray($model->Select_Store->city), array('id'=>false)),
                'name'=>'Select_Store.district',
                'value'=>function ($data, $row) {
                    return $data->Select_Store->Store_Area_district->name;
                },
                'headerHtmlOptions'=>array('style'=>'text-align:center;width:65px;'),
                'htmlOptions'=>array('style'=>'text-align:center;', 'title'=>function ($row, $data) {
                        return $data->getAttributeLabel('Select_Store.district') . '：' . $data->Select_Store->district . "\n" . 
                        $data->getAttributeLabel('Select_Store.address') . '：' . $data->Select_Store->address;
                    }
                ),
        ),
		array(
				'class'=>'DataColumn',
				'evaluateHtmlOptions'=>true,
				//'filter'=>,
				'name'=>'manager_id',
				'headerHtmlOptions'=>array('style'=>'text-align:center;width:70px;'),
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
			'class'=>'CButtonColumn',
			'header'=>'操 作',
			'template'=>'{view}{delete}',
			'buttons'=>array(
					'view'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
					'delete'=>array(
						'options'=>array('style'=>'padding:0 8px 0 0;'),
					),
			),
			'headerHtmlOptions'=>array('style'=>'text-align:center;width:40px;'),
		),
	),
)); ?>
