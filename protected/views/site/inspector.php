<?php Yii::app()->clientScript->registerCss(uniqid(),'form {margin:0!important;} #inspector { padding: 15px ; border-bottom: 1px solid #ddd;} #iframe { border-top: 1px solid #fff;}');?>

<script type="text/javascript">
    function getBrowserHeight() {
        var winH = 0;
        if (document.body && document.body.offsetWidth) 
            winH = document.body.offsetHeight;
        
        if (document.compatMode=='CSS1Compat' &&
            document.documentElement &&
            document.documentElement.offsetWidth ) 
            winH = document.documentElement.offsetHeight;
        
        if (window.innerWidth && window.innerHeight) 
            winH = window.innerHeight;
        
        return winH;        
    }
    
    jQuery(document).ready(function() {
        jQuery('#iframe').attr('height',parseInt(getBrowserHeight())-parseInt(jQuery('#inspector').outerHeight(true)));
        
        jQuery('#submit').click(function() {
            jQuery.ajax({
                'type': 'POST',
                'url': '<?php echo $this->createUrl('site/saveToStorage',array('id'=>$model->getPrimaryKey())) ?>',
                'data': {'data':jQuery('#form-inspector').serialize()},
                'dataType': 'json',
                'success': function(data){
                    window.location = '<?php echo $this->createUrl($model->getPrimaryKey()==0 ? 'site/create' : 'site/update',array('id'=>$model->getPrimaryKey())) ?>';
                }
            });
            return false;
        });
    }); 
</script>    

<div id="inspector">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'form-inspector',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'htmlOptions'=>array('class'=>'form-inline')
)); ?>     
    
    <?php echo $form->label($model,'element',array('class'=>'control-label','for'=>'element'))?>
    <?php echo $form->textField($model,'element',array('class'=>'input-xxlarge','id'=>'element'))?>
    <?php echo CHtml::submitButton('Gotowe',array('class'=>'btn pull-right btn-primary','id'=>'submit'))?>                                
    
<?php $this->endWidget(); ?>    
</div>
<iframe name="iframe" id="iframe" frameborder="0" width="100%" height="400" src="<?php echo Yii::app()->createAbsoluteUrl('site/loader',array('url'=>$model->url)); ?>"></iframe>