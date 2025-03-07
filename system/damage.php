<script type="text/javascript">
    $(document).ready(function () {
        $('#saman_list').on('click', ".delete-row", function (e) {
            e.preventDefault();
            $(this).closest('article').remove();

            antimYog();
        });

    });
</script>
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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "damage.php")) {
    die("Internal Server Error!");
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
        editq($id);
        break;
    case "ilist":
        ilist();
        break;
    default:
        qlist();
        break;
}
function create()
{
    global $siteConfig, $user;
    function getSerial()
    {
        global $db;
        $row = $db->select('damage', null, null, 'ORDER BY tid DESC')->results();
        if ($row) {
            echo $row['tid'] + 1;
        }
    }

    ?>


    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <form method="post" id="create_quote" class="fmargin">
        <input type="hidden" name="act" value="create_quote">

        <div class="row ">

            <div class="panel panel-primary ">
                <div class="panel-body chead">
                    <div class="col-md-6">
                        <h3>New Damage Receipt</h3></div>
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
                            <input type="text" name="invoice_id" id="invoice_id" class="form-control required"
                                   placeholder="Receipt Number" aria-describedby="sizing-addon1" value="<?php

                            getSerial();

                            ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="row bg-light-blue color-palette margin-t padding-f">


            <div class="ol-xs-12 col-md-6 col-lg-5 mol-5"><a href="#" class="btn btn-warning btn-xs add-row"><span
                            class="icon-plus"></span><strong>Add empty</strong></a> PRODUCTS ROW
            </div>
            <div class="col-xs-12 col-md-6 col-lg-1 mol-1">QTY</div>
            <div class="col-xs-12 col-md-6 col-lg-2 mol-2">UNIT PRICE</div>
            <div class="col-xs-12 col-md-6 col-lg-2 mol-2">TAX</div>
            <div class="col-xs-12 col-md-6 col-lg-2 mol-2" style="border-right:0">SUBTOTAL</div>

        </div>

        <div class="row panel panel-default" id="saman_list">
            <br>
            <article class="mr6 margin-b">
                <div class="col-xs-12 col-md-6 col-lg-5 mol-5">
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
                <div class="col-xs-12 col-md-6 col-lg-1 mol-1">
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

                <div class="col-xs-12 col-md-6 col-lg-2 mol-2">

                    <input type="number" class="form-control jod" name="bill_saman_tax[]" placeholder="TAX" value="<?php

                    echo $siteConfig['vatrate']

                    ?>">
                    <input type="hidden" class="form-control jod" name="saman-tax[]" placeholder="TAX" value="">


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
                                   id="grahak_name" placeholder="Customer name" tabindex="1">
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
                            <a href="#" class="grahk-select btn btn-success">Select existing vendor</a>&nbsp; <input
                                    type="button" id="clear-form" class="btn btn-danger dropdown-toggle" value="Clear"/>&nbsp;
                            &nbsp; <span class="crepeat"><input type="hidden" name="crepeat" value="no"></span>
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

                                ?><span class="bill-sub-yog">0.00</span>
                                <input type="hidden" name="bill_upyog" id="bill_upyog">
                            </div>
                        </div>
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <br>Remove TAX/VAT <input type="checkbox" class="remove_tax">
                            </div>
                            <div class="col-xs-4 text-right">
                                <?php

                                echo $siteConfig['curr'];

                                ?><span class="bill-ptax" data-tax-method="<?php

                                echo $siteConfig['vinc']

                                ?>" data-enable-tax="<?php

                                echo $siteConfig['vatst']

                                ?>">0.00</span>
                                <input type="hidden" name="bill_ptax" id="bill_ptax"
                                       placeholder="Enter % or value (ex: 10% or 10.50)">
                            </div>
                        </div>


                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Discount:</strong>
                            </div>
                            <input type="hidden" class="djod vvt" value="0">

                            <div class="col-xs-4 text-right">
                                <div class="input-group">
                                    <input type="number" class="form-control jod vvt" name="invoice_vvt"
                                           placeholder="Discount"><span class="input-group-addon">%</span></div>
                            </div>
                        </div>
                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Total Discount:</strong>
                            </div>
                            <div class="col-xs-4 text-right">

                                <?php

                                echo $siteConfig['curr'];

                                ?><span class="bill-fdisc" data-enable-tax="0" data-tax-method="0">0.00</span>
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
                                           aria-describedby="sizing-addon1" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="row margin-bottom padding">
                            <div class="col-xs-8 text-right">
                                <strong>Total:</strong>
                            </div>
                            <div class="col-xs-4 text-right  large">
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
                                    <option value="returned" selected>Return</option>
                                    <option value="refunded">Refund</option>
                                    <option value="replaced">Replace</option>
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
            <div class="col-md-6 btn-group">
                <input type="submit" id="action_create_quote" class="btn btn-success float-right" value="Create Receipt"
                       data-loading-text="Creating...">
            </div>

        </div>
    </form>
    <small class="float-right"><strong>Creator Employee ID <em><?php

                echo $user->username

                ?></em></strong></small>


    <script type="text/javascript">
        $("#action_create_quote").click(function (e) {
            e.preventDefault();
            actionCreateQuote();
        });
        function actionCreateQuote() {

            var errorNum = farmCheck();


            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {

                var $btn = $("#action_create_quote").button("loading");

                $(".required").parent().removeClass("has-error");
                $("#create_quote").find(':input:disabled').removeAttr('disabled');

                $.ajax({

                    url: 'request/drt.php',
                    type: 'POST',
                    data: $("#create_quote").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $("#create_quote").remove();
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
    </script>

    <?php

}

function editq($id)
{
    global $db, $user, $siteConfig;
    if ($user->group_id > 2) {
        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
    }
    if (isset($_GET['id'])) {
        $getID = intval($_GET['id']);
    } else {
        $getID = 0;
    }


    $query = "SELECT p.*, i.*
			FROM damage_items AS p 
			JOIN damage AS i ON i.tid = p.tid
			
			WHERE p.tid = '$getID' LIMIT 1";

    if ($result = $db->pdoQuery($query)->results()) {

        foreach ($result as $row) {
            $bill_number = $row['tid'];
            $bill_date = dateformat($row['tsn_date']);
            $bill_due_date = dateformat($row['tsn_due']);
            $bill_upyog = $row['subtotal'];
            $bill_shipping = $row['shipping'];
            $bill_disc = $row['discount'];
            $bill_discr = $row['discountr'];
            $bill_tax = $row['tax'];
            $bill_yog = $row['total'];
            $bill_notes = $row['notes'];

            $bill_status = $row['status'];
            $cidd = $row['csd'];
            $eid = $row['eid'];


            if ($cidd > 0) {

                $whereConditions = array('id' => $cidd);
                $row1 = $db->select('reg_customers', null, $whereConditions)->results();


                $grahak_name = $row1['name'];
                $grahak_adrs1 = $row1['address1'];
                $grahak_adrs2 = $row1['address2'];
                $grahak_phone = $row1['phone'];
                $grahak_email = $row1['email'];
                $grahak_tax = $row1['taxid'];

            } else {

                $whereConditions = array('tid' => $getID);
                $row1 = $db->select('damage_customers', null, $whereConditions)->results();


                $grahak_name = $row1['name'];
                $grahak_adrs1 = $row1['address1'];
                $grahak_adrs2 = $row1['address2'];
                $grahak_phone = $row1['phone'];
                $grahak_email = $row1['email'];
                $grahak_tax = $row1['taxid'];
            }
        }


        ?>


        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>

        <form method="post" id="edit_quote" class="fmargin">
            <input type="hidden" name="act" value="edit_quote">
            <input type="hidden" name="update_id" value="<?php

            echo $getID;

            ?>">


            <div class="row ">

                <div class="panel panel-primary">
                    <div class="panel-body chead">
                        <div class="col-md-6">
                            <h3>Edit Damage Receipt (<?php

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


                <div class="ol-xs-12 col-md-6 col-lg-5 mol-5"><a href="#" class="btn btn-warning btn-xs add-row"><span
                                class="icon-plus"></span><strong>Add empty</strong></a> PRODUCTS ROW
                </div>
                <div class="col-xs-12 col-md-6 col-lg-1 mol-1">QTY</div>
                <div class="col-xs-12 col-md-6 col-lg-2 mol-2">UNIT PRICE</div>
                <div class="col-xs-12 col-md-6 col-lg-2 mol-2">TAX</div>
                <div class="col-xs-12 col-md-6 col-lg-2 mol-2" style="border-right:0">SUBTOTAL</div>

            </div>

            <div class="row panel panel-default" id="saman_list">
                <br>
                <?php

                $query2 = "SELECT * FROM damage_items WHERE tid = '$getID'";
                $result2 = $db->pdoQuery($query2)->results();
                foreach ($result2 as $rows) {
                    $item_product = $rows['product'];
                    $item_qty = $rows['qty'];
                    $item_price = $rows['price'];
                    $item_discount = $rows['discount'];
                    $item_subtotal = $rows['subtotal'];
                    $item_taxr = $rows['trate'];
                    $item_pid = $rows['pid'];

                    ?>
                    <article class="mr6 margin-b">
                        <div class="col-xs-12 col-md-6 col-lg-5 mol-5">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <button href="#" class="btn btn-danger btn-xs" disabled><span class="icon-database"
                                                                                                  title="Select Product From List"></span>List
                                    </button>
                                </div>
                                <input type="text" class="form-control item-input bill_saman hbh required"
                                       id="bill_saman[]"
                                       name="bill_saman[]" placeholder="Enter item title and / or description"
                                       value="<?php

                                       echo $item_product;

                                       ?>">
                                <input
                                        type="hidden" class="bill_pid" id="bill_pid[]"
                                        name="bill_pid[]" value="<?php

                                echo $item_pid;

                                ?>">

                                <div class="input-group-addon">
                                    <a href="#" class="btn btn-danger btn-xs delete-row"
                                       title="Delete Product row"><span
                                                class="icon-cancel-circle"></span></a></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-1 mol-1">
                            <input type="text" class="form-control saman_qty jod vrequired" id="saman_qty[]"
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
                                <input type="text" class="form-control jod billsaman_price required"
                                       name="billsaman_price[]"
                                       placeholder="0.00" value="<?php

                                echo $item_price;

                                ?>">
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-2 mol-2">

                            <input type="text" class="form-control jod" name="bill_saman_tax[]" placeholder="<?php

                            echo $item_taxr;

                            ?>" value="<?php

                            echo $item_taxr;

                            ?>">
                            <input type="hidden" class="form-control jod" name="saman-tax[]" placeholder="TAX"
                                   value="<?php

                                   echo $item_discount;

                                   ?>">

                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-2 mol-2">
                            <div class="input-group">
								<span class="input-group-addon"><?php

                                    echo $siteConfig['curr'];

                                    ?></span>
                                <input type="text" class="form-control jod-sub" name="bill_saman_sub[]"
                                       id="bill_saman_sub[]"
                                       value="<?php

                                       echo $item_subtotal;

                                       ?>" aria-describedby="sizing-addon1" disabled>
                                <input type="hidden" class="ttInput" name="total[]" id="total-0" value="0">
                            </div>
                            <div class="clear">

                            </div>
                            <br><br>
                    </article>
                    <?php

                }

                ?>


            </div>
            <div class="row margin-t ">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading panel-primary">
                            <span class="icon-user-tie"></span>Vendor Information<?php

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
                                       id="grahak_adrs2" placeholder="City, Country, Postal Code" tabindex="5"
                                       value="<?php

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
                                <input type="text" class="form-control margin-bottom" name="grahak_email"
                                       id="grahak_email"
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
                                <a href="#" class="grahk-select btn btn-success">Select existing vendor</a>&nbsp;
                                <input
                                        type="button" id="clear-form" class="btn btn-danger dropdown-toggle"
                                        value="Clear"/>
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
                                <div class="col-xs-8 text-right">
                                    <br>Remove TAX/VAT <input type="checkbox" class="remove_tax">
                                </div>
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
                                <div class="col-xs-8 text-right">
                                    <strong>Discount:</strong>
                                </div>
                                <div class="ccol-xs-4 text-right"><input type="hidden" class="djod vvt"
                                                                         value="<?php echo $bill_discr; ?>">

                                    <div class="input-group">

                                        <input type="text" class="form-control jod vvt" name="invoice_vvt" value="<?php

                                        echo $bill_discr;

                                        ?>"><span class="input-group-addon tbh">%</span></div>
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
                                <div class="col-xs-4 text-right  large">
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

                                        <option value="returned" <?php

                                        if ($bill_status === 'returned')
                                        {

                                        ?>selected<?php

                                        }

                                        ?>>Return
                                        </option>
                                        <option value="refunded" <?php

                                        if ($bill_status === 'refunded')
                                        {

                                        ?>selected<?php

                                        }

                                        ?>>Refund
                                        </option>
                                        <option value="replaced" <?php

                                        if ($bill_status === 'replaced')
                                        {

                                        ?>selected<?php

                                        }

                                        ?>>Replace
                                        </option>
                                    </select>
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
                <div class="col-md-6 btn-group">
                    <input type="submit" id="action_edit_quote" class="btn btn-success float-right"
                           value="Update Receipt"
                           data-loading-text="Updating...">
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
            $("#action_edit_quote").click(function (e) {
                e.preventDefault();
                updateInvoice();
            });
            function updateInvoice() {
                var errorNum = farmCheck();

                if (errorNum > 0) {
                    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                    $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                    $("#notify").animate({scrollTop: $('#notify').offset().top}, 1000);
                } else {
                    var $btn = $("#action_edit_quote").button("loading");
                    $("#edit_quote").find(':input:disabled').removeAttr('disabled');

                    jQuery.ajax({

                        url: 'request/drt.php',
                        type: 'POST',
                        data: $("#edit_quote").serialize(),
                        dataType: 'json',
                        success: function (data) {
                            $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                            $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                            $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                            $("#edit_quote").remove();
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
}

function qlist()
{
    global $user;
    if ($user->group_id > 2) {
        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#qlist').DataTable({
                stateSave: true,
                "processing": true,
                "serverSide": true,
                "ajax": "request/listitem.php?page=damage"
            });
        });</script>
    <div class="row">
        <div class="col-lg-12">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Manage Damage Receipts</div>
                <div class="panel-body tbl"><p>Damaged stock and its supplier/vendor records</p>
                    <table id="qlist" class="table-responsive cell-border" cellspacing="0">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).on('click', ".delete-quote", function (e) {
            e.preventDefault();

            var quoteId = 'act=delete_quote&delete=' + $(this).attr('data-quote-id');
            var quote = $(this);

            $('#delete_quote').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                deleteQuote(quoteId);
                $(quote).closest('tr').remove();
            });
        });
        function deleteQuote(quoteId) {

            jQuery.ajax({

                url: 'request/drt.php',
                type: 'POST',
                data: quoteId,
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

                }
            });

        }
    </script>
    <div id="delete_quote" class="modal fade">
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

function ilist()
{
    global $user;
    if ($user->group_id > 2) {
        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#qlist').DataTable({
                stateSave: true,
                "processing": true,
                "serverSide": true,
                "ajax": "request/listitem.php?page=dilist"
            });
        });</script>
    <div class="row">
        <div class="col-lg-12">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Damaged Product List</div>
                <div class="panel-body tbl">
                    <table id="qlist" class="table-responsive cell-border" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Receipt No.</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div><?php }

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

                prdPL();

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