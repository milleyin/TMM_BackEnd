<div class="group_container">
    <div class="group_header">
        <span class="title">随行人员</span>
<table border="1" >

    <tr>
        <td>姓名</td>
        <td>性别</td>
        <td>身份证号码</td>
        <td>手机号</td>
    </tr>
    <?php foreach($model->Order_OrderRetinue as $retinue){ ?>
        <tr>
            <td>
                <?php if($retinue->is_main==1){ ?>
                    <div id="zhu">主</div>
                <?php }?>
                <?php echo CHtml::encode($retinue->retinue_name); ?>
            </td>
            <td><?php echo CHtml::encode(OrderRetinue::$_retinue_gender[$retinue->retinue_gender]); ?></td>
            <td><?php echo CHtml::encode($retinue->retinue_identity); ?></td>
            <td><?php echo CHtml::encode($retinue->retinue_phone); ?></td>
        </tr>
    <?php } ?>
</table>
    </div>
</div>
</br>
</br>
</br>