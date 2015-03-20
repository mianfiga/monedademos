<div class="mtrpwsumm">
 <?php echo CHtml::link(CHtml::encode($data->title), array('market/view', 'id'=>$data->id)); ?>
    <span class="list_date"><?php echo date(' -&\nb\sp;M&\nb\sp;d',strtotime($data->updated)); ?></span>
    <div><?php echo substr(strip_tags($data->summary . $data->description), 0, 100) . ' ...'; ?></div>
</div>
