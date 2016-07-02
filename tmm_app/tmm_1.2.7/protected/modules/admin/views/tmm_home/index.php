
<frameset rows="88,*" id="admin">
    <frame frameborder="0" id="admin_top"  scrolling="no"  name="admin_top"   noresize="no"
src="<?php echo Yii::app()->createUrl($this->frame['top']['url'],$this->frame['top']['params'])?>" />
	<frameset cols="190,*"  id="admin_body">
		<frame framespacing="0" frameborder="0" id="admin_left" name="admin_left"  noresize="no"
src="<?php echo Yii::app()->createUrl($this->frame['left']['url'],$this->frame['left']['params'])?>" />
   		<frame framespacing="0"  frameborder="0" id="admin_right"  name="admin_right"   noresize="no"
src="<?php echo Yii::app()->createUrl($this->frame['right']['url'],$this->frame['right']['params'])?>" />
	</frameset>
	<noframes>
		<body>
			<p><?php echo $content; ?></p>
		</body>
	</noframes>
</frameset>
