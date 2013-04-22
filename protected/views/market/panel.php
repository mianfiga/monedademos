<?php
$this->breadcrumbs=array(
	'Market' => array('index'),
	$model->title => array('view', 'id'=>$model->id),
	'Panel'
);

$this->menu=array(
	array('label'=>Yii::t('market','List Advertisements'), 'url'=>array('index')),
	array('label'=>Yii::t('market','View this Ad'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('market','Update Advertisement'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('market','Delete Advertisement'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>
<?php echo Yii::t('app',($model->type=='offer'?'Offering':'Looking for').' '.$model->class) ?><br/>
<h1><?php echo $model->title; ?></h1>
<?php if($model->image!='')
			{ ?>
<img class="mk_view_img" src="<?php echo Yii::app()->request->baseUrl.'/images/market/'.$model->image ?>" alt="<?php echo CHtml::encode($model->title)?>"/>
<?php } ?>
<h3><?php echo Yii::t('app','Summary') ?></h3>
<p><?php echo $model->summary; ?></p>
<div class="mk_panel_usrlist">
<?php
//print_r($dataProvider);
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
		'columns'=>array(
				'name',
/*				array(
						'name' => 'q',
						'value' => '$data->marketAds[0]->title',
					),*/
				array(
						'name' => 'email',
						'value' => '($data->marketJoined[0]->show_mail?$data->email:\'\')',
					),
				array(
						'name' => 'status',
						'value' => '$data->marketJoined[0]->status',
					),
				array(
						'name' => 'Updated',
						'value' => '$data->marketJoined[0]->updated',
					),
				
				array(
						'id'=>'autoId',
						'class'=>'CCheckBoxColumn',
						'selectableRows' => '50',   
        ),
				array(
						'class'=>'CButtonColumn',
						'template'=>'{view}{update}',
						'buttons'=>array(
								'view' => array(
//							'label'=>'...',     //Text label of the button.
										'url'=>'Yii::app()->createUrl("contribution/view", array("id"=>$data->id))',
									),
								'update' => array(
										'label'=>'view & update',     //Text label of the button.
										'url'=>'Yii::app()->createUrl("market/panelView", array("ad_id"=>$data->marketJoined[0]->ad_id, "entity_id"=>$data->id))',
										'options'=>array('title'=>'View & Update'), //HTML options for the button tag.

		        			),
							),
					),
			),
));
?>
</div>
