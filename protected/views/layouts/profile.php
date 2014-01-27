<?php $this->beginContent('application.views.layouts.base'); ?>
<div class="container" style="margin-top: 10em;">
    <div class="row">
        <div class="box span8 offset2">
            <div class="box-title">
                <div class="row-fluid">
                    <h3 class="pull-left"><?php echo $this->pageTitle?></h3>
                    <div class="pull-right">
                        <?php foreach($this->titleMenu as $item):?>
                            <?php $ico = (isset($item['icon']) && strlen($item['icon']) > 0) ? '<i class="'.$item['icon'].'"></i>' : '' ?>
                            <?php echo CHtml::link($ico.' '.$item['label'],$item['url'],array('class'=>'btn'))?>
                        <?php endforeach;?>
                    </div>
                </div>                
            </div>
            <div class="box-content">
                <?php $this->widget('ext.msg.BootAlertWidget'); ?>
                <?php echo $content ?> 
            </div>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>