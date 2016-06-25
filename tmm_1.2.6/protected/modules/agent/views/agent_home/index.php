<frameset cols="20%,*" scrolling="no" noresize="no" border="0">
	<frame name="index" id="index" src="<?php echo Yii::app()->createUrl($this->frame['left']['url'])?>" scrolling="auto" noresize="no" border="0" style="overflow-x:hidden;overflow-y:hidden;">
	<frame name="content" src="<?php echo Yii::app()->createUrl($this->frame['right']['url'])?>">
</frameset>
<noframes>
	<body>
	<p><?php echo $content; ?></p>
	</body>
</noframes>
