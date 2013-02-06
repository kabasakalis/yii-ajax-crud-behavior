<?php
/**
 *    _form  file TEMPLATE
 *  2/6/13
 *  12:04 AM
 * @author Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://iws.kabasakalis.gr/
 * @link http://www.reverbnation.com/spiroskabasakalis
 * @copyright Copyright &copy; 2013 Spiros Kabasakalis
 * @since 1.0
 * @license  http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @version 1.0.0
 */
?>

<?php if ($model->isNewRecord) : ?>
<h3><?php echo Yii::t($modelClassName, 'Create') ?> <?php echo Yii::t($modelClassName,$modelClassName) ?></h3>
<?php elseif (!$model->isNewRecord): ?>
<h3><?php echo Yii::t($modelClassName, 'Update') ?> <?php echo Yii::t($modelClassName, $modelClassName) ?></h3>
<?php endif; ?>



<?php
$val_error_msg = Yii::t($modelClassName, 'Error'.$modelClassName.' has not been not saved.');
$val_success_message = ($model->isNewRecord) ?
Yii::t($modelClassName,$modelClassName. ' has been created successfully.') :
Yii::t($modelClassName,$modelClassName.' has been updated successfully.');
?>

<div id="success-note" class="alert alert-success"
     style="display:none;">
    <?php   echo $val_success_message;  ?>
</div>

<div id="error-note" class="alert alert-error"
     style="display:none;">
    <?php   echo $val_error_msg;  ?>
</div>

<div id="ajax-form" class='form'>
    <?php
    $formId =$modelClassName.'-form';
    $actionUrl =
            ($model->isNewRecord) ? CController::createUrl($controllerID.'/'.'createajax')
                                                               : CController::createUrl($controllerID.'/'.'updateajax');

    $form = $this->beginWidget('CActiveForm', array(
                                                   'id' => $formId,
                                                   //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                                   'action' => $actionUrl,
                                                   // 'enableAjaxValidation'=>true,
                                                   'enableClientValidation' => true,
                                                   'focus' => array($model, 'name'),
                                                   'errorMessageCssClass' => 'alert alert-error',
                                                   'clientOptions' => array(
                                                       'validateOnSubmit' => true,
                                                       'validateOnType' => false,
                                                       'inputContainer' => '.control-group',
                                                       'errorCssClass' => 'error',
                                                       'successCssClass' => 'success',
                                                       'afterValidate' => 'js:function(form,data,hasError){$.js_crud_afterValidate(form,data,hasError);  }',
                                                   ),
                                              ));
    ?>

    <?php
         echo $form->errorSummary($model,
                                                                     '<div style="font-weight:bold">Please correct these errors:</div>' ,
                                                                     NULL,
                                                                      array('class' => 'alert alert-error')
                                                                    );
    ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <fieldset>

        <!--  ------------------------------------------------------------------------------------------------------------------------------------
        Fill in div blocks like the following for every attribute of your model that you want to include in the form.
        -->

        <div class="control-group">
            <?php echo $form->labelEx($model, '[ATTRIBUTE]', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model, '[ATTRIBUTE]', array('class' => 'span4', 'size' => 60, 'maxlength' => 128)); ?>
                <p class="help-block"><?php echo $form->error($model, '[ATTRIBUTE]'); ?></p>
            </div>
        </div>

      <!--    -------------------------------------------------------------------------------------------------------------------------------------     -->



        <input type="hidden" name="YII_CSRF_TOKEN"
               value="<?php echo Yii::app()->request->csrfToken; ?>"/>

        <?php  if (!$model->isNewRecord): ?>
        <input type="hidden" name="update_id"
               value=" <?php echo $model->id; ?>"/>
        <?php endif; ?>

        <div class="control-group">
            <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t($modelClassName, 'Submit')
                                                                                                                                        : Yii::t($modelClassName, 'Save'),
                                                                                     array('class' => 'btn btn-large  pull-right')); ?>
        </div>
</fieldset>
        <?php  $this->endWidget(); ?>
</div>
<!-- form -->




