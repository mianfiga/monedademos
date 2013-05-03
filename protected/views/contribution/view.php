<?php
$this->breadcrumbs = array(
    'Contributions' => array('index'),
    'View',
);

/* $this->menu=array(
  array('label'=>'Edit User', 'url'=>array('edit', 'id'=>$model->id)),
  //	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  //	array('label'=>'Manage User', 'url'=>array('admin')),
  ); */
?>

<h1>User Contribution</h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'contribution_title',
        'contribution_text',
        'zip',
        array(
            'label' => 'Contact',
            'type' => 'raw',
            'value' => CHtml::link(Yii::t('app', 'Send e-mail'), array('contribution/contact', 'id' => $model->id), array('class' => 'button'))
        ),
    ),
));
?>
<?php
echo $this->renderPartial('/rate/_list', array(
    'entity' => $entity,
    'dataProvider' => $dataProvider));
?>