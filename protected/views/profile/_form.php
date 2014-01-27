<?php if($model->scenario==='update'):?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#passwordChange').change(function(){
            var passwordConfirmWrapp = jQuery('#passwordConfirm-wrapp');
            var password = jQuery('#password');
            if (jQuery(this).is(':checked')) {
                password.removeAttr('disabled');
                passwordConfirmWrapp.show();
            } else {
                password.attr('disabled','disabled').val('');
                passwordConfirmWrapp.hide();
            }
        }).triggerHandler('change');
    });
</script>
<?php endif?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'form-create',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'htmlOptions'=>array('class'=>'form-horizontal')
)); ?>        
<fieldset>
    <div class="control-group <?php echo $model->hasErrors('email') ? 'error' : ''?>">
        <?php echo $form->label($model,'email',array('class'=>'control-label'))?>
        <div class="controls">
            <?php echo $form->textField($model,'email',array('class'=>'input-xlarge'))?>
            <span class="help-block"><?php echo $model->getError('email')?></span>
        </div>
    </div>
    <div class="control-group <?php echo $model->hasErrors('password') ? 'error' : ''?>">
        <?php echo $form->label($model,'password',array('class'=>'control-label'))?>
        <div class="controls">
            <?php if($model->scenario==='update'):?>
            <div class="input-append">
                <?php echo $form->passwordField($model,'password',array('class'=>'input-xlarge','disabled'=>'disabled','id'=>'password'))?><span class="add-on">
                    <label class="checkbox">
                        <?php echo $form->checkBox($model,'passwordChange',array('uncheckValue'=>null,'id'=>'passwordChange'))?> 
                        <?php echo $model->getAttributeLabel('passwordChange') ?>
                    </label>
                </span>                
            </div>
            <?php else:?>
                <?php echo $form->passwordField($model,'password',array('class'=>'input-xlarge',))?>
            <?php endif;?>
            <span class="help-block"><?php echo $model->getError('password')?></span>            
          
        </div>
    </div>
    
    <div id="passwordConfirm-wrapp" class="<?php if($model->scenario==='update'):?>hide<?php endif;?> control-group <?php echo $model->hasErrors('passwordConfirm') ? 'error' : '' ?>">
        <?php echo $form->label($model, 'passwordConfirm', array('class' => 'control-label')) ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'passwordConfirm', array('class' => 'input-xlarge')) ?>
            <span class="help-block"><?php echo $model->getError('passwordConfirm') ?></span>            
        </div>
    </div>    
    <div class="form-actions">
        <?php echo CHtml::submitButton($model->scenario==='create' ? 'Dodaj' : 'Edytuj',array('class'=>'btn btn-primary btn-large'))?>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

