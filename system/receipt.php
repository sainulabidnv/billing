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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "receipt.php")) {
    die("Internal Server Error!");
}
if (($user->group_id) > 2) {
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
    case "create":
        create();
        break;
    case "edit":
        editr($id);
        break;
    default:
        rlist();
        break;
}
function create()
{
    global $db, $siteConfig, $user;
    function getSerial()
    {
        global $db;
        $row = $db->select('receipts', null, null, 'ORDER BY tid DESC')->results();
        if ($row) {
            echo $row['tid'] + 1;
        }
    }

    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#saman_list').on('click', ".delete-row", function (e) {
                e.preventDefault();
                $(this).closest('article').remove();

                antimYog();
            });
        });
    </script>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <form method="post" id="create_receipt" class="fmargin">
        <input type="hidden" name="act" value="create_receipt">

        <div class="row ">

            <div class="panel panel-primary ">
                <div class="panel-body chead">
                    <div class="col-md-6">
                        <h3>New Receipt</h3></div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <small>Receipt Date</small>
                            <div class="input-group date" id="tsn_date">
                                <input type="text" class="form-control required" name="tsn_date" value="<?php

                                echo $siteConfig['date'];

                                ?>" data-date-format="<?php

                                echo $siteConfig['dformat2'];

                                ?>"/>
                                <span class="input-group-addon">
				                    <span class="icon-calendar"></span>
				                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <small>Receipt Due Date</small>
                            <div class="input-group date" id="tsn_due">
                                <input type="text" class="form-control required" name="tsn_due" value="<?php

                                echo $siteConfig['date'];

                                ?>" data-date-format="<?php

                                echo $siteConfig['dformat2'];

                                ?>"/>
                                <span class="input-group-addon">
				                    <span class="icon-calendar"></span>
				                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 float-right">
                        <small>Receipt Number</small>
                        <div class="input-group ">
							<span class="input-group-addon">#<?php

                                echo $siteConfig['pref']

                                ?></span>
                            <input type="number" name="invoice_id" id="invoice_id" class="form-control required"
                                   placeholder="Receipt Number" aria-describedby="sizing-addon1" value="<?php

                            getSerial();

                            ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="row bg-light-blue color-palette margin-t padding-f">


        <div class="ol-xs-12 col-md-5 col-lg-4 mol-4"><a href="#" class="btn btn-warning btn-xs add-row"><span
                        class="icon-plus"></span><strong>Add empty </strong></a> PRODUCTS ROW
        </div>
        <div class="col-xs-12 col-md-6 col-lg-1 mol-1">QTY</div>
        <div class="col-xs-12 col-md-6 col-lg-2 mol-2">UNIT PRICE</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">SGST</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">CGST</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-2" style="border-right:0">SUBTOTAL</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">Tax</div>

    </div>

        <div class="row panel panel-default" id="saman_list">
        <br>
        <article class="mr6 margin-b">
            <div class="col-xs-12 col-md-5 col-lg-4 mol-4">
                <div class="input-group">
    <span class="input-group-addon">
    <a href="#" class="btn btn-success btn-xs product-select"><span class="icon-database"
                                                                    title="Select Product From List"></span>List</a></span><input
                            type="text" class="form-control item-input bill_saman hbh required" id="bill_saman[]"
                            name="bill_saman[]" placeholder="Enter item title and / or description">
                    <input
                            type="hidden" class="bill_pid" id="bill_pid[]"
                            name="bill_pid[]" value="0">
                    <span
                            class="input-group-addon">
    <a href="#" class="btn btn-danger btn-xs delete-row" title="Delete Product row"><span
                class="icon-cancel-circle"></span></a></span></div>
            </div>
            <div class="col-xs-12 col-md-3 col-lg-1 mol-1">
                <input type="number" class="form-control saman_qty jod required" id="saman_qty[]" name="saman_qty[]"
                       value="">
            </div>
            <div class="col-xs-12 col-md-6 col-lg-2 mol-2">
                <div class="input-group">
    <span class="input-group-addon currenty"><?php

        echo $siteConfig['curr'];

        ?></span>
                    <input type="number" class="form-control jod billsaman_price required" name="billsaman_price[]"
                           placeholder="0.00">
                </div>
            </div>

            <div class="col-xs-12 col-md-3 col-lg-1 mol-1">

                <input type="number" class="form-control pdtax jod" name="bill_saman_tax[]" placeholder="TAX"  value="<?php  echo $siteConfig['vatrate']  ?>">
                <input type="hidden" class="form-control jod" name="saman-tax[]" placeholder="TAX" value="">


            </div>
            <div class="col-xs-12 col-md-3 col-lg-1 mol-1">

                <input type="number" class="form-control pdtax2 jod" name="bill_saman_tax2[]" placeholder="TAX"  value="<?php  echo $siteConfig['vatrate2']  ?>">
                <input type="hidden" class="form-control jod" name="saman-tax2[]" placeholder="TAX" value="">


            </div>
            <div class="col-xs-12 col-md-6 col-lg-2 mol-2">
                <div class="input-group">
    <span class="input-group-addon"><?php

        echo $siteConfig['curr'];

        ?></span>
                    <input type="number" class="form-control jod-sub" name="bill_saman_sub[]" id="bill_saman_sub[]"
                           value="0.00" aria-describedby="sizing-addon1" disabled>
                    <input type="hidden" class="ttInput" name="total[]" id="total-0" value="0">
                </div>
                <div class="clear">

                </div>
            </div>
            <div class="col-xs-12 col-md-3 col-lg-1 mol-1"> <button name="gen" class="taxgen"> Tax Gen</button></div>
            <br><br></article>

    </div>

        <div class="row margin-t ">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading panel-primary"><span class="icon-user-tie"></span>
                        Vendor Information


                    </div>
                    <div class="panel-body form-group form-group-sm" id="myc">

                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_name"
                                   id="grahak_name" placeholder="Vendor name" tabindex="1">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_adrs1"
                                   id="grahak_adrs1" placeholder="Address" tabindex="3">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_adrs2"
                                   id="grahak_adrs2" placeholder="City, Country, Postal Code" tabindex="5">
                        </div>
                        <input type="hidden" name="grahak_id" id="grahak_id" value="0">


                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_phone"
                                   id="grahak_phone" placeholder="Phone number" tabindex="8">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom" name="grahak_email" id="grahak_email"
                                   placeholder="Email" tabindex="8">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom" name="grahak_tax" id="grahak_tax"
                                   placeholder="TAX ID" tabindex="8">
                        </div>
                        <div class="form-group no-margin-bottom">
                            <a href="#" class="grahk-select btn btn-success">Select existing vender</a>&nbsp; <input
                                    type="button" id="clear-form" class="btn btn-danger dropdown-toggle" value="Clear"/>&nbsp;
                            &nbsp; <span class="crepeat"><input type="checkbox" name="crepeat" value="yes"> Add to registered vendor</span>
                        </div>
                    </div>

                </div>


            </div>
            <div class="col-md-6">

                <div class="panel panel-primary">
                    <div class="panel-heading panel-summary"><span class="icon-cart"></span>
                        Summary


                    </div>
                    <div id="bill_totl" class=" panel panel-default padding-b">
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Sub Total:</strong>
                            </div>
                            <div class="col-xs-4 text-right">
                                <?php

                                echo $siteConfig['curr'];

                                ?><span class="bill-sub-yog">0.00</span>
                                <input type="hidden" name="bill_upyog" id="bill_upyog">
                            </div>
                        </div>
                        <div class="row margin-bottom padding">
                        <div class="col-xs-8 text-right">
                            <strong>SGST</strong>
                        </div>
                        <div class="col-xs-4 text-right">
                            <?php echo $siteConfig['curr']; ?><span class="bill-ptax" data-tax-method="<?php  echo $siteConfig['vinc']  ?>" data-enable-tax="<?php echo $siteConfig['vatst'] ?>">0.00</span>
                            <input type="hidden" name="bill_ptax" id="bill_ptax">
                        </div>
                    </div>
                    
                    <div class="row margin-bottom padding">
                        <div class="col-xs-8 text-right">
                            <strong>CGST</strong>
                        </div>
                        <div class="col-xs-4 text-right">
                            <?php echo $siteConfig['curr']; ?><span class="bill-ptax2" data-tax-method="<?php  echo $siteConfig['vinc']  ?>" data-enable-tax="<?php echo $siteConfig['vatst'] ?>">0.00</span>
                            <input type="hidden" name="bill_ptax2" id="bill_ptax2">
                        </div>
                    </div>
                    <div class="row margin-bottom padding">  Remove TAX/VAT <input type="checkbox" class="remove_tax"> </div>
                   
                    


                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Discount:</strong>
                            </div>
                            <input type="hidden" class="djod vvt" value="0">

                            <div class="col-xs-4 text-right">
                                <div class="input-group">
                                    <input type="number" class="form-control jod vvt" name="invoice_vvt"
                                           placeholder="Discount" value="0"><select class="jod vvtype" name="jod vvtype"> <option value="0">%</option> <option selected="selected" value="1">Flat</option> </select>
                                </div>
                            </div>
                        </div>
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Total Discount:</strong>
                            </div>
                            <div class="col-xs-4 text-right">

                                <?php

                                echo $siteConfig['curr'];

                                ?><span class="bill-fdisc" data-enable-tax="1" data-tax-method="0">0.00</span>
                                <input type="hidden" name="bill_fdisc" id="bill_fdisc">
                            </div>
                        </div>
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Shipping</strong>
                            </div>
                            <div class="col-xs-4 text-right">

                                <div class="input-group">
    <span class="input-group-addon"><?php

        echo $siteConfig['curr'];

        ?></span>
                                    <input type="number" class="form-control jod deliv" name="saman_deliv"
                                           placeholder="0.00" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Total:</strong>
                            </div>
                            <div class="col-xs-4 text-right bttl">
                                <?php

                                echo $siteConfig['curr'];

                                ?><span class="bill-yog">0.00</span>
                                <input type="hidden" name="bill_yog" id="bill_yog">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 text-right "><br>
                                <strong>Status:</strong>
                            </div>
                            <div class="col-xs-4 text-right margin-t">
                                <select name="invoice_status" id="invoice_status" class="form-control">
                                    <!--<option value="paid" >Paid</option>-->
                                    <option value="due" selected>Due</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row  panel panel-default padding-f">
            <div class="col-md-6">
                <textarea class="form-control" name="invoice_notes" placeholder="Please enter any order notes here."
                          rows="3"></textarea>
            </div>
            <div class="col-md-6 btn-group"><span class="cstock"><input type="checkbox" name="cstock" value="yes"
                                                                        checked> Auto update above items quantity and stock in products list. Only if product is selected from product list.</span>
                <input type="submit" id="action_create_receipt" class="btn btn-success float-right"
                       value="Create Receipt" data-loading-text="Creating...">
            </div>

        </div>
    </form>
    <small class="float-right"><strong>Creator Employee ID <em><?php

                echo $user->username

                ?></em></strong></small>


    <script type="text/javascript">
        $("#action_create_receipt").click(function (e) {
            e.preventDefault();
            actionCreateReceipt();
        });
        function actionCreateReceipt() {

            var errorNum = farmCheck();

            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {

                var $btn = $("#action_create_receipt").button("loading");

                $(".required").parent().removeClass("has-error");
                $("#create_receipt").find(':input:disabled').removeAttr('disabled');

                $.ajax({

                    url: 'request/crt.php',
                    type: 'POST',
                    data: $("#create_receipt").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $("#create_receipt").remove();
                        button.button('reset');
                    },
                    error: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        button.button('reset');
                    }

                });
            }

        }
    </script>

    <?php

}

