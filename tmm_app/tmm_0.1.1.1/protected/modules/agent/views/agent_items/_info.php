<table border="0" class="choose_business">
	<tbody>
		<tr>
			<td>
				登陆手机号：
			</td>
			<td>
				<?php echo CHtml::encode($model->phone); ?>
			</td>
		</tr>
		<tr>
			<td>
				公司名称：
			</td>
			<td>
				<?php echo CHtml::encode($model->Store_Content->name); ?>
			</td>
		</tr>
		<tr>
			<td>
				公司地址：
			</td>
			<td>
				<?php echo CHtml::encode(
						$model->Store_Content->Content_area_id_p_Area_id->name.
						$model->Store_Content->Content_area_id_m_Area_id->name.
						$model->Store_Content->Content_area_id_c_Area_id->name.
						$model->Store_Content->address); 
				?>
			</td>
		</tr>
		<tr>
			<td>
				公司电话：
			</td>
			<td>
				<?php echo CHtml::encode($model->Store_Content->store_tel); ?>
			</td>
		</tr>
		<tr>
			<td class="text_top">
				选择项目管理人：
			</td>
			<td>
				<input type="radio" name="id" id="business" value="<?php echo CHtml::encode($model->Store_Content->id);?>" checked="checked">
				<span class="distance">
					&nbsp;商家
				</span>
				<br/>
				<?php 
                  		foreach ($model->Store_Content->Content_Stoer_Son as $son)
                  		{
                  ?>
				<input type="radio" name="id" id="sub_business" value="<?php echo $son->id;?>">
				<span class="distance">
					子账号<?php echo $son->phone;?>
				</span>
				<br/>
				<?php 
                  		}
                 	?>
			<?php echo CHtml::link('新增子账号',array('/agent/agent_store/createSon'),array('class'=>'blue distance'));?>
			</td>
		</tr>
	</tbody>
</table>

 