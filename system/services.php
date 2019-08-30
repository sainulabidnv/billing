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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "services.php")) {
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
                <h4>New Service Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="add_product">
                    <input type="hidden" name="act" value="add_product">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Service Name</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Service Name"
                                           class="form-control margin-bottom  required" name="product_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Service Code</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Service Code"
                                           class="form-control margin-bottom required" name="product_code">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Service Price</label>

                                <div class="col-sm-5">
                                    <div class="input-group margin-bottom">
                                        <span class="input-group-addon"><?php echo $siteConfig['curr'] ?></span>
                                        <input type="text" name="product_price" class="form-control required"
                                               placeholder="0.00" aria-describedby="sizing-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">TAX Rate(%)</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Only Numeric value 0.00"
                                           class="form-control margin-bottom required" name="product_tax">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5">
                                    <input type="submit" id="action_add_product" class="btn btn-success margin-bottom"
                                           value="Add Service" data-loading-text="Adding...">
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
        $("#action_add_product").click(function (e) {
            e.preventDefault();
            actionAddProduct();
        });
        function actionAddProduct() {

            var errorNum = farmCheck();
            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {

                $(".required").parent().removeClass("has-error");

                var $btn = $("#action_add_product").button("loading");

                $.ajax({

                    url: 'request/service.php',
                    type: 'POST',
                    data: $("#add_product").serialize(),
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
    $whereConditions = array('pid' => $id);
    $row = $db->select('services', null, $whereConditions)->results();

    if ($row) {

        $product_name = $row['product_name'];
        $product_code = $row['product_code'];
        $product_price = $row['product_price'];
        $product_tax = $row['tax'];


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
                <form method="post" id="update_product">
                    <input type="hidden" name="act" value="update_product">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Service Name</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Service Name"
                                           class="form-control margin-bottom  required" name="product_name"
                                           value="<?php echo $product_name; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Service Code</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Service Code"
                                           class="form-control margin-bottom required" name="product_code"
                                           value="<?php echo $product_code; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Service Price</label>

                                <div class="col-sm-5">
                                    <div class="input-group margin-bottom">
                                        <span class="input-group-addon"><?php echo $siteConfig['curr'] ?></span>
                                        <input type="text" name="product_price" class="form-control required"
                                               placeholder="0.00" aria-describedby="sizing-addon1"
                                               value="<?php echo $product_price; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">TAX Rate(%)</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Only Numeric value 0.00"
                                           class="form-control margin-bottom required" name="product_tax"
                                           value="<?php echo $product_tax; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5 margin-bottom">
                                    <input type="submit" id="action_update_product" class="btn btn-success float-right"
                                           value="Update product" data-loading-text="Updating...">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row">

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $(document).on('click', "#action_update_product", function (e) {
            e.preventDefault();
            updateService();
        });
        function updateService() {

            var $btn = $("#action_update_product").button("loading");

            jQuery.ajax({

                url: 'request/service.php',
                type: 'POST',
                data: $("#update_product").serialize(),
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
                <h4>Service List</h4>
            </div>
            <div class="panel-body form-group form-group-sm"><p><a href="index.php?rdp=services&op=add"
                                                                   class="btn btn-primary"><span
                                class="icon-plus"></span>Add New Service</a></p></div>
            <div class="panel-body form-group form-group-sm tbl">
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#produc').DataTable({
                            stateSave: true,
                            "processing": true,
                            "serverSide": true,
                            "ajax": "request/ajax.php?page=service"
                        });
                    });</script>
                <div class="table-responsive">
                    <table id="produc" class="table cell-border" cellspacing="0">
                        <thead>
                        <tr>

                            <th>Service</th>
                            <th>Price <?php echo $siteConfig['curr']; ?></th>


                            <th>Settings</th>

                        </tr>
                        </thead>
                    </table>
                </div>


            </div>
        </div>
    </div>
    <div>
        <script type="text/javascript">
            $(document).on('click', ".delete-product", function (e) {
                e.preventDefault();

                var productId = 'act=delete_product&delete=' + $(this).attr('data-product-id');
                var product = $(this);

                $('#confirm').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                    deleteService(productId);
                    $(product).closest('tr').remove();
                });
            });
            function deleteService(productId) {

                jQuery.ajax({

                    url: 'request/service.php',
                    type: 'POST',
                    data: productId,
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
                        <h4 class="modal-title">Delete product</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this service</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
<?php }