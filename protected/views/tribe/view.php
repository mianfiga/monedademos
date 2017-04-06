<?php
/* @var $this TribeController */
/* @var $model Tribe */

$this->pageTitle = Yii::app()->name; ?>
<div class="row">
    <div class="small-12 large-6 columns">
        <div class="row">
            <div class="small-12 columns">
                <h2><?php echo $model->name; ?></h2>
            </div>
            <div class="small-12 columns">
                <?php echo $model->summary; ?><br/><br/>
            </div>
        </div>
        <div class="row">
            <div class="small-5 columns">
                <?php echo CHtml::link(Yii::t('app', 'More info'), array('/site/page', 'view' => 'info'), array('class' => 'site_join_button large expand secondary button')); ?>
            </div>
            <div class="small-7 columns">
              <?php
                if(!$entity){
                  echo CHtml::link(Yii::t('app', 'Join'), array('migrationRequest', 'id' => $model->id), array('class' => 'site_join_button large button expand'));
                }else if($entity->tribe_id == $model->id){
                  echo CHtml::link(Yii::t('app', 'Join'), '#', array('class' => 'site_join_button large button expand disabled', 'aria-disabled' => 'aria-disabled'));
                }else if($entity->lastMigration
                  && $entity->lastMigration->to_id == $model->id
                  && $entity->lastMigration->status == TribeMigration::STATUS_PENDING){
                  echo CHtml::link(Yii::t('tribe', 'Pending'), '#', array('class' => 'site_join_button large button success expand disabled', 'aria-disabled' => 'aria-disabled'));
                }else if(!$entity->lastMigration
                  || ($entity->lastMigration && $entity->lastMigration->status == TribeMigration::STATUS_REJECTED)
                  || ($entity->lastMigration && date_create($entity->lastMigration->added)->format('Y-m') != date_create()->format('Y-m'))){
                  echo CHtml::link(Yii::t('app', 'Join'), array('migrationRequest', 'id' => $model->id), array('class' => 'site_join_button large button expand'));
                } else {
                  echo CHtml::link(Yii::t('app', 'Join'), '#', array('class' => 'site_join_button large button expand disabled', 'aria-disabled' => 'aria-disabled'));
                }
              ?>
            </div>
        </div>
    </div>
    <div class="small-12 large-6 columns">
        <div class="flex-video widescreen">
            <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/V9mY8MKop6s" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="panel amount_data">
            <span class="moving"><?php echo Yii::t('app', 'Moving <strong>{total_amount}</strong> within <strong>{user_count}</strong> users', array('{total_amount}' => Transaction::amountSystemToUser($record->total_amount), '{user_count}' => $record->user_count)) ?>.</span>
            <span class="current_salary"><?php echo Yii::t('app', 'Average salary: <strong>{salary}</strong>, Minimum salary: <strong>{min_salary}</strong>', array('{salary}' => Transaction::amountSystemToUser($rule->salary), '{min_salary}' => Transaction::amountSystemToUser($rule->min_salary))) ?>.</span><br/>
            <span class="next_salary"><?php echo Yii::t('app', '<strong>Next month:</strong> Average salary: <strong>{salary}</strong>, Minimum salary: <strong>{min_salary}</strong>', array('{salary}' => Transaction::amountSystemToUser($next_rule->salary), '{min_salary}' => Transaction::amountSystemToUser($next_rule->min_salary))) ?>.</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 large-4 columns">
      <div class="list_top">
        <?php echo CHtml::link(Yii::t('app', 'Market'), array('market/index','tribe_id'=>$model->id)); ?>
      </div>
      <div class="list_body">
        <?php
        $this->widget('zii.widgets.CListView', array(
            'id' => 'ads-grid',
            'dataProvider' => $adsDataProvider,
            'itemView' => '//market/_viewList',
            'summaryText' => '',
        ));
        ?>
      </div>
    </div>
    <div class="small-12 large-4 columns">
      <div class="list_top">
        <?php echo CHtml::link( Yii::t('tribe', 'Tribe ads'), array('market/index','tribe_id'=>$model->id)); ?>
      </div>
      <div class="list_body">
        <?php
        $this->widget('zii.widgets.CListView', array(
            'id' => 'ads-grid',
            'dataProvider' => $newsDataProvider,
            'itemView' => '//market/_viewList',
            'summaryText' => '',
        ));
        ?>
      </div>
    </div>
    <div class="small-12 large-4 columns">
      <div class="list_top">
        <?php echo Yii::t('app', 'Public accounts') ?>
      </div>
      <div class="list_body">
        <?php
        $this->widget('zii.widgets.CListView', array(
            'id' => 'ads-grid',
            'dataProvider' => $publicAccountDataProvider,
            'itemView' => '//account/_viewList',
            'summaryText' => '',
        ));
        ?>
      </div>
    </div>
</div>

<?php //echo $this->renderPartial('/site/_socialRow', array('dataProviderMarketAd' => $dataProviderMarketAd)); ?>

<br/>
