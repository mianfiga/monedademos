<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
//		'id',
//		'username',
//		'salt',
//		'password',
//		'name',
//		'surname',
//		'identification',
		'contribution_title',
		'contribution_text',
//		'email',
//		'contact',
		'zip',
//		'created',
//		'blocked',
//		'deleted',

		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
			'buttons'=>array
		    (
					'view' => array
						(
//							'label'=>'...',     //Text label of the button.
							'url'=>'Yii::app()->createUrl("user/contribution", array("id"=>$data->id))',       //A PHP expression for generating the URL of the button.
//							'imageUrl'=>'...',  //Image URL of the button.
//							'options'=>array(), //HTML options for the button tag.
//							'click'=>'...',     //A JS function to be invoked when the button is clicked.
//							'visible'=>'...',   //A PHP expression for determining whether the button is visible.
						),
					'update' => array
        		(
							'label'=>'Send e-mail',     //Text label of the button.
							'url'=>'Yii::app()->createUrl("user/contact", array("id"=>$data->id))',       //A PHP expression for generating the URL of the button.
//							'imageUrl'=>'...',  //Image URL of the button.
							'options'=>array('title'=>'Send e-mail'), //HTML options for the button tag.
//							'click'=>'...',     //A JS function to be invoked when the button is clicked.
//							'visible'=>'...',   //A PHP expression for determining whether the button is visible.
		        ),
				),
		),
	),
)); ?>
