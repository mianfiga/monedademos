<?php
$this->breadcrumbs = array(
    'Users' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => Yii::t('app', 'Edit contribution'), 'url' => array('edit', 'id' => Yii::app()->user->getId())),
    array('label' => Yii::t('app', 'Update user info'), 'url' => array('update', 'id' => Yii::app()->user->getId())),
//	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage User', 'url'=>array('admin')),
);
if (strpos($model->abilities, User::ABILITY_INVITE) !== false) {
    $this->menu[] = array(
        'label' => Yii::t('app', 'Invite friend'), 'url' => array('invitation/index')
    );
}

//print_r($model);
?>

<h1>User <?php echo $model->username; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'username',
        'name',
        'surname',
        'birthday',
        'identification',
        'contribution_title',
        'contribution_text',
        'email',
        'contact',
        'zip',
    ),
));
?>

<?php
echo $this->renderPartial('/rate/_list', array(
    'entity' => $entity,
    'dataProvider' => $dataProvider));
?>