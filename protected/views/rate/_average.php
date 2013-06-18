<?php
/* @var $this RateController */
/* @var $entity Entity */
?>
<?php

$this->widget('CStarRating', array(
    'name' => 'rating',
    'model' => $entity,
    'attribute' => 'rate',
    'minRating' => 1,
    'maxRating' => 5,
    'starCount' => 5,
    'readOnly' => true,
    'starWidth' => '64',
));

echo '&nbsp;&nbsp;' . Yii::t('app', 'In {count} rates', array('{count}' => $entity->rates));
?>
