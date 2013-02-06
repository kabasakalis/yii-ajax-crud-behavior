# AjaxCrudBehavior
Single Page CRUD operations on ActiveRecord models with controller behavior inherited AJAX actions

 © 2013  [Spiros Kabasakalis](http://iws.kabasakalis.gr/) 
 
  [The MIT License (MIT)]( http://opensource.org/licenses/MIT)
  
  ![AjaxCrudBehavior](https://lguzqa.dm1.livefilestore.com/y1pN2rQywAmmTrMk-9OM5v9saB6WHspBBN30o1Ji-MdQifVPFTsycmi9-bsvlpIfaBk04lRVNFr2dfAQBaWw3XlaR435mj5_ug8/ajaxcrud.jpg?psid=1)
  
  
## [LIVE DEMO](http://yiilab.kabasakalis.tk/product/admingrid)

## Overview
Create, update and view details of records in a fancybox pop up.
Single and bulk deletions  with modal dialog prompt (noty plugin).
This is actually a complete rewrite of my [ajaxcrudgiitemplate extension](http://www.yiiframework.com/extension/ajaxcrudgiitemplate).
Instead of gii generated files,I wrote a reusable  behavior that adds controller actions and eliminates
 repetition of code across different controllers.Javascript has been moved to a js file resulting in cleaner code.

##Requirements
Yii 1.1.12 or above,may work with older versions too.

## Setup.

- Copy  js_plugins folder in webroot folder.I don't like publishing assets.I register css and jss straight from a webroot subfolder.
- Copy css folder with icons  to webroot.
- Copy behaviors folder containing AjaxCrudBehavior.php  class file to protected folder.
- Copy BootPager.php and BaseController.php to components folder.
- Prepare the database table for your ActiveRecord model,say Product.(example product.sql provided).
- Create the model ActiveRecord class file that corresponds to product table,(example Product.php).
The only requirement is that it includes  a  search function  that returns  a CActiveDataProvider instance to populate CGridView:
 ~~~ php
 public function search($pagination)
 	{
 		// Warning: Please modify the following code to remove attributes that
 		// should not be searched.
 		$criteria=new CDbCriteria;

        //example,fill in with your model property names.
 		$criteria->compare('id',$this->id,true);
 		$criteria->compare('name',$this->name,true);
 		$criteria->compare('description',$this->description,true);
 		$criteria->compare('price',$this->price,true);

 		return new CActiveDataProvider($this, array(
 			'criteria'=>$criteria,
             'pagination' => array(
             'pageSize' =>$pagination,
                       ),
 		));
 	}
 ~~~
- Write a controller for the model.The only requirement for the controller is that includes the code below and
  extends from BaseController.
 ~~~ php
class [ MODEL CLASS NAME ] Controller extends BaseController
{
public function init(){
    $this->ajaxCrudBehavior->register_Js_Css();
    parent::init();
}

    public function behaviors()
       {
           return array(
                   'ajaxCrudBehavior' => array('class' => 'application.behaviors.AjaxCrudBehavior',
                   'modelClassName' =>'[MODEL CLASS NAME(ex.Product)]',
                   'form_alias_path' =>'[FORM PATH ALIAS (ex.application.views.product._form)]',
                   'view_alias_path' =>'[VIEW PATH ALIAS (ex.application.views.product._view)]' ,
                   'pagination'=>'10'      //page size for CGridView pagination
               )
           );
       }
...
Your controller code
...
}
 ~~~
Templates for the form and view files are provided,you will only need to modify property names and input fields
 for your specific model and copy to view folder of your controller.
- Last,you need to configure property columns for the CGridView in admingrid.php and copy it to controller's view folder.
   Again,a template is provided-it's straightforward.
- Make sure you include jquery before any other scripts.Either uncomment the relevant line in register_Js_Css() function of AjaxCrudBehavior or
   register it somewhwere else in your code.
- Example files use bootstrap styled markup.Uncomment the relevant line that registers bootstrap.css in register_Js_Css() function of AjaxCrudBehavior
   if it's not already registered somewhere else in your application.
- Navigate to /[controllerID]/admingrid to render the administration page.
- Example files for model,controller,form and view files are provided.


    ##Resources
- [Fancybox](http://www.fancyapps.com/fancybox/)
- [Noty](http://needim.github.com/noty/)
- [Bootstrap](http://twitter.github.com/bootstrap/)
- [Bootstrap extension for Yii]( http://www.yiiframework.com/extension/bootstrap)
- [JQueryForm Plugin]( http://malsup.com/jquery/form/)
- [spin.js](http://fgnass.github.com/spin.js/)






