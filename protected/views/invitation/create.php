<?php
$this->breadcrumbs=array(
	'Invitations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Create Invitation', 'url'=>array('create')),
	array('label'=>'List Invitation', 'url'=>array('index')),
);
?>

<h1>Get Invitation</h1>
Haga clic en continuar para recibir su url de invitación. Utilice el campo a continuación para anotar información sobre la invitación, esa información no será accesible por el usuario invitado:<br/>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
