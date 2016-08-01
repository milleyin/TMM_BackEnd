<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-06-01 15:27:03 */
class RecordController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Record';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Record('search');
        $model->Record_User = new \User('search');
        $model->Record_Pad = new \Pad('search');
        $model->Record_Store = new \Store('search');    
        //清除默认值
        $model->unsetAttributes();
        $model->Record_User->unsetAttributes();
        $model->Record_Pad->unsetAttributes();
        $model->Record_Store->unsetAttributes();
        if (isset($_GET['Record']))
            $model->attributes = $_GET['Record'];
        if (isset($_GET['User']))
            $model->Record_User->attributes = $_GET['User'];
        if (isset($_GET['Pad']))
            $model->Record_Pad->attributes = $_GET['Pad'];
        if (isset($_GET['Store']))
            $model->Record_Store->attributes = $_GET['Store'];

        $this->render('admin', array(
            'model'=>$model,
        ));
    }
    
    /**
     * 下载
     */
    public function actionDownload()
    {
        $model = new \Record('search');
        $model->Record_User = new \User('search');
        $model->Record_Pad = new \Pad('search');
        $model->Record_Store = new \Store('search');
        //清除默认值
        $model->unsetAttributes();
        $model->Record_User->unsetAttributes();
        $model->Record_Pad->unsetAttributes();
        $model->Record_Store->unsetAttributes();
        if (isset($_GET['Record']))
            $model->attributes = $_GET['Record'];
        if (isset($_GET['User']))
            $model->Record_User->attributes = $_GET['User'];
        if (isset($_GET['Pad']))
            $model->Record_Pad->attributes = $_GET['Pad'];
        if (isset($_GET['Store']))
            $model->Record_Store->attributes = $_GET['Store'];
        //data
        $dataProvider = $model->search();
        //加载数据 统计页数
        $dataProvider->getData();
        $pageVar = $dataProvider->getPagination()->pageVar;
        $pageSize = $dataProvider->getPagination()->getPageSize();
        $pageCount = $dataProvider->getPagination()->getPageCount();
        $count = $dataProvider->getTotalItemCount();
        if ($count <= 0) {
            $this->returnMessage('没有找到数据');
        }
        //设置 不懒加载
        \Yii::$enableIncludePath = false;
        \Yii::import('ext.PHPExcel.PHPExcel', true);
        
        //创建一个实例
        $objPHPExcel = new \PHPExcel();
        //创建人
        $objPHPExcel->getProperties()->setCreator("ChangHai Zhan");
        //最后修改人
        $objPHPExcel->getProperties()->setLastModifiedBy("ChangHai Zhan");
        //标题
        $objPHPExcel->getProperties()->setTitle("展示屏抽奖记录");
        //题目
        $objPHPExcel->getProperties()->setSubject("展示屏抽奖记录");
        //描述
        $objPHPExcel->getProperties()->setDescription("展示屏抽奖记录");
        //关键字
        $objPHPExcel->getProperties()->setKeywords("展示屏抽奖记录");
        //种类
        $objPHPExcel->getProperties()->setCategory("展示屏抽奖记录");
        
        //设置当前的sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //设置sheet的标题
        $objPHPExcel->getActiveSheet()->setTitle('展示屏抽奖记录');
        // columns
        $columns = array(
            array(
                'name'=>'id',
            ),
            array(
                'name'=>'user_id',
                'value'=>function ($data) {
                    return $data->Record_User->name;
                },
            ),
            array(
                'name'=>'prize_id',
            ),
            array(
                'name'=>'prize_name',
            ),
            array(
                'name'=>'add_time',
                'value' => function ($data) {
                    return \Yii::app()->format->formatDatetime($data->add_time);
                },
            ),
            array(
                'name'=>'pad_id',
                'value'=>function ($data) {
                    return $data->Record_Pad->name;
                },
            ),
            array(
                'name'=>'Record_Pad.number',
            ),
            array(
                'name'=>'store_id',
                'value'=>function ($data) {
                    return $data->Record_Store->store_name;
                },
            ),
            array(
                'name'=>'Record_Store.phone',
            ),
            array(
                'name'=>'Record_Store.province',
                'value'=>function ($data) {
                    return $data->Record_Store->Store_Area_province->name;
                },
            ),
            array(
                'name'=>'Record_Store.city',
                'value'=>function ($data) {
                    return $data->Record_Store->Store_Area_city->name;
                },
            ),
            array(
                'name'=>'Record_Store.district',
                'value'=>function ($data) {
                    return $data->Record_Store->Store_Area_district->name;
                },
            ),
            array(
                'label' => '详细地址',
                'value' => function ($data) {
                    return $data->Record_Store->Store_Area_province->name . $data->Record_Store->Store_Area_city->name .$data->Record_Store->Store_Area_district->name . $data->Record_Store->address;
                },
            ),
            array(
                'name'=>'receive_type',
                'value'=>function ($data) {
                    return \Prize::$_receive_type[$data->receive_type];
                },
            ),
            array(
                'name'=>'code',
            ),
            array(
                'name'=>'print_status',
                'value'=>function ($data, $row) {
                    return $data::$_print_status[$data->print_status];
                },
            ),
            array(
                'name'=>'exchange_status',
                'value'=>function ($data) {
                    return $data::$_exchange_status[$data->exchange_status];
                },
            ),
            array(
                'name'=>'exchange_time',
                'value' => function ($data) {
                    return \Yii::app()->format->formatDatetime($data->exchange_time);
                },
            ),
            array(
                'name'=>'status',
                'value'=>function ($data) {
                    return $data::$_status[$data->status];
                },
            ),
        );
        // 写入数据
        for($i=0; $i < $pageCount; $i++) {
            $_GET[$pageVar] = $i+1;
            foreach ($model->search()->getData() as $y =>$data) {
                $y = $y + $i * $pageSize + 2;
                foreach ($columns as $x => $column) {
                    if ($y == 2) {
                        if (isset($column['name'])) {
                            $title = $model->getAttributeLabel($column['name']);
                        } elseif (isset($column['label'])) {
                            $title = $column['label'];
                        } else {
                            $this->returnMessage('程序异常');
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue($this->getExcelCeilIndex($x, 1), $title);
                        $objPHPExcel->getActiveSheet()->getColumnDimension($this->getExcelCeilIndex($x, false))->setAutoSize(true);
                    }
                    if (isset($column['value'])) {
                        $value = call_user_func_array($column['value'], array($data));
                    } elseif (isset($column['name'])) {
                        $value = \CHtml::value($data, $column['name']);
                    } else {
                        $this->returnMessage('程序异常');
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue($this->getExcelCeilIndex($x, $y), $value);
                }
            }
        }
        // excel头参数
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="展示屏抽奖记录' . date('Y-m-d') .'-' . $count . '.xls"');
        header("Content-Transfer-Encoding:binary");
        //excel5为xls格式,excel2007为xlsx格式
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    /**
     * 
     * @param unknown $row
     * @param unknown $col
     * @return string|boolean
     */
    public function getExcelCeilIndex($x, $y = 1)
    {
        $return = array();
        if ($x < 26) {
            $return[$x] = chr(65 + $x);
        } elseif ($x < 702) {
            $return[$x] = chr(64 + ($x / 26)) .
            chr(65 + $x % 26);
        } else {
            $return[$x] = chr(64 + (($x - 26) / 676)) .
            chr(65 + ((($x - 26) % 676) / 26)) .
            chr(65 + $x % 26);
        }
        if ($y === false)
            return $return[$x];
        return $return[$x] . $y;
    }
    
    /**
     * 查看
     * @param integer $id
     */
    public function actionView($id)
    {
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'Record_User',
            'Record_Store'=>array(
                'with'=>array(
                    'Store_Area_province',
                    'Store_Area_city',
                    'Store_Area_district',
                ),
            ),
            'Record_Pad',
            'Record_Upload',
        );
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id, $criteria),
        ));
    }

    /**
     * 兑换
     * @param $id
     */
    public function actionExchange($id)
    {
        $this->loadModelByPk(
            $id,
            '`exchange_status`=:exchange_status AND `status`=:status AND `receive_type`=:receive_type',
            array(':exchange_status'=>\Record::RECORD_EXCHANGE_STATUS_NO, ':status'=>\Record::_STATUS_NORMAL , ':receive_type' => \Prize::PRIZE_RECEIVE_TYPE_EXCHANGE)
        )->updateByPk(
            $id,
            array('exchange_status'=>\Record::RECORD_EXCHANGE_STATUS_YES, 'exchange_time'=>time())
        );
        
        if ( !isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
