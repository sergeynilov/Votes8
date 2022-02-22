var this_backend_home_url
var this_csrf_token

function backendTodoContainerPage(page, paramsArray) {  // constructor of backendTodoContainerPage
    // alert( "backendTodoContainerPage paramsArray::"+var_dump(paramsArray) )
    this_backend_home_url = paramsArray.backend_home_url;
    this_backend_per_page = paramsArray.backend_per_page;
    this_csrf_token = paramsArray.csrf_token;

    // alert( " backendTodoContainerPage this_csrf_token::"+var_dump(this_csrf_token) )
} // function backendTodoContainerPage(Params) {  constructor


backendTodoContainerPage.prototype.onBackendPageInit = function (page) {  // all vars/objects init
    backendInit()
    this.showTodoPage(1)

    // $('body').on('click', '.pagination a', function (e) {
    //     e.preventDefault();
    //     var url = $(this).attr('href');
    //     // alert( "++url::"+var_dump(url) )
    //     //page_to_load::1?=5
    //     var page_to_load = getSplitted(url, 'get-activity-log-rows/1?=', 1)
    //     // alert( "page_to_load::"+var_dump(page_to_load) )
    //     if (!checkInteger(page_to_load)) page_to_load = 1
    //     backendTodoContainerPage.showActivityLogRows(page_to_load)
    //     return false;
    // });

}



backendTodoContainerPage.prototype.showTodoPage = function (page) {
    var href = '/admin/show-todo-page';
    $.ajax(
        {
            type: "GET",
            dataType: "json",
            url: href,
            success: function (response) {
                $('#div_todo_container_page_content').html(response.html)

                $(".chosen_select_box").chosen({
                    disable_search_threshold: 10,
                    no_results_text: "Nothing found!",
                    allow_single_deselect: true,
                });


                $(document).on('click', '.todo-btn-add', function (e) {
                    e.preventDefault();
                    var todos_count = parseInt($("#todos_count").val())

                    var controlForm = $('.controls form:first'),
                        currentEntry = $(this).parents('.entry:first'),
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);

                    var todo_id_hidden = newEntry.find("input").eq(0)
                    todo_id_hidden.val('');
                    todo_id_hidden.attr('id', 'todo_id_' + todos_count);
                    todo_id_hidden.attr('name', 'todo_id_' + todos_count);      // OK

                    var modifiedHidden = todo_id_hidden.next('input');
                    modifiedHidden.val('1');
                    modifiedHidden.attr('id', 'todo_modified_' + todos_count);     // OK
                    modifiedHidden.attr('name', 'todo_modified_' + todos_count);

                    var todo_text_input = modifiedHidden.next("input").eq(0)
                    todo_text_input.val('');
                    todo_text_input.attr('id', 'todo_text_' + todos_count);
                    todo_text_input.attr('name', 'todo_text_' + todos_count);      // OK

                    var todo_select_priority = newEntry.find("select").eq(0)
                    todo_select_priority.val('');
                    todo_select_priority.attr('id', 'todo_priority_' + todos_count);
                    todo_select_priority.attr('name', 'todo_priority_' + todos_count);

                    var todo_select_completed = newEntry.find(".todo_editable_field_completed").eq(0)
                    todo_select_completed.val('0');
                    todo_select_completed.attr('id', 'todo_completed_' + todos_count);
                    todo_select_completed.attr('name', 'todo_completed_' + todos_count);


                    var todo_select_foruserid = newEntry.find(".todo_editable_field_foruserid").eq(0)
                    todo_select_foruserid.val('');
                    todo_select_foruserid.attr('id', 'todo_foruserid_' + todos_count);
                    todo_select_foruserid.attr('name', 'todo_foruserid_' + todos_count);
                    // $("#filter_accepted").val(this_filter_value);
                    // $('.chosen_filter_accepted').trigger("chosen:updated");
                    todo_select_foruserid.trigger("chosen:updated");



                    controlForm.find('.entry:not(:last) .todo-btn-add')
                        .removeClass('todo-btn-add').addClass('btn-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="fa fa-minus"></span>');

                    $("#todos_count").val(todos_count + 1)
                    $("#todos_count_label").html($("#todos_count").val())


                    $(".chosen_select_box").chosen({
                        disable_search_threshold: 10,
                        allow_single_deselect: true,
                        no_results_text: "Nothing found!",
                    });

                    // alert( ".todo-btn-add CLICKED!" )

                }).on('click', '.btn-remove', function (e) {
                    let v = $(this).parents('.entry:first')
                    console.log("v::")
                    console.log(v)


                    let removable_todo_field_id = $(this).parents('.entry:first').find(".todo_editable_field_id").eq(0)
                    console.log("removable_todo_field_id::")
                    console.log(removable_todo_field_id)
                    console.log(removable_todo_field_id[0].id)
                    console.log(removable_todo_field_id[0].value)
                    if (typeof removable_todo_field_id[0].value != undefined) {
                        backendTodoContainerPage.deleteTodo(removable_todo_field_id[0].value)
                    }
                    return;
                    console.log(typeof  removable_todo_field_id)
                    // console.log( removable_todo_field_id.getAttribute("id") )

                    alert("delete_todo_id::" + (delete_todo_id))
                    // debugger;
                    // this_todo_removed_ids.push(removable_todo_field_id);
                    // console.log("this_todo_removed_ids::")
                    // console.log( this_todo_removed_ids )

                    $(this).parents('.entry:first').remove();
                    $("#todos_count").val(parseInt($("#todos_count").val()) - 1)
                    e.preventDefault();
                    return false;
                });

            },
            error: function (error) {
                popupErrorMessage(error.responseJSON.message)
            }

        });

}


