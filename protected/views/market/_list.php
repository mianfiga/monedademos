<?php
/* @var $this MarketController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->widget('zii.widgets.CListView', array(
    'id' => 'ads-grid',
    'dataProvider' => $dataProvider,
    'itemView' => '/market/_view',
    'sortableAttributes' => array(
        'updated',
        'added',
    ),
));
?>
