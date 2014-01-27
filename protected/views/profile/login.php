<?php $this->titleMenu = array(
    array('label'=>'Nowe konto', 'url'=>array('/profile/create'),'icon'=>'icon-plus-sign'),
);?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'form-login',
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
            <?php echo $form->passwordField($model,'password',array('class'=>'input-xlarge'))?>
            <span class="help-block"><?php echo $model->getError('password')?></span>            
            <span class="help-block"><?php echo CHtml::link('Zapomnialeś hasła?',array('profile/passwordReset'))?></span>
        </div>
    </div>
    <div class="form-actions">
        <?php echo CHtml::submitButton('Zaloguj',array('class'=>'btn btn-primary btn-large'))?>            
        <span class="help-inline">
            <label class="checkbox">
                <?php echo $form->checkBox($model,'rememberMe',array('uncheckValue'=>null))?> 
                <span rel="tooltip" title="Na okres miesiąca."><?php echo $model->getAttributeLabel('rememberMe') ?></span>
            </label>                             
        </span>
    </div>
</fieldset>
<?php $this->endWidget(); ?>

