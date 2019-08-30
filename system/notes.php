<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@skyresoft.com
 *  Website: https://www.skyresoft.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * If you Purchased from Codecanyon, Please read the full License from
 *  * here- http://skyresoft.com/licenses/standard/
 * ***********************************************************************
 */
if (stristr(htmlentities($_SERVER['PHP_SELF']), "notes.php")) {
    die("Internal Server Error!");
}
if ($user->group_id > 2) {
    die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
}
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    $id = 0;
}

switch ($op) {
    case "add":
        add();
        break;
    case "edit":
        editp($id);
        break;
    default:
        plist();
        break;
}

function add()
{
    global $siteConfig;
    ?>


    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>New Note Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="add_note">
                    <input type="hidden" name="act" value="add_note">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Note Title</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Note Title"
                                           class="form-control margin-bottom  required" name="title">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Note</label>

                                <div class="col-sm-8">
                                    <textarea type="text" placeholder="Note"
                                              class="form-control margin-bottom required" name="note"></textarea>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Date</label>

                                <div class="col-sm-8">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required margin-bottom" name="ndate"
                                               value="<?php

                                               echo $siteConfig['date'];

                                               ?>" data-date-format="<?php

                                        echo $siteConfig['dformat2'];

                                        ?>"/>
                                        <span class="input-group-addon margin-bottom">
    <span class="icon-calendar margin-bottom"></span>
    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom"></label>

                                <div class="col-sm-8">
                                    <input type="submit" id="action_add_note" class="btn btn-success margin-bottom"
                                           value="Add note" data-loading-text="Adding...">
                                </div>
                            </div>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $("#action_add_note").click(function (e) {
            e.preventDefault();
            actionAddNote();
        });
        function actionAddNote() {

            var errorNum = farmCheck();
            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {

                $(".required").parent().removeClass("has-error");

                var $btn = $("#action_add_note").button("loading");

                $.ajax({

                    url: 'request/e_control.php',
                    type: 'POST',
                    data: $("#add_note").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $btn.button("reset");
                    },
                    error: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $btn.button("reset");
                    }

                });
            }

        }
    </script><?php }

function editp($id){
    global $db, $siteConfig;
    $whereConditions = array('id' => $id);
    $row = $db->select('notes', null, $whereConditions)->results();

    if ($row) {

        $product_name = $row['title'];
        $product_code = $row['note'];
        $product_price = $row['date'];


    } else {
        die();
    }
    ?>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?php echo $product_name; ?></h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="update_note">
                    <input type="hidden" name="act" value="update_note">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Note Title</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Title"
                                           class="form-control margin-bottom  required" name="title"
                                           value="<?php echo $product_name; ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Note</label>

                                <div class="col-sm-8">
                                    <textarea type="text" placeholder="Note"
                                              class="form-control margin-bottom required"
                                              name="note"><?php echo $product_code; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Date</label>

                                <div class="col-sm-8">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required margin-bottom" name="ndate"
                                               value="<?php

                                               echo $siteConfig['date'];

                                               ?>" data-date-format="<?php

                                        echo $siteConfig['dformat2'];

                                        ?>"/>
                                        <span class="input-group-addon margin-bottom">
    <span class="icon-calendar margin-bottom"></span>
    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom"></label>

                                <div class="col-sm-8 margin-bottom">
                                    <input type="submit" id="action_update_note" class="btn btn-success float-right"
                                           value="Update note" data-loading-text="Updating...">
                                </div>
                            </div>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $(document).on('click', "#action_update_note", function (e) {
            e.preventDefault();
            updateNote();
        });
        function updateNote() {

            var $btn = $("#action_update_note").button("loading");

            jQuery.ajax({

                url: 'request/e_control.php',
                type: 'POST',
                data: $("#update_note").serialize(),
                dataType: 'json',
                success: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $btn.button("reset");
                },
                error: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $btn.button("reset");
                }
            });

        }
    </script>
<?php }
function plist()
{
    global $siteConfig;
    ?>


    <div class="row">

        <div class="col-xs-12">

            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Notes</h4>
                    <a href="index.php?rdp=notes&op=add"
                       class="btn btn-primary"><span
                                class="icon-plus"></span>Add New Note</a>
                </div>
                <div class="panel-body form-group form-group-sm tbl">
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $('#produc').DataTable({
                                stateSave: true,
                                "processing": true,
                                "serverSide": true,
                                "ajax": "request/ajax.php?page=notes"
                            });
                        });</script>
                    <table id="produc" class="table-responsive cell-border" cellspacing="0">
                        <thead>
                        <tr>

                            <th>ID</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Settings</th>

                        </tr>
                        </thead>
                    </table>


                </div>
            </div>
        </div>
        <div>
            <script type="text/javascript">
                $(document).on('click', ".delete-note", function (e) {
                    e.preventDefault();

                    var noteId = 'act=delete_note&delete=' + $(this).attr('data-note-id');
                    var note = $(this);

                    $('#confirm').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                        deletenote(noteId);
                        $(note).closest('tr').remove();
                    });
                });
                function deletenote(noteId) {

                    jQuery.ajax({

                        url: 'request/e_control.php',
                        type: 'POST',
                        data: noteId,
                        dataType: 'json',
                        success: function (data) {
                            $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                            $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                            $("html, body").animate({scrollTop: $('body').offset().top}, 1000);

                        },
                        error: function (data) {
                            $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                            $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                            $("html, body").animate({scrollTop: $('body').offset().top}, 1000);

                        }
                    });

                }
            </script>
            <div id="confirm" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Delete note</h4>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this note?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete
                            </button>
                            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
<?php }