<?php
$this->breadcrumbs=array(
	'Contributions'=>array('index'),
	'View',
);

/*$this->menu=array(
	array('label'=>'Edit User', 'url'=>array('edit', 'id'=>$model->id)),
//	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage User', 'url'=>array('admin')),
);*/
?>

<h1>User Contribution</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
/*		'username',
		'name',
		'surname',
		'birthday',
		'identification',*/
		'contribution_title',
		'contribution_text',
/*		'email',
		'contact',*/
		'zip',
		array(
            'label'=>'Contact',
            'type'=>'raw',
            'value'=>CHtml::link(Yii::t('app','Send e-mail'),
                                 array('contribution/contact','id'=>$model->id),array('class' =>'button'))
					),
	),
)); ?>
