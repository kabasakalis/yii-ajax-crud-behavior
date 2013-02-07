<?php
/**
 *    admingrid.php file TEMPLATE
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
<p>
<h1 class="page-header"><?php echo  Yii::t($modelClassName, $modelClassName) ?> </h1>
</p>



<a  id="add"style="margin: 5px;" class="btn btn-primary" title="add">
    <img alt="create" src="<?php echo Yii::app()->baseUrl;?>/css/icons/plus.png">
               <span>
            <?php echo 'Add '.Yii::t($modelClassName, $modelClassName);?>
            </span>
</a>

<a style="margin: 5px;" id='massdelete' class="btn btn-danger" title="">
    <img alt="" src="<?php echo Yii::app()->baseUrl ?>/css/icons/cross.png">
         <span>
         <?php echo Yii::t($modelClassName, 'Delete Selected') ?>
         </span>
</a>

<p>
    <?php echo Yii::t('global', 'You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.') ?>
</p>

<div class="row">
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
           'id' => $modelClassName.'-grid',
          'htmlOptions' => array('class' => 'grid-view span12'),
                                                               'dataProvider' =>  $model->search($pagination) ,
                                                               'filter' => $model,
                                                               'pagerCssClass' => 'dataTables_paginate paging_bootstrap pagination',
                                                               'pager' => array('class' => 'BootPager','cssFile' => false,'htmlOptions' => array('class'=>'sas' )),
                                                               'itemsCssClass' => 'table table-striped table-bordered table-condensed',
                                                               'cssFile' =>false,
                                                               'selectableRows' => 3,
                                                                'columns' => array(
            //Don't remove this column,it is used for bulk deletion
            array(
                'name' => 'id',
                'value' => '$data->primaryKey',
                'selectableRows' => '10', //notice
                'class' => 'CCheckBoxColumn',
            ),
         /*   Modify here,configure property columns.------------------------------------------------------------------------------*/
              '[PROPERTY COLUMN]',
             '[PROPERTY COLUMN]',
            '...',
            '[PROPERTY COLUMN]',
       /* -------------------------------------------------------------------------------------------------------------------------------------*/
            array(
                'class' => 'CButtonColumn',
                'htmlOptions' => array('style' => 'width:75px'),
                      'buttons' => array(
                                                     'delete' => array(
                                                     'label' => Yii::t($modelClassName, 'Delete'), // text label of the button
                                                      'url' => '$data->primaryKey', // a PHP expression for generating the URL of the button
                                                      'imageUrl' => Yii::app()->baseUrl . '/css/icons/delete.png', // image URL of the button.If not set or false,a text link is used
                                                      'options' => array("class" => "del", 'title' => Yii::t($modelClassName, 'Delete')), // HTML options for the button
                                                      ),
                                                     'update' => array(
                                                     'label' => Yii::t($modelClassName, 'Update'), // text label of the button
                                                     'url' => '$data->primaryKey', // a PHP expression for generating the URL of the button
                                                     'imageUrl' =>Yii::app()->baseUrl  . '/css/icons/update.png', // image URL of the button.   If not set or false, a text link is used
                                                     'options' => array("class" => "update", 'title' => Yii::t($modelClassName, 'Update')), // HTML options for the    button tag
                                                        ),
                                                     'view' => array(
                                                      'label' => Yii::t($modelClassName, 'View'), // text label of the button
                                                      'url' => '$data->primaryKey', // a PHP expression for generating the URL of the button
                                                      'imageUrl' =>Yii::app()->baseUrl . '/css/icons/view.png', // image URL of the button.   If not set or false, a text link is used
                                                      'options' => array("class" => "view", 'title' => Yii::t($modelClassName, 'View')), // HTML options for the    button tag
                                                        )
                                                    ),
                   'template' => '{view}{update}{delete}',
            ),
    ),
           'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud();$("tbody tr:even").addClass("alt-row");}'
                                            ));
   ?>
</div>

