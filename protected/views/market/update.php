<?php
$this->breadcrumbs = array(
    'Market' => array('index'),
    $model->title => array('view', 'id' => $model->id),
    'Update',
);

$this->menu = array(
    array('label' => Yii::t('market', 'List Advertisements'), 'url' => array('index')),
    array('label' => Yii::t('market', 'View this Ad'), 'url' => array('view', 'id' => $model->id)),
    array('label' => Yii::t('market', 'Delete Advertisement'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => Yii::t('app','Are you sure you want to delete this ad?'), 'csrf' => true)),
);


//tooltip javascript
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScriptFile($baseUrl . '/js/foundation.min.js');
$cs->registerScriptFile($baseUrl . '/js/vendor/custom.modernizr.js');
$cs->registerScript('foundation_tooltip', '$(document).foundation(\'tooltips\');', CClientScript::POS_READY);
?>

<h1><?php echo Yii::t('market', 'Update Advertisement') ?></h1>
<div class="row">
    <div class="small-6 large-4 columns">
        <?php
        echo CHtml::link(Yii::t('app', 'End advertisement'), array('expire',
            'id' => $model->id), array('class' => 'button secondary has-tip',
            'data-tooltip' => '',
            'title' => Yii::t('app', 'When you end an advertisement it gets expired, you can change expiration date to make it available again'),
            'confirm' => Yii::t('app','Are you sure you want to expire this ad?')));
        ?>
    </div>
</div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>