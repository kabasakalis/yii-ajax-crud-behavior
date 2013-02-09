/**
 *   ajaxcrud_behavior.js
 *
 * Spiros Kabasaskalis,kabasakalis@gmail.com,
 * http://.reverbnation/spiroskabasakalis
 * http://iws/kabasakalis.gr
 *  Licensed under the MIT licenses:http://www.opensource.org/licenses/mit-license.php
 * Date: 2/4/13
 * Time: 10:39 PM
 *
 *
 */

//document ready
$(function () {

    var  spinnertarget = "#"+AjaxCrudBehavior.modelClassName + "-grid";

    $.refresh_grid = function () {
        var page = $("li.active  > a").text();
        updateOptions = {url:'', data:{}};
        updateOptions['data'][AjaxCrudBehavior.modelClassName + "_page"] = page;
        $.fn.yiiGridView.update(AjaxCrudBehavior.modelClassName + "-grid", updateOptions);
    }

    // DELETE RECORD

    $.ajax_delete = function () {
        $.delete = function (id) {
            $.ajax({
                type:"POST",
                url:Yii_js.baseUrl + "/" + AjaxCrudBehavior.controllerID + '/' + 'deleteAjax',
                data:{"id":id, "YII_CSRF_TOKEN":Yii_js.csrf},
                beforeSend:function () {
                    $(spinnertarget).spin("large", "white");
                },
                complete:function () {
                    $(spinnertarget).spin(false);
                },
                success:function (data) {
                   // var res = jQuery.parseJSON(data);
                    $.refresh_grid();
                }//success
            });//ajax
        };// delete function

        $('.del').click(function (index) {
            var id = $(this).attr('href');
            var msg = AjaxCrudBehavior.modelClassName + '  with ID ' + id + " will be deleted!Are you sure?"
            var n = noty({
                text:msg,
                type:'warning',
                dismissQueue:true,
                modal:true,
                layout:'center',
                theme:'defaultTheme',
                buttons:[
                    {addClass:'btn btn-primary', text:'Yes,Delete!', onClick:function ($noty) {
                        $.delete(id);
                        $noty.close();
                        noty({dismissQueue:true, force:true, layout:'center', theme:'defaultTheme', text:'You just deleted item with ID  ' + id, type:'success'});
                    }
                    },
                    {addClass:'btn btn-danger', text:'Cancel', onClick:function ($noty) {
                        $noty.close();
                        // noty({dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Cancel" button', type: 'error'});
                    }
                    }
                ]
            });
            return false;
        });
    } //ajax_delete ends


    //VIEW DETAILS
    $.ajax_view_details=function(){
        $('.view').each(function (index) {
                 var id = $(this).attr('href');
                 $(this).bind('click', function () {
                     $.ajax({
                         type:"POST",
                         url:Yii_js.baseUrl + "/" + AjaxCrudBehavior.controllerID + '/' + 'returnDetailsView',
                         data:{"id":id, "YII_CSRF_TOKEN":Yii_js.csrf },
                         beforeSend:function () {
                             $(spinnertarget).spin("large", "white");
                         },
                         complete:function () {
                             $(spinnertarget).spin(false);
                         },
                         success:function (data) {
                             $.fancybox(data,
                                 {    "transitionIn":"elastic",
                                     "transitionOut":"elastic",
                                     "speedIn":600,
                                     "speedOut":200,
                                     "width":500,
                                     "overlayShow":false,
                                     "hideOnContentClick":false
                                 });//fancybox
                             //  console.log(data);
                         } //success
                     });//ajax
                     return false;
                 });
             });
    }

    //UPDATE
    $.ajax_update=function(){
        $('.update').each(function (index) {
                 var id = $(this).attr('href');
                 $(this).bind('click', function () {
                     $.ajax({
                         type:"POST",
                         url:Yii_js.baseUrl + "/" + AjaxCrudBehavior.controllerID + '/' + 'returnAjaxForm',
                         data:{"update_id":id, "YII_CSRF_TOKEN":Yii_js.csrf },
                         beforeSend:function () {
                             $(spinnertarget).spin("large", "white");
                         },
                         complete:function () {
                             $(spinnertarget).spin(false);
                         },
                         success:function (data) {
                             $.fancybox(data,
                                 {    "transitionIn":"elastic",
                                     "transitionOut":"elastic",
                                     "speedIn":600,
                                     "speedOut":200,
                                     "overlayShow":false,
                                     "hideOnContentClick":false,
                                     "afterClose":function () {
                                         $.refresh_grid();
                                     }//onclosed
                                 });//fancybox
                             //  console.log(data);
                         } //success
                     });//ajax
                     return false;
                 });
             });
    }


   //MASS DELETE
    // bind  mass delete behavior
    $.mass_article_delete = function () {
        //mass delete articles
        $.delete_items = function (ids) {
            $.ajax({
                type:"POST",
                url:Yii_js.baseUrl + "/" + AjaxCrudBehavior.controllerID + '/' + 'AjaxMassDelete',
                data:{"ids":ids, "YII_CSRF_TOKEN":Yii_js.csrf},
                success:function (data) {
                    res = jQuery.parseJSON(data);
                    if (res.success == true) {
                        $.refresh_grid();
                    }
                } //success
            });//ajax
        };//end of mass delete articles

        $("#massdelete").bind("click", function (e) {
            e.preventDefault();
            var selected_ids = new Array();
            $("tbody .checkbox-column input:checked").each(function () {
                selected_ids.push($(this).val());
            });

            var ids = JSON.stringify(selected_ids);
            if (selected_ids.length <= 0) {
                var msg = "No Items selected.";
                var n = noty({
                    text:msg,
                    type:'warning',
                    dismissQueue:true,
                    modal:true,
                    layout:'center',
                    theme:'defaultTheme',
                    buttons:[

                        {addClass:'btn btn-info', text:'OK', onClick:function ($noty) {
                            $noty.close();
                            // noty({dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "OK" button', type: 'error'});
                        }
                        }
                    ]
                });

                return false;
            }
            var msg = "Delete all selected records?";
            var n = noty({
                text:msg,
                type:'warning',
                dismissQueue:true,
                modal:true,
                layout:'center',
                theme:'defaultTheme',
                buttons:[
                    {addClass:'btn btn-primary', text:'Yes,Delete!', onClick:function ($noty) {
                        $.delete_items(ids);
                        $noty.close();
                        noty({dismissQueue:true, force:true, layout:'center', theme:'defaultTheme', text:'Selected Items have just been deleted.', type:'success'});
                    }
                    },
                    {addClass:'btn btn-danger', text:'Cancel', onClick:function ($noty) {
                        $noty.close();
                        // noty({dismissQueue: true, force: true, layout: layout, theme: 'defaultTheme', text: 'You clicked "Cancel" button', type: 'error'});
                    }
                    }
                ]
            });
        });
    }

//CREATE
    $.ajax_create=function(){
        $('#add').bind('click', function () {
            $.ajax({
                type:"POST",
                url:Yii_js.baseUrl + "/" + AjaxCrudBehavior.controllerID + '/' + 'returnajaxform',
                data:{"YII_CSRF_TOKEN":Yii_js.csrf},
                beforeSend:function () {
                    $(spinnertarget).spin("large", "white");
                },
                complete:function () {
                    $(spinnertarget).spin(false);
                },
                success:function (data) {
                    $.fancybox(data,
                        {    "transitionIn":"elastic",
                            "transitionOut":"elastic",
                            "speedIn":600,
                            "speedOut":200,
                            "overlayShow":false,
                            "hideOnContentClick":false,
                            "afterClose":function () {
                                $.refresh_grid();
                            } //onclosed function
                        });//fancybox
                } //success
            });//ajax
            return false;
        });//bind
    }

    $.bind_crud = function () {
          $.ajax_view_details();
          $.ajax_update();
          $.ajax_delete();
      }
      $.bind_crud();
      $.ajax_create();
      $.mass_article_delete();
})//document ready

