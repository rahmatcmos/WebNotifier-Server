<?php $this->beginContent('application.views.layouts.base'); ?>
<div class="container" style="margin-top: 6em;">
    <?php $this->widget('bootstrap.widgets.BootNavbar', array(
        'htmlOptions'=>array('id'=>'navbar'),
        'fixed'=>false,
        'brand'=>Yii::app()->name,
        'brandUrl'=>Yii::app()->baseUrl,
        'collapse'=>true, // requires bootstrap-responsive.css
        'items'=>array(
            array(
                'class'=>'bootstrap.widgets.BootMenu',
                'items'=>array(
                    array('label'=>'Lista stron', 'url'=>array('site/list'), 'active'=>$this->id==='site' && $this->action->id==='list'),
                ),
            ),
            array(
                'class'=>'bootstrap.widgets.BootMenu',
                'htmlOptions'=>array('class'=>'pull-right'),
                'items'=>array(
                    '---',
                    array('label'=>'<i class="icon-white icon-user" style="opacity: 0.3; filter: alpha(opacity=30);"></i> '.Yii::app()->user->name,'encodeLabel'=>false, 'url'=>'#notarget', 'htmlOptions'=>array('class'=>'hide'), 'items'=>array(
                        array('label'=>'Zmiana danych', 'url'=>array('profile/update')),
                        '---',
                        array('label'=>'Wyloguj mnie', 'url'=>array('profile/logout')),
                    )),
                ),
            ),
        ),
    )); ?>    
    
    <div class="row-fluid">
        <div class="box span12">
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
    <div class="row-fluid" style="margin-top: 20px;">
        <span class="shadow pull-right">&copy; 2012 Maciej Kłak</span>
    </div>
</div>
<?php $this->endContent(); ?>