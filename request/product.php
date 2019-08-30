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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "product.php")) {
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
if (isset($_GET['cat'])) {
    $cat = intval($_GET['cat']);
} else {
    $cat = 0;
}
switch ($op) {
    case "add":
        add();
        break;
    case "edit":
        editp($id);
        break;
    case "catadd":
        catadd();
        break;
    case "catedit":
        catedit($id);
        break;
    case "cat":
        catlist();
        break;
    default:
        plist($cat);
        break;
}

function add()
{
    global $db, $siteConfig;
    ?>


    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>New Product Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="add_product">
                    <input type="hidden" name="act" value="add_product">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Name</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Product Name"
                                           class="form-control margin-bottom  required" name="product_name">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Category</label>

                                <div class="col-sm-8">
                                    <select name="product_cat" class="form-control margin-bottom">
                                        <?php $query = "SELECT * FROM product_cat";
                                        $result = $db->pdoQuery($query)->results();
                                        foreach ($result as $row) {
                                            $cid = $row['id'];
                                            $title = $row['title'];
                                            echo "<option value='$cid'>$title</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Code</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Product Code"
                                           class="form-control margin-bottom required" name="product_code">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Retail Price</label>

                                <div class="col-sm-8">
                                    <div class="input-group margin-bottom">
                                        <span class="input-group-addon"><?php echo $siteConfig['curr'] ?></span>
                                        <input type="number" name="product_price" class="form-control required"
                                               placeholder="0.00" aria-describedby="sizing-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Wholesale Price</label>

                                <div class="col-sm-8">
                                    <div class="input-group margin-bottom">
                                        <span class="input-group-addon"><?php echo $siteConfig['curr'] ?></span>
                                        <input type="number" name="fproduct_price" class="form-control required"
                                               placeholder="0.00" aria-describedby="sizing-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">SGST(%)</label>

                                <div class="col-sm-8">
                                    <input type="number" placeholder="only numeric value 0.00"
                                           class="form-control margin-bottom required" name="product_tax">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">CGST(%)</label>

                                <div class="col-sm-8">
                                    <input type="number" placeholder="only numeric value 0.00"
                                           class="form-control margin-bottom required" name="product_tax2">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Stock Units</label>

                                <div class="col-sm-8">
                                    <input type="number" placeholder="Stock Items"
                                           class="form-control margin-bottom required" name="product_qty">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom"></label>

                                <div class="col-sm-8">
                                    <input type="submit" id="action_add_product" class="btn btn-success margin-bottom"
                                           value="Add product" data-loading-text="Adding...">
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

                    url: 'request/e_control.php',
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
    $row = $db->select('products', null, $whereConditions)->results();

    if ($row) {
        $pcat = $row['pcat'];
        $product_name = $row['product_name'];
        $product_code = $row['product_code'];
        $product_price = $row['product_price'];
        $fproduct_price = $row['fproduct_price'];
        $product_qty = $row['qty'];
        $product_tax = $row['tax'];
		$product_tax2 = $row['tax2'];

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

                                <label class="col-sm-4 control-label margin-bottom">Product Name</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Product Name"
                                           class="form-control margin-bottom  required" name="product_name"
                                           value="<?php echo $product_name; ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Category</label>

                                <div class="col-sm-8">
                                    <select name="product_cat" class="form-control margin-bottom">
                                        <?php
                                        $whereConditions = array('id' => $pcat);
                                        $row = $db->select('product_cat', null, $whereConditions)->results();
                                        $title = $row['title'];
                                        echo "<option value='$pcat'>[Selected] $title</option>";

                                        $query = "SELECT * FROM product_cat";
                                        $result = $db->pdoQuery($query)->results();
                                        foreach ($result as $row) {
                                            $cid = $row['id'];
                                            $title = $row['title'];
                                            echo "<option value='$cid'>$title</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Code</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Product Code"
                                           class="form-control margin-bottom required" name="product_code"
                                           value="<?php echo $product_code; ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Retail Price</label>

                                <div class="col-sm-8">
                                    <div class="input-group margin-bottom">
                                        <span class="input-group-addon"><?php echo $siteConfig['curr'] ?></span>
                                        <input type="text" name="product_price" class="form-control required"
                                               placeholder="0.00" aria-describedby="sizing-addon1"
                                               value="<?php echo $product_price; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Product Wholesale Price</label>

                                <div class="col-sm-8">
                                    <div class="input-group margin-bottom">
                                        <span class="input-group-addon"><?php echo $siteConfig['curr'] ?></span>
                                        <input type="text" name="fproduct_price" class="form-control required"
                                               placeholder="0.00" aria-describedby="sizing-addon1"
                                               value="<?php echo $fproduct_price; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">SGST(%)</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="only numeric value 0.00"
                                           class="form-control margin-bottom required" name="product_tax"
                                           value="<?php echo $product_tax; ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">CGST(%)</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="only numeric value 0.00"
                                           class="form-control margin-bottom required" name="product_tax2"
                                           value="<?php echo $product_tax2; ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Stock Units</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Stock Items"
                                           class="form-control margin-bottom required" name="product_qty"
                                           value="<?php echo $product_qty; ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom"></label>

                                <div class="col-sm-8 margin-bottom">
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
            updateProduct();
        });
        function updateProduct() {

            var $btn = $("#action_update_product").button("loading");

            jQuery.ajax({

                url: 'request/e_control.php',
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
function plist($cat)
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
                <h4>Product List</h4>
            </div>
            <div class="panel-body tbl">
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#produc').DataTable({
                            stateSave: true,
                            "processing": true,
                            "serverSide": true,
                            <?php if ($cat > 0) {
                            echo '"ajax": "request/listinex.php?page=product&cat=' . $cat . '"';
                        } else {
                            echo '"ajax": "request/ajax.php?page=product"';
                        }
                            ?>
                        });
                    });</script>
                <table id="produc" class="table-responsive cell-border" cellspacing="0">
                    <thead>
                    <tr>

                        <th>Product</th>
                        <th>Sales Price <?php echo $siteConfig['curr']; ?></th>
                        <th>Wholesale Price <?php echo $siteConfig['curr']; ?></th>
                        <th>Stock Status</th>
                        <th>Barcode</th>

                        <th>Settings</th>

                    </tr>
                    </thead>
                </table>


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
                deleteProduct(productId);
                $(product).closest('tr').remove();
            });
        });
        function deleteProduct(productId) {

            jQuery.ajax({

                url: 'request/e_control.php',
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
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php }


#####################
function catadd()
{
    global $db, $siteConfig;
    ?>


    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>New Category Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="addcat_product">
                    <input type="hidden" name="act" value="addcat_product">

                    <div class="row">

                        <div class="col-xs-12">
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Name</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="New Product Category Name"
                                           class="form-control margin-bottom  required" name="catname">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Description</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Description"
                                           class="form-control margin-bottom required" name="catdes">
                                </div>
                            </div>


                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom"></label>

                                <div class="col-sm-8">
                                    <input type="submit" id="action_addcat_product"
                                           class="btn btn-success margin-bottom"
                                           value="Add product category" data-loading-text="Adding...">
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
        $("#action_addcat_product").click(function (e) {
            e.preventDefault();
            actionAddCatProduct();
        });
        function actionAddCatProduct() {

            var errorNum = farmCheck();
            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {

                $(".required").parent().removeClass("has-error");

                var $btn = $("#action_addcat_product").button("loading");

                $.ajax({

                    url: 'request/c_control.php',
                    type: 'POST',
                    data: $("#addcat_product").serialize(),
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



function catlist()
{
    global $db, $siteConfig;
    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#prclist').DataTable({
                stateSave: true
            });
        });
    </script>

    <div class="row">

    <div class="col-xs-12">

        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Category List</h4><a href="index.php?rdp=product&op=catadd"
                                         class="btn btn-primary"><span
                            class="icon-plus"></span>Add New Product Category</a>&nbsp; &nbsp; &nbsp;<a
                        href="index.php?rdp=product"
                        class="btn btn-success"><span
                            class="icon-list"></span>All Products List</a>
            </div>
            <div class="panel-body form-group form-group-sm tbl">

                <table id="prclist" class="table-responsive">
                    <thead>
                    <tr>

                        <th>Name</th>
                        <th>Products</th>
                        <th>Settings</th>

                    </tr>
                    </thead>
                    <?php
                    $query = "SELECT * FROM product_cat";
                    $result = $db->pdoQuery($query)->results();
                    foreach ($result as $row) {
                        $cid = $row['id'];
                        $title = $row['title'];
                        $irs = $db->count('products', "pcat=$cid");

                        echo "<tr><td>$title</td><td><a href='index.php?rdp=product&cat=$cid' class='btn btn-primary btn-xs'><span class='icon-info'></span> View  ($irs) Products</a></td><td><a href='index.php?rdp=product&op=catedit&id=$cid' class='btn btn-primary btn-xs'><span class='icon-pencil'></span>Edit</a> <a data-product-id='$cid' class='btn btn-danger btn-xs delete-catproduct'><span class='icon-bin'></span></a></td></tr>";
                    }
                    ?>
                </table>


            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $(document).on('click', ".delete-catproduct", function (e) {
            e.preventDefault();

            var productId = 'act=delete_catproduct&delete=' + $(this).attr('data-product-id');
            var product = $(this);

            $('#confirm').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                deletecatProduct(productId);
                $(product).closest('tr').remove();
            });
        });
        function deletecatProduct(productId) {

            jQuery.ajax({

                url: 'request/c_control.php',
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
                    <h4 class="modal-title">Delete product categorry</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product category ? All products related to this category
                        will also deleted ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php }

function catedit($id)
{
    global $db, $siteConfig;
    $whereConditions = array('id' => $id);
    $row = $db->select('product_cat', null, $whereConditions)->results();

    if ($row) {

        $product_name = $row['title'];
        $product_code = $row['extra'];


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
                <form method="post" id="catupdate_product">
                    <input type="hidden" name="act" value="catupdate_product">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <div class="row">
                        <div class="col-xs-12">

                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Category</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Category Name"
                                           class="form-control margin-bottom  required" name="catname"
                                           value="<?php echo $product_name; ?>">
                                </div>
                            </div>

                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Description</label>

                                <div class="col-sm-8">
                                    <input type="text" placeholder="Description"
                                           class="form-control margin-bottom  required" name="catdes"
                                           value="<?php echo $product_code; ?>">
                                </div>
                            </div>


                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom"></label>

                                <div class="col-sm-8 margin-bottom">
                                    <input type="submit" id="action_catupdate_product"
                                           class="btn btn-success float-right"
                                           value="Update product category" data-loading-text="Updating...">
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
            $(document).on('click', "#action_catupdate_product", function (e) {
                e.preventDefault();
                catupdateProduct();
            });
            function catupdateProduct() {

                var $btn = $("#action_catupdate_product").button("loading");

                jQuery.ajax({

                    url: 'request/c_control.php',
                    type: 'POST',
                    data: $("#catupdate_product").serialize(),
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