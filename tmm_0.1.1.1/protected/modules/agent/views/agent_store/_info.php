<table border="0" class="choose_business">
    <tbody>
    <tr>
        <td>登陆手机号：</td>
        <td>
            <?php echo CHtml::encode($model->phone); ?>
        </td>
    </tr>
    <tr>
        <td>公司名称：</td>
        <td>
            <?php echo CHtml::encode($model->Store_Content->name); ?>
        </td>
    </tr>
    <tr>
        <td>公司地址：</td>
        <td class="address">
            <?php echo CHtml::encode($model->Store_Content->Content_area_id_p_Area_id->name.$model->Store_Content->Content_area_id_m_Area_id->name.$model->Store_Content->Content_area_id_c_Area_id->name.$model->Store_Content->address); ?>
        </td>
    </tr>
    <tr>
        <td>公司电话：</td>
        <td>
            <?php echo CHtml::encode($model->Store_Content->store_tel); ?>
        </td>
    </tr>
    <tr>
        <td>公司邮编：</td>
        <td>
            <?php echo CHtml::encode($model->Store_Content->store_postcode); ?>
        </td>
    </tr>
    <tr>
        <td>剩余可建子账号数：</td>
        <td>
            <?php echo $model->Store_Content->son_limit - $model->Store_Content->son_count; ?>
        </td>
    </tr>
    </tbody>
</table>