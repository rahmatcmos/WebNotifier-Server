<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.inputObserver.js'); ?>

<script type="text/javascript">
    function validateURL(value) {
        return /^(https?):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
    }
    
    jQuery(document).ready(function(){
        jQuery('#url').inputObserver({
            onChange: function(obj,value) {
                var elements = jQuery('#element, #element-inspector');
                if (validateURL(value)) 
                    elements.removeAttr('disabled');
                else 
                    elements.attr('disabled','disabled');
            },
            trigger: true
        });
        
        jQuery('#element-inspector').click(function(){
            jQuery.ajax({
                'type': 'POST',
                'url': '<?php echo $this->createUrl('site/saveToStorage',array('id'=>$model->isNewRecord ? 0 : $model->getPrimaryKey())) ?>',
                'data': {'data':jQuery('#form-site').serialize()},
                'dataType': 'json',
                'success': function(data){
                    window.location = '<?php echo $this->createUrl('site/inspector',array('id'=>$model->isNewRecord ? 0 : $model->getPrimaryKey())) ?>';
                }
            });            
      
            return false;
        });
});
</script>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'form-site',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'htmlOptions'=>array('class'=>'form-horizontal')
)); ?>        
<fieldset>
    <div class="control-group <?php echo $model->hasErrors('name') ? 'error' : ''?>">
        <?php echo $form->label($model,'name',array('class'=>'control-label'))?>
        <div class="controls">
            <?php echo $form->textField($model,'name',array('class'=>'input-xxlarge'))?>
            <span class="help-block"><?php echo $model->getError('name')?></span>
        </div>
    </div>
    <div class="control-group <?php echo $model->hasErrors('url') ? 'error' : ''?>">
        <?php echo $form->label($model,'url',array('class'=>'control-label'))?>
        <div class="controls">
            <?php echo $form->textField($model,'url',array('class'=>'input-xxlarge','id'=>'url'))?>
            <span class="help-block"><?php echo $model->getError('url')?></span>           
        </div>
    </div>
    <div class="control-group">
        <?php echo $form->label($model,'element',array('class'=>'control-label','for'=>'element'))?>
        <div class="controls">
            <div class="input-append">
                <?php echo $form->textField($model,'element',array('class'=>'input-xxlarge','id'=>'element','rel'=>'tooltip','title'=>'Ścieżka w języku XPath'))?><button class="btn" type="button" id="element-inspector">wybierz</button>
            </div>      
        </div>
    </div>    
    <div class="control-group">
        <label class="control-label">Monitorowanie</label>
        <div class="controls">
            <label class="checkbox">
                <?php echo $form->checkBox($model,'checkAvailability',array())?> 
                <?php echo $model->getAttributeLabel('checkAvailability') ?>
            </label>  
            <label class="checkbox">
                <?php echo $form->checkBox($model,'checkStatus',array())?> 
                <?php echo $model->getAttributeLabel('checkStatus') ?>
            </label>   
            <label class="checkbox">
                <?php echo $form->checkBox($model,'checkContent',array())?> 
                <?php echo $model->getAttributeLabel('checkContent') ?>
            </label>             
        </div>
    </div>    
    <div class="form-actions">
        <?php echo CHtml::submitButton($model->scenario==='create' ? 'Dodaj' : 'Edytuj',array('class'=>'btn btn-primary btn-large'))?>                             
        </span>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

