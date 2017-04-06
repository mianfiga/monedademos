<div class="mtrpwsumm">
 <?php echo CHtml::link(Yii::t('app', 'Account'). ' ' . CHtml::encode($data->id), array('account/view', 'id'=>$data->id)); ?>
 <div><?php echo substr(strip_tags($data->title), 0, 100) . ' ...'; ?></div>
</div>
