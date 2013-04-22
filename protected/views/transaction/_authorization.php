<div class="row">
    <div class="small-12 large-4 columns">
        <h5 class="has-tip" data-tooltip title="<?php echo Yii::t('app', 'You can authorize other users to manage your account by sending an email to contacto@monedademos.es.');?>"><?php echo Yii::t('app', 'Authorizations') ?></h5>
        <?php
        $auths = Authorization::getByAccount($account->id);
        foreach ($auths as $auth) {
            echo $auth->getAccountNumber(false) . ' ' . $auth->class . '<br/>';
        }

        ?>
    </div>
</div>

<?php
/* if (!isset($highlight)) $highlight = null;

  ?>


  <?php echo CHtml::link(Yii::t('app', 'Pay'), array('transfer'),
  array('class' => 'site_join_button large expand button'. ($highlight=='charge'?' secondary':''))); ?>
  </div>
  <div class="small-6 large-3 columns">
  <?php echo CHtml::link(Yii::t('app', 'Charge'), array('charge'), array('class' => 'site_join_button large expand button'. ($highlight!='charge'?' secondary':''))); ?>
  </div>
  <div class="small-6 large-3 columns">
  <?php echo CHtml::link(Yii::t('app', ($showingTransactions ?'Hide':'View movements')), array(($showingTransactions?'index':'list')), array('class' => 'site_join_button large expand secondary button')); ?>
  </div>

  <div class="small-6 large-3 columns">
  <?php echo CHtml::link(Yii::t('app', 'View pending'), array('pending/index'), array('class' => 'site_join_button large expand secondary button')); ?>
  </div>
  </div>
 */