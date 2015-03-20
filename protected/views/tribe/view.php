<?php
/* @var $this TribeController */
/* @var $model Tribe */

$this->pageTitle = Yii::app()->name; ?>
<div class="row">
    <div class="small-12 columns" >
        <div id="summary">
            Demos is a social currency made as an alternative to official currencies that runs at <?php echo CHtml::link('monedademos.es', array('/site/index')); ?>.
        </div>
    </div>
</div>
<div class="row">

    <?php /*            <div class="small-12 large-6 columns">
      <img class="site_img_steps" src="<?php echo Yii::app()->request->baseUrl?>/images/3pasos.png" alt="1º Cobrar, 2º Comerciar, 3º Colaborar" />
      </div> */ ?>
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
                <?php echo CHtml::link(Yii::t('app', 'Join now'), array('/user/create'), array('class' => 'site_join_button large button expand')); ?>
            </div>
        </div>
    </div>
    <div class="small-12 large-6 columns">
        <div class="flex-video widescreen">
            <iframe width="560" height="315" src="http://www.youtube.com/embed/V9mY8MKop6s" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>
<?php /*<div class="row">
    <div class="small-12 large-12 columns">
        <ul class="inline-list">
            <li><strong>No te pierdas nada:</strong> </li>
            <li class="social twitter"><a href="http://twitter.com/monedademos">@monedademos</a></li>
            <li class="social facebook"><a href="http://facebook.com/monedaDemos">Moneda Demos</a></li>
            <li class="social googleplus"><a href="https://plus.google.com/113493943316049288613" rel="publisher">Moneda Demos</a></li>
            <li class="social blog"><a href="http://blog.monedademos.es">Blog de DEMOS</a></li>
        </ul>
    </div>
</div> */ ?>
<div class="row">
    <div class="small-12 columns">
        <div class="panel amount_data">
            <span class="moving"><?php echo Yii::t('app', 'Moving <strong>{total_amount}</strong> within <strong>{user_count}</strong> users', array('{total_amount}' => Transaction::amountSystemToUser($record->total_amount), '{user_count}' => $record->user_count)) ?>.</span>
            <span class="current_salary"><?php echo Yii::t('app', 'Average salary: <strong>{salary}</strong>, Minimum salary: <strong>{min_salary}</strong>', array('{salary}' => Transaction::amountSystemToUser($rule->salary), '{min_salary}' => Transaction::amountSystemToUser($rule->min_salary))) ?>.</span><br/>
            <span class="next_salary"><?php echo Yii::t('app', '<strong>Next month:</strong> Average salary: <strong>{salary}</strong>, Minimum salary: <strong>{min_salary}</strong>', array('{salary}' => Transaction::amountSystemToUser($next_rule->salary), '{min_salary}' => Transaction::amountSystemToUser($next_rule->min_salary))) ?>.</span>
        </div>
    </div>
</div>

<?php //echo $this->renderPartial('/site/_socialRow', array('dataProviderMarketAd' => $dataProviderMarketAd)); ?>

<br/>
