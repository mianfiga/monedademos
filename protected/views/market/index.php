<?php
$cs = Yii::app()->getClientScript();
$cs->registerLinkTag("alternate", "application/rss+xml", Yii::app()->createAbsoluteUrl('market/rss'));

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
<a class="mk_rss" href="<?php echo Yii::app()->createUrl('market/rss') ?>"><img src="<?php echo Yii::app()->request->baseUrl ?>/images/fc-webicon-rss-m.png" alt="Market RSS" /></a>
<h1>Market<?php echo ($tribe?'<span class="subheader"> '.$tribe->name.'</span>':'') ?></h1>


<p>Anuncios de productos y servicios no especulativos (<a href="http://monedademos.blogspot.com.es/2014/04/declaracion-de-la-asamblea-demos-acerca.html">?</a>), tanto gratuítos como sufragables, al menos parcialmente, con Demos.</p>
<?php $this->renderPartial('_search', array(
  'model' => $model
))
?>
<?php
echo $this->renderPartial('/market/_list', array(
    'dataProvider' => $dataProvider,
));
?>