function editr($id)
{

    global $db, $user, $siteConfig;
    if ($user->group_id > 2) {
        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
    }
    $getID = $_GET['id'];


    $query = "SELECT * FROM receipts WHERE tid = '$getID'";

    $result = $db->pdoQuery($query)->results();

    foreach ($result as $row) {

        $bill_number = $row['tid'];
        $bill_date = dateformat($row['tsn_date']);
        $bill_due_date = dateformat($row['tsn_due']);
        $bill_upyog = $row['subtotal'];
        $bill_shipping = $row['shipping'];
        $bill_disc = $row['discount'];
        $bill_discr = $row['discountr'];
        $bill_tax = $row['tax'];
		$bill_tax2 = $row['tax2'];
        $bill_yog = $row['total'];
        $bill_notes = $row['notes'];
        $bill_status = $row['status'];
        $cidd = $row['csd'];
        $eid = $row['eid'];
        if ($cidd > 0) {

            $whereConditions = array('id' => $cidd);
            $row1 = $db->select('reg_vendors', null, $whereConditions)->results();


            $grahak_name = $row1['name'];
            $grahak_adrs1 = $row1['address1'];
            $grahak_adrs2 = $row1['address2'];
            $grahak_phone = $row1['phone'];
            $grahak_email = $row1['email'];
            $grahak_tax = $row1['taxid'];

        } else {

            $whereConditions = array('tid' => $getID);
            $row1 = $db->select('vendors', null, $whereConditions)->results();


            $grahak_name = $row1['name'];
            $grahak_adrs1 = $row1['address1'];
            $grahak_adrs2 = $row1['address2'];
            $grahak_phone = $row1['phone'];
            $grahak_email = $row1['email'];
            $grahak_tax = $row1['taxid'];


        }
    }


    ?>

    <script type="text/javascript">
        $(document).ready(function () {

            $('#saman_list').on('click', ".delete-row", function (e) {
                e.preventDefault();
                var idd = $(this).closest('article').find('.bill_pid').val();

                if ($(this).closest('article').find('.psaman_qty').val() > 0) {
                    var pqty = $(this).closest('article').find('.psaman_qty').val();
                    pqty = idd + '-' + pqty;
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'restock',
                        name: 'restock[]',
                        value: pqty
                    }).appendTo('form');

                }

                $(this).closest('article').remove();

                antimYog();
            });

        });
    </script>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <form method="post" id="edit_receipt" class="fmargin">
        <input type="hidden" name="act" value="edit_receipt">
        <input type="hidden" name="update_id" value="<?php

        echo $getID;

        ?>">


        <div class="row ">

            <div class="panel panel-primary">
                <div class="panel-body chead">
                    <div class="col-md-6">
                        <h3>Edit Receipt (<?php

                            echo $getID;

                            ?>)</h3></div>


                    <div class="col-md-2">
                        <div class="form-group ">
                            <small>Receipt Date</small>
                            <div class="input-group date" id="tsn_date">
                                <input type="text" class="form-control required" name="tsn_date" value="<?php

                                echo $bill_date;

                                ?>" data-date-format="<?php

                                echo $siteConfig['dformat2'];

                                ?>"/>
                                <span class="input-group-addon">
				                    <span class="icon-calendar"></span>
				                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <small>Receipt Due Date</small>
                            <div class="input-group date" id="tsn_due">
                                <input type="text" class="form-control required" name="tsn_due" value="<?php

                                echo $bill_due_date;

                                ?>" data-date-format="<?php

                                echo $siteConfig['dformat2'];

                                ?>"/>
                                <span class="input-group-addon">
				                    <span class="icon-calendar"></span>
				                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 float-right">
                        <small>Receipt Number</small>
                        <div class="input-group ">
							<span class="input-group-addon">#<?php

                                echo $siteConfig['pref']

                                ?></span>
                            <input type="text" name="invoice_id" id="invoice_id" class="form-control required"
                                   placeholder="Invoice Number" aria-describedby="sizing-addon1" value="<?php

                            echo $getID;

                            ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row bg-light-blue color-palette margin-t padding-f">


        <div class="ol-xs-12 col-md-5 col-lg-4 mol-4"><a href="#" class="btn btn-warning btn-xs add-row"><span
                        class="icon-plus"></span><strong>Add empty </strong></a> PRODUCTS ROW
        </div>
        <div class="col-xs-12 col-md-6 col-lg-1 mol-1">QTY</div>
        <div class="col-xs-12 col-md-6 col-lg-2 mol-2">UNIT PRICE</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">SGST</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">CGST</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-2" style="border-right:0">SUBTOTAL</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">Tax</div>

    </div>

        <div class="row panel panel-default" id="saman_list">
            <br>
            <?php


            $query2 = "SELECT * FROM receipts_items WHERE tid = '$getID'";
            $result2 = $db->pdoQuery($query2)->results();
            foreach ($result2 as $rows) {
                $item_product = $rows['product'];
                $item_qty = $rows['qty'];
                $item_price = $rows['price'];
                $item_discount = $rows['discount'];
                $item_subtotal = $rows['subtotal'];
                $item_taxr = $rows['trate'];
				$item_taxr2 = $rows['trate2'];
                $item_pid = $rows['pid'];

                ?>
                <article class="mr6 margin-b">
                <div class="col-xs-12 col-md-5 col-lg-4 mol-4">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <button href="#" class="btn btn-danger btn-xs" disabled><span class="icon-database"
                                                                                          title="Select Product From List"></span>List
                            </button>
                        </div>
                        <input type="text" class="form-control item-input bill_saman hbh required" id="bill_saman[]"
                               name="bill_saman[]" placeholder="Enter item title and / or description" value="<?php

                        echo $item_product;

                        ?>">
                        <input
                                type="hidden" class="bill_pid" id="bill_pid[]"
                                name="bill_pid[]" value="<?php

                        echo $item_pid;

                        ?>">

                        <div class="input-group-addon">
                            <a href="#" class="btn btn-danger btn-xs delete-row" title="Delete Product row"><span
                                        class="icon-cancel-circle"></span></a></div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-1 mol-1">
                    <input type="number" class="form-control saman_qty jod vrequired" id="saman_qty[]"
                           name="saman_qty[]"
                           value="<?php

                           echo $item_qty;

                           ?>"><input type="hidden" class="psaman_qty" name="psaman_qty[]" value="<?php

                    echo $item_qty;

                    ?>">
                </div>
                <div class="col-xs-12 col-md-6 col-lg-2 mol-2">
                    <div class="input-group">
                        <div class="input-group-addon currenty"><?php

                            echo $siteConfig['curr'];

                            ?></div>
                        <input type="number" class="form-control jod billsaman_price required" name="billsaman_price[]"
                               placeholder="0.00" value="<?php

                        echo $item_price;

                        ?>">
                        
                    </div>
                </div>

                <div class="col-xs-12 col-md-3 col-lg-1 mol-1">

                    <input type="number" class="form-control jod" name="bill_saman_tax[]" placeholder="<?php

                    echo $item_taxr;

                    ?>" value="<?php

                    echo $item_taxr;

                    ?>">
                    <input type="hidden" class="form-control jod" name="saman-tax[]" placeholder="SGST" value="<?php

                    echo $item_discount;

                    ?>">

                </div>
                
                <div class="col-xs-12 col-md-3 col-lg-1 mol-1">

                    <input type="number" class="form-control jod" name="bill_saman_tax2[]" placeholder="<?php

                    echo $item_taxr2;

                    ?>" value="<?php

                    echo $item_taxr2;

                    ?>">
                    <input type="hidden" class="form-control jod" name="saman-tax2[]" placeholder="CGST" value="<?php

                    echo $item_discount;

                    ?>">

                </div>
                
                <div class="col-xs-12 col-md-6 col-lg-2 mol-2">
                    <div class="input-group">
								<span class="input-group-addon"><?php

                                    echo $siteConfig['curr'];

                                    ?></span>
                        <input type="number" class="form-control jod-sub" name="bill_saman_sub[]" id="bill_saman_sub[]"
                               value="<?php

                               echo $item_subtotal;

                               ?>" aria-describedby="sizing-addon1" disabled="disabled" >
                        <input type="hidden" class="ttInput" name="total[]" id="total-0" value="0">
                        
                    </div>
                    

                    </div>
                    <div class="col-xs-12 col-md-3 col-lg-1 mol-1"> <button name="gen" class="taxgen"> Tax Gen</button></div>
                    <div class="clearfix"></div>
            </article>
                <?php

            }

            ?>


        </div>
        <div class="row margin-t ">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading panel-primary">
                        <span class="icon-user-tie"></span>Customer Information<?php

                        $dsbl = "";
                        if ($cidd > 0) {
                            $dsbl = "disabled";
                        }

                        ?>


                    </div>
                    <div class="panel-body form-group form-group-sm" id="myc">

                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_name"
                                   id="grahak_name" placeholder="Customer name" tabindex="1" value="<?php

                            echo $grahak_name;

                            ?>" <?php

                            echo $dsbl;

                            ?>>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_adrs1"
                                   id="grahak_adrs1" placeholder="Address" tabindex="3" value="<?php

                            echo $grahak_adrs1;

                            ?>" <?php

                            echo $dsbl;

                            ?>>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_adrs2"
                                   id="grahak_adrs2" placeholder="City, Country, Postal Code" tabindex="5" value="<?php

                            echo $grahak_adrs2;

                            ?>" <?php

                            echo $dsbl;

                            ?>>
                        </div>
                        <input type="hidden" name="grahak_id" id="grahak_id" value="<?php

                        echo $cidd;

                        ?>">


                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom required" name="grahak_phone"
                                   id="grahak_phone" placeholder="Phone number" tabindex="8" value="<?php

                            echo $grahak_phone;

                            ?>" <?php

                            echo $dsbl;

                            ?>>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom" name="grahak_email" id="grahak_email"
                                   placeholder="Email" tabindex="8" value="<?php

                            echo $grahak_email;

                            ?>" <?php

                            echo $dsbl;

                            ?>>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control margin-bottom" name="grahak_tax" id="grahak_tax"
                                   placeholder="TAX ID" tabindex="8" value="<?php

                            echo $grahak_tax;

                            ?>" <?php

                            echo $dsbl;

                            ?>>
                        </div>
                        <div class="form-group no-margin-bottom">
                            <a href="#" class="grahk-select btn btn-success">Select existing customer</a>&nbsp; <input
                                    type="button" id="clear-form" class="btn btn-danger dropdown-toggle" value="Clear"/>
                        </div>
                    </div>

                </div>


            </div>
            <div class=" col-md-6">

                <div class="panel panel-primary">
                    <div class="panel-heading panel-summary"><span class="icon-cart"></span>
                        Summary


                    </div>
                    <div id="bill_totl" class=" panel panel-default padding-b">
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Sub Total:</strong>
                            </div>
                            <div class="col-xs-4 text-right">
                                <?php

                                echo $siteConfig['curr'];

                                ?><span class="bill-sub-yog"><?php

                                    echo $bill_upyog;

                                    ?></span>
                                <input type="hidden" name="bill_upyog" id="bill_upyog" value="<?php

                                echo $bill_upyog;

                                ?>">
                            </div>
                        </div>
                        
                        <div class="row margin-bottom padding">
                        
                        <div class="col-xs-8 text-right"> SGST</div>
                        <div class="col-xs-4 text-right">
                            <?php

                            echo $siteConfig['curr'];

                            ?><span class="bill-ptax" data-tax-method="<?php

                            echo $siteConfig['vinc']

                            ?>" data-enable-tax="<?php

                            echo $siteConfig['vatst']

                            ?>"><?php

                                echo $bill_tax;

                                ?></span>
                            <input type="hidden" name="bill_ptax" id="bill_ptax" value="<?php

                            echo $bill_tax;

                            ?>">
                        </div>
                    </div>
                    
                    <div class="row margin-bottom padding">
                        
                        <div class="col-xs-8 text-right"> CGST</div>
                        <div class="col-xs-4 text-right">
                            <?php

                            echo $siteConfig['curr'];

                            ?><span class="bill-ptax2" data-tax-method="<?php

                            echo $siteConfig['vinc']

                            ?>" data-enable-tax="<?php

                            echo $siteConfig['vatst']

                            ?>"><?php

                                echo $bill_tax2;

                                ?></span>
                            <input type="hidden" name="bill_ptax2" id="bill_ptax2" value="<?php

                            echo $bill_tax2;

                            ?>">
                        </div>
                    </div>
                    
                    <div class="row margin-bottom padding">
                        <div class="col-xs-8 text-right">
                            <br>Remove TAX/VAT <input type="checkbox" class="remove_tax">
                        </div>
                    </div>

                        
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Discount:</strong>
                            </div>
                            <div class="ccol-xs-4 text-right"><input type="hidden" class="djod vvt"
                                                                     value="<?php echo $bill_disc; ?>">

                                <div class="input-group">

                                    <input type="text" class="form-control jod vvt" name="invoice_vvt" value="<?php

                                    echo $bill_disc;

                                    ?>"><select class="jod vvtype" name="jod vvtype"> <option value="0">%</option> <option selected="selected" value="1">Flat</option> </select></div>
                            </div>
                        </div>
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Total Discount:</strong>
                            </div>
                            <div class="col-xs-4 text-right">

                                <?php

                                echo $siteConfig['curr'];

                                ?>
                                <span class="bill-fdisc" data-enable-tax="1" data-tax-method="0"><?php

                                    echo $bill_disc;

                                    ?></span>
                                <input type="hidden" name="bill_fdisc" id="bill_fdisc" value="<?php

                                echo $bill_disc;

                                ?>">
                            </div>
                        </div>
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Shipping</strong>
                            </div>
                            <div class="col-xs-4 text-right">

                                <div class="input-group">
							<span class="input-group-addon"><?php

                                echo $siteConfig['curr'];

                                ?></span>
                                    <input type="text" class="form-control jod deliv" name="saman_deliv"
                                           aria-describedby="sizing-addon1" placeholder="0.00" value="<?php

                                    echo $bill_shipping;

                                    ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Total:</strong>
                            </div>
                            <div class="col-xs-4 text-right bttl">
                                <?php

                                echo $siteConfig['curr'];

                                ?><span class="bill-yog"><?php

                                    echo $bill_yog;

                                    ?></span>
                                <input type="hidden" name="bill_yog" id="bill_yog" value="<?php

                                echo $bill_yog;

                                ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 text-right "><br>
                                <strong>Status:</strong>
                            </div>
                            <div class="col-xs-4 text-right margin-t">
                                <select name="invoice_status" id="invoice_status" class="form-control">
                                    <option value="paid" > <?php echo $bill_status; ?>  </option> </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row  panel panel-default padding-f">
            <div class="col-md-6">
										<textarea class="form-control" name="invoice_notes"
                                                  placeholder="Please enter any order notes here." rows="3"><?php

                                            echo $bill_notes;

                                            ?></textarea> <?php

                if ($bill_tax > 0.00 and $siteConfig['vatst'] == 0) {

                    ?>

                    <div class="alert alert-danger">
                        <strong>Alert!!</strong> : Please check you TAX Settings

                    </div>

                    <?php

                }

                ?>
            </div>
            <div class="col-md-6 btn-group"><span class="cstock"><input type="checkbox" name="cstock" value="yes"
                                                                        checked> Auto update above items quantity and stock in products list. Only if product is selected from product list.</span>
                <input type="submit" id="action_edit_receipt" class="btn btn-success float-right" value="Update Receipt"
                       data-loading-text="Creating...">

            </div>

        </div>
    </form><?php

    $colums = array('username');
    $whereConditions = array('id' => $eid);
    $iuser = $db->select('employee', $colums, $whereConditions)->results();

    ?>
    <small class="float-right"><strong>Created by <em><?php

                echo $iuser['username'];

                ?></em></strong></small>


    <script type="text/javascript">
        $("#action_edit_receipt").click(function (e) {
            e.preventDefault();
            updateInvoice();
        });
        function updateInvoice() {
            var errorNum = farmCheck();

            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {
                var $btn = $("#action_edit_receipt").button("loading");
                $("#edit_receipt").find(':input:disabled').removeAttr('disabled');

                jQuery.ajax({

                    url: 'request/crt.php',
                    type: 'POST',
                    data: $("#edit_receipt").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $("#edit_receipt").remove();
                        $btn.button("reset");
                        $(".saman_qty").attr('disabled', 'disabled');

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
    </script><?php

}

function rlist()
{

    global $db, $user;
    if ($user->group_id > 2) {
        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
    }


    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#rlist').DataTable({
                stateSave: true,
                "processing": true,
                "serverSide": true,
                "ajax": "request/listbills.php?page=receipt"
            });
        });</script>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div id="notify" class="alert alert-success" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>

                    <div class="message"></div>
                </div>
                <div class="panel-heading">Manage Receipts</div>
                <div class="panel-body tbl">
                    <table id="rlist" class="table-responsive cell-border" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Settings</th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).on('click', ".delete-receipt", function (e) {
            e.preventDefault();

            var receiptId = 'act=delete_receipt&delete=' + $(this).attr('data-receipt-id');
            var receipt = $(this);

            $('#delete_receipt').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                deleteReceipt(receiptId);
                $(receipt).closest('tr').remove();
            });
        });
        function deleteReceipt(receiptId) {

            jQuery.ajax({

                url: 'request/crt.php',
                type: 'POST',
                data: receiptId,
                dataType: 'json',
                success: function (data) {
                    if (data.status == "Success") {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                        $("html, body").scrollTop($("body").offset().top);

                    } else {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                        $("html, body").scrollTop($("body").offset().top);

                    }
                    ;
                },
                error: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    button.button('reset');
                    ;
                }
            });

        }
    </script>
    <div id="delete_receipt" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Receipt</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this receipt?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div><?php

}

?>
<div id="insert" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select a product</h4>
            </div>
            <div class="modal-body">
                <?php

                rprdPL();

                ?>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-my btn-rounded" id="selected">Add</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="insert_grahk" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select an existing vendor</h4>
            </div>
            <div class="modal-body">
                <?php

                vndPL();

                ?>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
        </div>
    </div>
</div>