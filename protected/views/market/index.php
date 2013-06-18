<?php
$this->breadcrumbs = array(
    'Market',
);

$this->menu = array(
    array('label' => Yii::t('market', 'List Advertisements'), 'url' => array('index')),
    array('label' => Yii::t('market', 'List my Ads'), 'url' => array('list', 'mode' => 1)),
    array('label' => Yii::t('market', 'List Ads I joined'), 'url' => array('list', 'mode' => 2)),
    array('label' => Yii::t('market', 'Create Advertisement'), 'url' => array('create')),
);
?>

<h1>Market</h1>
<p>Anuncios de productos y servicios tanto gratuítos como sufragables, al menos parcialmente, con Demos.</p>
<?php
echo $this->renderPartial('/market/_list', array(
    'dataProvider' => $dataProvider,
));
?>
