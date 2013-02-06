<?php

class ProductController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/col1';

	/**
	 * @return array action filters
	 */

public function init(){

    $this->ajaxCrudBehavior->register_Js_Css();
    parent::init();
}

    public function behaviors()
       {
           return array(
               'ajaxCrudBehavior' => array('class' => 'application.behaviors.AjaxCrudBehavior',
                   'modelClassName' => 'Product',
                   'form_alias_path' => 'application.views.product._form',
                   'view_alias_path' => 'application.views.product._view',
                   'pagination'=>'10'
               )
           );
       }

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='Product-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
