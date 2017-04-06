<?php
$this->breadcrumbs=array(
	Yii::t('app','tribe')=>array('tribe/view','id' => $model->tribe_id),
	$model->title,
);

/* $this->menu=array(
	array('label'=>'List Account', 'url'=>array('index')),
	array('label'=>'Create Account', 'url'=>array('create')),
	array('label'=>'Update Account', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Account', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Account', 'url'=>array('admin')),
);*/
?>

<h1>View Account #<?php echo $model->id; ?></h1>

<?php if ($transactionDataProvider) {
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'transaction-grid',
        'dataProvider' => $transactionDataProvider,
//        'filter' => $model,
        'columns' => array(
            array(
                'class' => 'CButtonColumn',
                'template' => '{view}',
                'buttons' => array(
                    'view' => array(
                        'url' => 'Yii::app()->createUrl("transaction/view", array("id"=>$data->id))', //A PHP expression for generating the URL of the button.
                    ),
                ),
            ),
            'executed_at',
            'class',
            array(
							'name' => 'foreign_account_number',
							'value' => '($data->deposit_account_number=='. $model->id .'?$data->charge_account_number:$data->deposit_account_number)',
						),
            'subject',
            array(
                'name' => 'amount',
                'value' => '($data->deposit_account_number=='. $model->id .'?\'\':\'- \').$data->getAmount()',
                'htmlOptions' => array('class' => 'amounts'),
            )
        ),
    ));
} ?>
