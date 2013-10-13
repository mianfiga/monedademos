<?php

Yii::import('ext.feed.*');

//$this->widget(
//        'ext.yii-feed-widget.YiiFeedWidget', array('url' => Yii::app()->createAbsoluteUrl('market/rss'), 'limit' => 1/* $dataProvider->itemCount */)
//);

// RSS 2.0 is the default type
$feed = new EFeed();

$feed->title = 'monedademos.es Market';
$feed->description = 'Anuncios del Market';

//$feed->setImage('monedademos.es',Yii::app()->createAbsoluteUrl('market/rss'),
//'http://www.yiiframework.com/forum/uploads/profile/photo-7106.jpg');

$feed->addChannelTag('language', Yii::app()->language);
$feed->addChannelTag('pubDate', date(DATE_RSS, time()));
$feed->addChannelTag('link', Yii::app()->createAbsoluteUrl('market/rss'));

// * self reference
$feed->addChannelTag('atom:link', Yii::app()->createAbsoluteUrl('market/rss'));

foreach($dataProvider->getData() as $record) {
    $item = $feed->createNewItem();

    $item->title = $record->title;
    $item->link = Yii::app()->createAbsoluteUrl('market/view',array('id' => $record->id));
    $item->date = $record->added;
    $item->description = $record->summary;
// this is just a test!!
//$item->setEncloser('http://www.tester.com', '1283629', 'audio/mpeg');

    if ($record->createdBy->class == 'Brand') {
        //$brandid = $model->createdBy->getObject()->id;
        $item->addTag('author', $record->createdBy->getObject()->name);
    }
    
    $item->addTag('guid', Yii::app()->createAbsoluteUrl('market/view',array('id' => $record->id)), array('isPermaLink' => 'true'));


    $feed->addItem($item);
}
$feed->generateFeed();
Yii::app()->end();
?>
