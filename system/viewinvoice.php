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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "viewinvoice.php")) {
    die("Internal Server Error!");
}
//edit invoice

if (isset($_GET['id'])) {
    $getID = intval($_GET['id']);
} else {
    $getID = 0;
}
$token = hash_hmac('ripemd160', $getID, IKEY);
$query = "SELECT *
			FROM rec_invoices WHERE tid = '$getID' LIMIT 1";

if ($result = $db->pdoQuery($query)->results()) {


    foreach ($result as $row) {
        $bill_number = $row['tid'];
        $bill_date = dateformat($row['tsn_date']);
        $bill_due_date = $row['tsn_due'];
        $bill_upyog = $row['subtotal'];
        $bill_shipping = $row['shipping'];
        $bill_disc = $row['discount'];
        $bill_tax = $row['tax'];
		$bill_tax2 = $row['tax2'];
        $bill_yog = $row['total'];
        $bill_notes = $row['notes'];
        //$bill_status = $row['status'];
        $bill_status = '';
        $cidd = $row['csd'];
        $eid = $row['eid'];
        $bill_pm = $row['pmethod'];
        $next_pm = $row['rc_next'];
        $last_pm = $row['rc_up'];
        $active = $row['active'];
        $bill_rm = $row['ramm'];
        $date2 = date('Y-m-d');
        $inext_pm = $next_pm;
        switch ($row['rperiod']) {
            case 1:
                $rc_next = "7 days";
                break;
            case 2:
                $rc_next = "15 days";
                break;
            case 3:
                $rc_next = "30 days";
                break;
            case 4:
                $rc_next = "3 months";
                break;
            case 5:
                $rc_next = "6 months";
                break;
            case 6:
                $rc_next = "1 year";
                break;
            case 7:
                $rc_next = "3 years";
                break;
        }
        //if(strtotime($bill_due_date) >= strtotime($date2))
        //	{
        //	if(strtotime($last_pm) > strtotime($bill_due_date)){
        //	$next_pm=$bill_due_date;}}
        if ($last_pm == '0000-00-00') {
            $next_pm = $bill_due_date;
        }
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
            $row1 = $db->select('rec_customers', null, $whereConditions)->results();
            $grahak_name = $row1['name'];
            $grahak_adrs1 = $row1['address1'];
            $grahak_adrs2 = $row1['address2'];
            $grahak_phone = $row1['phone'];
            $grahak_email = $row1['email'];
            $grahak_tax = $row1['taxid'];
        }
    }
} else {
    die();
}

?>

<div id="notify" class="alert alert-success" style="display:none;">
    <a href="#" class="close" data-dismiss="alert">&times;</a>

    <div class="message"></div>