backendTodoContainerPage.prototype.deleteTodo = function (todo_id) {
    var href = this_backend_home_url + "/admin/delete-todo";
    $.ajax({
        type: "DELETE",
        dataType: "json",
        url: href,
        data: {"todo_id": todo_id, "_token": this_csrf_token},
        success: function (response) {
            popupAlert("Todo item waw deleted successfully !", 'success')
            backendTodoContainerPage.showTodoPage()
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

}

// backendTodoContainerPage.prototype.showTodoPage = function () {
//     var href = '/admin/show-todo-page';
//     $.ajax(
//         {
//             type: "GET",
//             dataType: "json",
//             url: href,
//             success: function (response) {
//                 $("#div_show_todo_page_modal").modal({
//                     "backdrop": "static",
//                     "keyboard": true,
//                     "show": true
//                 });
//                 $('#div_show_todo_page_content').html(response.html)
//
//                 $(".chosen_select_box").chosen({
//                     disable_search_threshold: 10,
//                     no_results_text: "Nothing found!",
//                     allow_single_deselect: true,
//                 });
//
//
//                 $(document).on('click', '.todo-btn-add', function (e) {
//                     e.preventDefault();
//                     var todos_count = parseInt($("#todos_count").val())
//
//                     var controlForm = $('.controls form:first'),
//                         currentEntry = $(this).parents('.entry:first'),
//                         newEntry = $(currentEntry.clone()).appendTo(controlForm);
//
//                     var todo_id_hidden = newEntry.find("input").eq(0)
//                     todo_id_hidden.val('');
//                     todo_id_hidden.attr('id', 'todo_id_' + todos_count);
//                     todo_id_hidden.attr('name', 'todo_id_' + todos_count);      // OK
//
//                     var modifiedHidden = todo_id_hidden.next('input');
//                     modifiedHidden.val('1');
//                     modifiedHidden.attr('id', 'todo_modified_' + todos_count);     // OK
//                     modifiedHidden.attr('name', 'todo_modified_' + todos_count);
//
//                     var todo_text_input = modifiedHidden.next("input").eq(0)
//                     todo_text_input.val('');
//                     todo_text_input.attr('id', 'todo_text_' + todos_count);
//                     todo_text_input.attr('name', 'todo_text_' + todos_count);      // OK
//
//                     var todo_select_priority = newEntry.find("select").eq(0)
//                     todo_select_priority.val('');
//                     todo_select_priority.attr('id', 'todo_priority_' + todos_count);
//                     todo_select_priority.attr('name', 'todo_priority_' + todos_count);
//
//                     var todo_select_completed = newEntry.find(".todo_editable_field_completed").eq(0)
//                     todo_select_completed.val('0');
//                     todo_select_completed.attr('id', 'todo_completed_' + todos_count);
//                     todo_select_completed.attr('name', 'todo_completed_' + todos_count);
//
//                     var todo_select_foruserid = newEntry.find(".todo_editable_field_foruserid").eq(0)
//                     todo_select_foruserid.val('');
//                     todo_select_foruserid.attr('id', 'todo_foruserid_' + todos_count);
//                     todo_select_foruserid.attr('name', 'todo_foruserid_' + todos_count);
//
//                     controlForm.find('.entry:not(:last) .todo-btn-add')
//                         .removeClass('todo-btn-add').addClass('btn-remove')
//                         .removeClass('btn-success').addClass('btn-danger')
//                         .html('<span class="fa fa-minus"></span>');
//
//                     $("#todos_count").val(todos_count + 1)
//                     $("#todos_count_label").html($("#todos_count").val())
//                 }).on('click', '.btn-remove', function (e) {
//                     let v = $(this).parents('.entry:first')
//                     console.log("v::")
//                     console.log(v)
//
//
//                     let removable_todo_field_id = $(this).parents('.entry:first').find(".todo_editable_field_id").eq(0)
//                     console.log("removable_todo_field_id::")
//                     console.log(removable_todo_field_id)
//                     console.log(removable_todo_field_id[0].id)
//                     console.log(removable_todo_field_id[0].value)
//                     if (typeof removable_todo_field_id[0].value != undefined) {
//                         backendTodoContainerPage.deleteTodo(removable_todo_field_id[0].value)
//                     }
//                     return;
//                     console.log(typeof  removable_todo_field_id)
//                     // console.log( removable_todo_field_id.getAttribute("id") )
//
//                     alert("delete_todo_id::" + (delete_todo_id))
//                     // debugger;
//                     // this_todo_removed_ids.push(removable_todo_field_id);
//                     // console.log("this_todo_removed_ids::")
//                     // console.log( this_todo_removed_ids )
//
//                     $(this).parents('.entry:first').remove();
//                     $("#todos_count").val(parseInt($("#todos_count").val()) - 1)
//                     e.preventDefault();
//                     return false;
//                 });
//
//             },
//             error: function (error) {
//                 popupErrorMessage(error.responseJSON.message)
//             }
//
//         });
//
// }


backendTodoContainerPage.prototype.saveTodoDialog = function () {
    let todos_count = $("#todos_count").val()
    let tempTodoList = Array.prototype.slice
    // .call( document.querySelectorAll('#todo_fields > .form-control') )
    // .call( document.querySelectorAll('input.todo_editable_field') )
        .call(document.querySelectorAll('.todo_editable_field'))
        // .call( document.querySelectorAll('input|select.form-control') )
        .map((todo, index) => {
            console.log("todo::")
            console.log(todo)
            console.log("index.id::")
            console.log(todo.id)
            console.log("index.value::")
            console.log(todo.value)
            console.log("index::")
            console.log(index)

            // if ( index % 5 != 0 ) return false;
            let todoItem = {
                // todo_id: todo.id, // todo_id_0
                todo_id: todo.id,
                // todo_modified: '',
                todo_text: todo.value,
                // todo_priority: '',
                // todo_completed: ''
            }; //Object initialiser
            return todoItem
            // Create Individual TODO object
        });

    console.log("tempTodoList::")
    console.log(tempTodoList)

    let todoList = []
    for (i = 0; i < tempTodoList.length; i += 6) {

        // alert (i);
        console.log("tempTodoList[i]::")
        console.log(tempTodoList[i])

        // debugger;
        let todoItem = {
            todo_index: i,
            todo_id: tempTodoList[i].todo_text,
            todo_modified: tempTodoList[i + 1].todo_text,
            todo_text: tempTodoList[i + 2].todo_text,
            todo_priority: tempTodoList[i + 3].todo_text,
            todo_completed: tempTodoList[i + 4].todo_text,
            todo_foruserid: tempTodoList[i + 5].todo_text
        }; //Object initialiser
        todoList.push(todoItem);
    }
    console.log("todoList::")
    console.log(todoList)
    // debugger
    let href = "/admin/save-todo-page";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: href,
        data: {"_token": this_csrf_token, "todoList": todoList},
        success: function (response) {
            popupAlert("Todo items were saved successfully !", 'success')
        },
        error: function (error) {
            popupErrorMessage(error.responseJSON.message)
        }
    });

}

backendTodoContainerPage.prototype.todoOnChange = function (ref) {
    if (typeof ref.id == "undefined") return;
    let index = getSplitted(ref.id, '_', 2);
    // alert( "index::"+var_dump(index) )
    $("#todo_modified_" + index).val(1);
}
