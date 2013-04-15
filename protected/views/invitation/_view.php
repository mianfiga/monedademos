<?php
	$route = 'user/invited';
	$params = array('id'=>$data->id, 'code'=>$data->code);
?>
<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::link($data->note,array('view', 'id'=>$data->id)); ?>
	<br />
<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />
*/ ?>
	<b><?php echo CHtml::encode('Share Link'); ?>:</b>
	<?php echo CHtml::link($this->createAbsoluteUrl($route,$params), array_merge(array($route),$params)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('used')); ?>:</b>
	<?php echo CHtml::encode($data->used?'Yes':'No'); ?>
	<br />


</div>