</div>
<!-- Main content -->
<div id="invoice">
    <div class="invoice" id="maininvoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <strong> <?php echo $siteConfig['name']; ?>
                    </strong>
                    <?php

                    if ($active == 0){

                    echo ' <span class="pull-right">';

                    if (strtotime($next_pm) > strtotime($date2)) {

                        echo 'Status: <span id="markp2"><span class="label label-primary">Recurring</span></span>';
                    } else {
                        $bill_status = 'due';
                        echo 'Status: <span id="markp2"><span class="label label-danger">Due</span></span>';
                    }


                    ?></span>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php if ($bill_status == 'due') {
                    echo '<span id="markp"><a data-invoice-id="' .
                        $row['tid'] . '" data-csd="' . $row['csd'] .
                        '" class="btn btn-primary btn-sm rec-payment" title="Mark as Paid"><span class="icon-credit-card"></span>Mark as Paid for ' . dateformat($next_pm) . '</a>&nbsp; &nbsp;<a data-invoice-id="' .
                        $row['tid'] . '" data-csd="' . $row['csd'] .
                        '" data-type="recinvoice" class="btn btn-info btn-sm prm-invoice" title="Payment Reminder"><span class="icon-bullhorn"></span>Payment Reminder </a>&nbsp; </span>';
                }
                echo '
 

        &nbsp; &nbsp;<a href="view/invoice-rec.php?id=' .
                    $row["tid"] . '" class="btn btn-success btn-sm"  title="Print/Save"><span class="icon-download2"></span> PDF/Print</a>
          
          <a href="index.php?rdp=recurring&op=edit&id=' .
                    $row["tid"] . '" class="btn btn-warning btn-sm" title="Edit"><span class="icon-pencil"></span>Edit Invoice</a> <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-primary btn-sm rsend-invoice" title="Email"><span class="icon-mail4"></span>Email </a> <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-info btn-sm rsend-sms" title="SMS"><span class="icon-star-empty"></span>SMS </a> &nbsp; ';

                echo '<span id="markp"><a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-danger btn-sm can-payment" title="Stop Recurring"><span class="icon-cancel-circle"></span>Stop Recurring</a></span>';
                }
                else {
                    echo '<span class="pull-right">Status: <span id="markp2"><span class="label label-warning">Canceled</span></span></span>';
                };

                ?>
            </div>
            <!-- /.col -->
        </div>
        <hr>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <?php echo '<strong>' . $siteConfig['name'] . '</strong><br>' . $siteConfig['address'] . '<br>' . $siteConfig['address2'] . '<br>Phone: ' . $siteConfig['phone'], '<br>Mail: ' . $siteConfig['email'] . '<br>VAT/TAX No ' .
                        $siteConfig['vatno'];
                    ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <?php echo '<strong>' . $grahak_name . '</strong><br>' . $grahak_adrs1 . '<br>' . $grahak_adrs2 . '<br>' . 'Phone: ' . $grahak_phone . '<br>' . $grahak_email;
                    if (!$grahak_tax == '') {
                        echo '<br>VAT/TAX No. ' . $grahak_tax;
                    } ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Invoice #<?php

                    echo $getID;

                    echo '</b>
            <br>

            <b>Invoice Date: </b> ';

                    echo $bill_date;

                    echo '<br>
            <b>First Payment Due: </b>';

                    echo dateformat($bill_due_date);
                    if ($last_pm != '0000-00-00') {
                        echo '<br>
            <b>Last Payment Date: </b>';
                        echo dateformat($last_pm);
                    }
                    if ($active == 0) {
                        echo '<br>
            <b>Next Payment Date: </b>';
                        echo dateformat($inext_pm);
                        echo "<br>
            <b>Recurrence on : </b> $rc_next";

                    }
                    ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <br>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>SGST</th>
                            <th>CGST</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $query2 = "SELECT * FROM rec_items WHERE tid = '$getID'";
                        $result2 = $db->pdoQuery($query2)->results();
                        foreach ($result2 as $rows) {
                            $item_product = $rows['product'];
                            $item_qty = $rows['qty'];
                            $item_price = $rows['price'];
                            $item_tax = $rows['discount'];
                            $item_subtotal = $rows['subtotal'];
                            $item_taxr = $rows['trate'];
							$item_taxr2 = $rows['trate2'];

                            ?>
                            <tr>
                                <td><?php

                                    echo $item_product;


                                    ?></td>
                                <td><?php

                                    echo $item_qty;

                                    ?></td>
                                <td><?php

                                    echo $item_price;

                                    ?></td>
                                
                                    
                                <td><?php echo $item_qty*$item_price*$item_taxr/100 . ' (' . $item_taxr . '%)';  ?></td>
                            <td><?php echo $item_qty*$item_price*$item_taxr2/100 . ' (' . $item_taxr2 . '%)';  ?></td>
                            
                                <td><?php

                                    echo $item_subtotal;

                                    ?></td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <br>&nbsp;
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="lead">Payment Methods:</p>
                <?php if ($bill_pm == 1) { ?>
                    <img src="images/card/visa.png" alt="Visa">
                    <img src="images/card/mastercard.png" alt="Mastercard">
                    <img src="images/card/american-express.png" alt="American Express">
                    <img src="images/card/paypal2.png" alt="Paypal"><br><br><a
                            href='index.php?rdp=checkout-rec&id=<?php echo $getID ?>'
                            class='btn btn-primary btn-lg'><span
                                class='icon-credit-card' aria-hidden='true'></span>Make Card Payment </a>
                <?php } else {
                    echo '<img src="images/card/cashpay.png" alt="Cash payment"><br><br>';
                }
                if ($bill_pm == 3) {
                    echo " <p class='lead'>Partial Payments:</p><strong>Total Due Amount:</strong> " . $siteConfig['curr'], $bill_yog . "<br>";
                    echo "<strong>Partial Paid Amount: </strong>" . $siteConfig['curr'], $bill_rm . "<br>";
                    echo "<strong>Due Amount:</strong> " . $siteConfig['curr'], amountFormat($bill_yog - $bill_rm, 2) . "";

                }


                if (!$bill_notes == '') {

                    echo '<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;"><strong>Customer Note&nbsp; </strong>';
                    echo $bill_notes;
                    echo '  </p>';
                }
                ?>


            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <p class="lead">Summary</p>

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td><?php

                                echo $siteConfig['curr'], $bill_upyog;

                                ?></td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td><?php

                                echo $siteConfig['curr'], $bill_disc;

                                ?></td>
                        </tr>
                        <tr>
                            <th>SGST</th>
                            <td><?php

                                echo $siteConfig['curr'], $bill_tax;

                                ?></td>
                        </tr>
                        <tr>
                            <th>CGST</th>
                            <td><?php

                                echo $siteConfig['curr'], $bill_tax2;

                                ?></td>
                        </tr>
                        <tr>
                            <th>Shipping:</th>
                            <td><?php

                                echo $siteConfig['curr'], $bill_shipping;

                                ?></td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td><?php

                                echo $siteConfig['curr'], amountFormat($bill_yog);

                                ?></td>
                        </tr>


                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <script type="text/javascript">
            //paid
            $(document).on('click', ".rec-payment", function (e) {
                e.preventDefault();

                var billNo = 'act=rec_payment&send=' + $(this).attr('data-invoice-id') + '&rcnext=<?php echo $next_pm; ?>';

                $('#rec_payment').modal({backdrop: 'static', keyboard: false}).one('click', '#pay', function () {
                    recInvoice(billNo);

                });
            });
            function recInvoice(billNo) {
                var $btn;
                jQuery.ajax({

                    url: 'request/recp.php',
                    type: 'POST',
                    data: billNo,
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == "Success") {
                            $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                            $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                            $("#markp").remove();
                            $("#markp2").remove();
                            $("html, body").scrollTop($("body").offset().top);

                            setTimeout(function () {
                                location.reload();
                            }, 2000);

                        } else {
                            $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                            $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                            $("#markp").remove();
                            $("html, body").scrollTop($("body").offset().top);

                        }
                        ;
                    },
                    error: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                        $("html, body").scrollTop($("body").offset().top);
                        $btn.button("reset");
                    }
                });

            }

        </script>
        <div class="row">
            <div class="col-md-8">
                <p class="lead">Total Payment Paid for this invoice is</p>

                <strong><?php echo $siteConfig['curr'], amountFormat($bill_rm); ?></strong>
            </div>
        </div>
        <div class="table-responsive"><br>Payment Transactions
            <table class="table">
                <tr>
                    <th style="width:50%">Date</th>
                    <th>Amount</th>
                </tr>
                <?php $query2 = "SELECT * FROM rec_part_trans WHERE tid = '$getID'";
                $result2 = $db->pdoQuery($query2)->results();
                foreach ($result2 as $rows) {
                    $item_product = $rows['amount'];

                    $item_price = $rows['tdate'];
                    echo "<tr>
                        <td>$item_price</td>
						<td>$item_product</td>
                        
                    </tr>";
                }
                ?>
            </table>
        </div>

    </div>
    <!-- /.content -->
