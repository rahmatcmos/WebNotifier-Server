<?php $this->titleMenu = array(
    array('label'=>'Logowanie', 'url'=>array('/profile/login'),'icon'=>'icon-user'),
);?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'form-passwordReset',
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
    <?php if($token):?>
        <div class="control-group <?php echo $model->hasErrors('password') ? 'error' : ''?>">
            <?php echo $form->label($model,'password',array('class'=>'control-label'))?>
            <div class="controls">

                <?php echo $form->passwordField($model,'password',array('class'=>'input-xlarge',))?>
      
                <span class="help-block"><?php echo $model->getError('password')?></span>            

            </div>
        </div>

        <div class="control-group <?php echo $model->hasErrors('passwordConfirm') ? 'error' : '' ?>">
            <?php echo $form->label($model, 'passwordConfirm', array('class' => 'control-label')) ?>
            <div class="controls">
                <?php echo $form->passwordField($model, 'passwordConfirm', array('class' => 'input-xlarge')) ?>
                <span class="help-block"><?php echo $model->getError('passwordConfirm') ?></span>            
            </div>
        </div>      
    
    <?php endif; ?>
    <div class="form-actions">
        <?php echo CHtml::submitButton($token ? 'Zapisz' : 'Wyślij informacje',array('class'=>'btn btn-primary btn-large'))?>            
    </div>
</fieldset>
<?php $this->endWidget(); ?>

