<div id="group_data">
	<?php
		if(isset($model->Dot_Pro) && is_array($model->Dot_Pro) && isset($model->Dot_Pro[0]->items_id)){
			foreach ($model->Dot_Pro as $Dot_Pro){
	?>
			<div class="group">
			<?php echo $form->HiddenField($Dot_Pro, "[]items_id")?>
				<hr class="hr"  />
				<div class="group_left" >
					<div class="group_title">
						<span class="group_name"><?php echo CHtml::encode($Dot_Pro->Pro_Items->name); ?></span>
						<span class="group_classliy"  title="<?php echo CHtml::encode($Dot_Pro->Pro_ItemsClassliy->info); ?>"><?php echo CHtml::encode($Dot_Pro->Pro_ItemsClassliy->name); ?></span>
					</div>
					<div class="group_address">
						<span>地址:</span>
							<?php
							 echo CHtml::encode(
											$Dot_Pro->Pro_Items->Items_area_id_p_Area_id->name.
											$Dot_Pro->Pro_Items->Items_area_id_m_Area_id->name.
											$Dot_Pro->Pro_Items->Items_area_id_c_Area_id->name.
											$Dot_Pro->Pro_Items->address
								);
							?>
					</div>
				</div>
				<div class="group_img" >
				<?php
						$img='';
						if(isset($Dot_Pro->Pro_Items->Items_ItemsImg[0]))
							$img=$Dot_Pro->Pro_Items->Items_ItemsImg[0]->img;
						echo $this->show_img($img);
				?>
				</div>
				<div style="clear: both;"></div>
				<div class="group_button">
						<?php echo CHtml::button('上移',array('class'=>'group_up'));?>
						<?php echo CHtml::button('下移',array('class'=>'group_down'));?>
						<?php echo CHtml::button('移除',array('class'=>'group_remove'));?>
				</div>
			</div>
		
	<?php 
			}
		}	
	?>
	</div>
	<div style="clear: both;"></div>
	<div class="row">
		<?php echo $form->hiddenField($model,'select_items'); ?>
		<?php echo $form->error($model,'select_items'); ?>	
		<?php echo $form->error($model->Dot_Pro[0],'items_id');  ?>
	</div>