</div>
<div id="rsend_invoice" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send this Invoice as an email</h4>
            </div>
            <div class="modal-body">
                <p>Are you like to email this invoice?</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="send">Yes</button>
                <button type="button" data-dismiss="modal" class="btn">No</button>
            </div>
        </div>
    </div>
</div>
<div id="rsend_sms" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send this Invoice as a sms</h4>
            </div>
            <div class="modal-body">
                <p>Are you like to sms this invoice?</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="sms">Yes</button>
                <button type="button" data-dismiss="modal" class="btn">No</button>
            </div>
        </div>
    </div>
</div>
<div id="rec_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mark as Paid</h4>
            </div>
            <div class="modal-body">
                <p>Are you like to mark as paid this invoice?</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="pay">Yes</button>
                <button type="button" data-dismiss="modal" class="btn">No</button>
            </div>
        </div>
    </div>
</div>
<div id="can_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Stop Recurring</h4>
            </div>
            <div class="modal-body">
                <p>Are you like to stop recurring of this invoice?</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="rcancel">Yes</button>
                <button type="button" data-dismiss="modal" class="btn">No</button>
            </div>
        </div>
    </div>
</div>
<div id="prm_invoice" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payment reminder</h4>
            </div>
            <div class="modal-body">
                <p>Are you like to send payment reminder email for this Invoice?</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="rmsend">Yes</button>
                <button type="button" data-dismiss="modal" class="btn">No</button>
            </div>
        </div>
    </div>
</div>

<script src="lib/js/rec-control.js"></script>