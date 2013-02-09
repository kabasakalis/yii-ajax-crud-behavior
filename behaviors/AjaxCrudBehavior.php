<?php
/**
 * AjaxCrudBehavior class file.
 *
 * Date: 1/29/13
 * Time: 12:00 PM
 *
 * @author: Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://iws.kabasakalis.gr/
 * @link http://www.reverbnation.com/spiroskabasakalis
 * @copyright Copyright &copy; Spiros Kabasakalis 2013
 * @license http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @version 1.0.0
 */

class AjaxCrudBehavior extends CBehavior
{

    /**
     * @var string the model class name
     */
    public $modelClassName;

    /**
     * @var string the partial form view alias path (example:application.views.controllerId._form) used for creating and updating the model.
     */
    public $form_alias_path;

    /**
     * @var string the partial details view alias path (example:application.views.controllerId._view) used for viewing model details.
     */
    public $view_alias_path;

    /**
     * @var string number of records in each page
     */
    public $pagination = 10;

    /**
     *   Renders the GridView view.
     */
    public function actionAdminGrid()
    {
        $this->owner->breadcrumbs[Yii::t('article', $this->modelClassName)] = $this->owner->createUrl($this->owner->id . '/admingrid');
        $model = new $this->modelClassName('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET[$this->modelClassName]))
            $model->attributes = $_GET[$this->modelClassName];
        $this->owner->render('admingrid', array('model' => $model,
                                                        'modelClassName' => $this->modelClassName,
                                                         'pagination' => $this->pagination
        ));
    }



    public function register_Js_Css()
    {
        $baseUrl = Yii::app()->baseUrl;
        $csrf = Yii::app()->request->csrfToken;
        $controllerID = $this->owner->id;

        //pass php variables to javascript
        $ajaxcrud_behavior_js = <<<EOD
      (function ($) {
         AjaxCrudBehavior = {
           controllerID:'$controllerID',
           modelClassName:'$this->modelClassName'
              },
         Yii_js = {
           baseUrl:'$baseUrl',
           csrf:'$csrf'
           }
      }(jQuery));
EOD;

        //uncomment to register jquery only if you have not already registered it somewhere else in your application
        //Yii::app()->clientScript->registerCoreScript('jquery');

        //uncomment to register bootstrap css if you have not already included  it (optional),or else you will have to style the html by yourself.
        //Yii::app()->clientScript->registerCssFile($baseUrl . '/js_plugins/bootstrap/css/bootstrap.css');
        //Yii::app()->clientScript->registerCoreScript('cookie');
        Yii::app()->clientScript->registerScript(__CLASS__ . 'ajaxcrud_behavior_params', $ajaxcrud_behavior_js, CClientScript::POS_END);

        //modal dialog with noty.js
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/noty/js/noty/jquery.noty.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/noty/js/noty/layouts/center.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/noty/js/noty/themes/default.js', CClientScript::POS_END);
        //js spinner
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/spin.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/jquery.spin.js', CClientScript::POS_END);
        //fancybox
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css');

        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/json2/json2.js');
        Yii::app()->clientScript->registerCoreScript('yiiactiveform');

        // jquery.form.js plugin http://malsup.com/jquery/form/
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/ajaxform/ajaxcrud_form.js', CClientScript::POS_END);

        Yii::app()->clientScript->registerScriptFile($baseUrl . '/js_plugins/ajaxcrud_behavior.js', CClientScript::POS_END);

    }

/**
 *     Loads the model with primaryKey of $id
*/
    public function loadModel($id)
    {
        $model = CActiveRecord::model($this->modelClassName)->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

/**
 *    Renders the model details view  inside a  fancybox popup
 */
    public function actionReturnDetailsView()
    {
        //don't reload these scripts or they will mess up the page
        $this->excludeScripts();

        $model = $this->loadModel($_POST['id']);
        $this->owner->renderPartial($this->view_alias_path, array('model' => $model ), false, true);
    }

/**
 *      Renders the update or create form inside a  fancybox popup
 */
    public function actionReturnAjaxForm()
    {
          //don't reload these scripts or they will mess up the page
        $this->excludeScripts();
        //Figure out if we are updating a Model or creating a new one.
        if (isset($_POST['update_id'])) $model = $this->loadModel($_POST['update_id']);
        else $model = new $this->modelClassName;
        $this->owner->renderPartial($this->form_alias_path, array(
                'model' => $model,
                'modelClassName' => $this->modelClassName,
                'controllerID' => $this->owner->id,
            ),
            false, true);
    }

/**
 *     Updates the model
 */
    public function actionUpdateAjax()
    {
        if (isset($_POST[$this->modelClassName]) &&Yii::app()->request->isAjaxRequest) {
            $model = $this->loadModel($_POST['update_id']);
            $model->attributes = $_POST[$this->modelClassName];
            if ($model->save()) {
                echo json_encode(array('success' => true));
            } else echo json_encode(array('success' => false));
        }
        else
                   throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    /**
     *   Creates a new model
     */
    public function actionCreateAjax()
    {
        if (isset($_POST[$this->modelClassName]) &&Yii::app()->request->isAjaxRequest) {
            $model = new $this->modelClassName;
            $model->attributes = $_POST[$this->modelClassName];
            if ($model->save()) {
                echo json_encode(array('success' => true,
                        'id' => $model->primaryKey)
                );
                exit;
            } else {
                echo json_encode(array('success' => false,
                        'message' => 'Error.' . $this->modelClassName . ' has not been created.'
                    )
                );
                exit;
            }
        }
        else
                   throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
       *    Deletes a  model
       */
    public function actionDeleteAjax()
    {
        if (isset($_POST['id']) &&Yii::app()->request->isAjaxRequest) {
        $id = $_POST['id'];
        $deleted = $this->loadModel($id);
        if ($deleted->delete()) {
            echo json_encode(array('success' => true));
            exit;
        } else {
            echo json_encode(array('success' => false));
            exit;
        }
        }  else
                    throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }




    /**
      *    Deletes more than one model at once.
      */
    public function actionAjaxMassDelete()
    {
        if (isset($_POST['ids']) &&Yii::app()->request->isAjaxRequest) {
            $success = true;
            $ids = json_decode($_POST['ids']);
            foreach ($ids as $id) {
                $deleted = CActiveRecord::model($this->modelClassName)->findByPk($id)->delete();
                if ($deleted) $success = $success && true;
            }
            echo   json_encode(array('success' => $success));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $this->owner->id . '-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


    /**
     *  don't reload  scripts or they will mess up the page
     */
    private function excludeScripts()
    {

       Yii::app()->clientScript->scriptMap['*.js'] = false;
    }

}

