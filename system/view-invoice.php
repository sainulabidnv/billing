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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "view-invoice.php")) {
    die("Internal Server Error!");
}
//edit invoice

if (isset($_GET['id'])) {
    $getID = intval($_GET['id']);
} else {
    $getID = 0;
}
$token = hash_hmac('ripemd160', $getID, IKEY);
$query = "SELECT * FROM invoices WHERE tid = '$getID' LIMIT 1";

if ($result = $db->pdoQuery($query)->results()) {


    foreach ($result as $row) {
        $bill_number = $row['tid'];
        $bill_date = dateformat($row['tsn_date']);
        $bill_due_date = dateformat($row['tsn_due']);
        $bill_upyog = $row['subtotal'];
        $bill_shipping = $row['shipping'];
        $bill_disc = $row['discount'];
        $bill_tax = $row['tax'];
		$bill_tax2 = $row['tax2'];
        $bill_yog = $row['total'];
        $bill_notes = $row['notes'];
        $bill_status = $row['status'];
        $cidd = $row['csd'];
        $eid = $row['eid'];
        $bill_pm = $row['pmethod'];
        $bill_rm = $row['ramm'];


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
            $row1 = $db->select('customers', null, $whereConditions)->results();
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
                    <span class="pull-right"><?php

                        switch ($bill_status) {
                            case "paid" :
                                $out = '<span class="label label-success">Paid</span> ';
                                break;
                            case "due" :
                                $out = "<span class='label label-danger'>Due</span>";
                                break;
                            case "canceled" :
                                $out = '<span class="label label-warning">Canceled</span> ';
                                break;
                            case "partial" :
                                $out = '<span class="label label-primary">Partial</span> ';
                                break;
                            default :
                                $out = '<span class="label label-info">Pending</span> ';
                                break;
                        }
                        echo 'Status: <span id="markp2">' . $out . '</span>';

                        ?></span>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-xs-10">
                <?php if ($bill_status != 'paid') {
                    echo '<span id="markp"><a data-invoice-id="' .
                        $row['tid'] . '" data-csd="' . $row['pmethod'] .
                        '" class="btn btn-success btn-sm mark-payment" title="Mark as Paid"><span class="icon-clipboard"></span>Mark as Paid </a>&nbsp; &nbsp;';
                    if ($bill_pm == 0 OR $bill_pm == 2) {
                        echo '<a data-invoice-id="' .
                            $row['tid'] . '" data-csd="' . $row['csd'] .
                            '" class="btn btn-primary btn-sm part-payment" title="Partial Payment"><span class="icon-drawer"></span>Partial Pay </a>&nbsp; &nbsp;';
                    }
                    echo '<a data-invoice-id="' .
                        $row['tid'] . '" data-csd="' . $row['csd'] .
                        '" data-type="invoice" class="btn btn-info btn-sm prm-invoice" title="Payment Reminder"><span class="icon-bullhorn"></span>Payment Reminder </a>&nbsp; </span>';
                }
                echo '
 

         <a href="view/invoice-view.php?id=' .
                    $row["tid"] . '" class="btn btn-success btn-sm"  title="Print/Save"><span class="icon-download2"></span> PDF/Print</a>
          
          <a href="index.php?rdp=edit-invoice&id=' .
                    $row["tid"] . '" class="btn btn-warning btn-sm" title="Edit"><span class="icon-pencil"></span>Edit Invoice</a> <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-primary btn-sm send-invoice" title="Email"><span class="icon-mail4"></span>Email </a> <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-info btn-sm send-sms" title="SMS"><span class="icon-star-empty"></span>SMS </a> &nbsp; '; ?>
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

                    ?></b>
                <br>

                <b>Invoice Date:</b> <?php

                echo dateformat($bill_date);

                ?><br>
                <b>Payment Due:</b> <?php

                echo dateformat($bill_due_date);

                ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <br>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>SGST </th>
                        <th>CGST </th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $query2 = "SELECT * FROM invoice_items WHERE tid = '$getID'";
                    $result2 = $db->pdoQuery($query2)->results();
                    foreach ($result2 as $rows) {
                        $item_product = $rows['product'];
                        $item_qty = $rows['qty'];
                        $item_price = $rows['price'];
                        $item_tax = $rows['discount'];
                        $item_taxr = $rows['trate'];
						$item_taxr2 = $rows['trate2'];
                        $item_subtotal = $rows['subtotal'];

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
                            href='index.php?rdp=checkout&id=<?php echo $getID ?>' class='btn btn-primary btn-lg'><span
                                class='icon-credit-card' aria-hidden='true'></span>Make Card Payment </a>
                <?php } elseif ($bill_pm == 4) {
                    echo '<img src="images/card/bankt.png" alt="Cash payment"><br><br>';
                } else {
                    echo '<img src="images/card/cashpay.png" alt="Cash payment"><br><br>';
                }
                if ($bill_pm == 2) {
                    echo " <p class='lead'>Partial Payments:</p><strong>Total Due Amount:</strong> " . $siteConfig['curr'], $bill_yog . "<br>";
                    echo "<strong>Partial Paid Amount: </strong>" . $siteConfig['curr'], $bill_rm . "<br>";
                    if ($bill_status != 'paid') {
                        echo "<strong>Due Amount:</strong> " . $siteConfig['curr'], number_format($bill_yog - $bill_rm, 2) . "";
                    }

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
        <?php if ($bill_pm == 2) { ?>
            <div class="row">
                <div class="col-md-8">
                    <p class="lead">Partial Payment Transactions</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Date</th>
                                <th>Amount</th>
                                <th>Note</th>
                            </tr>
                            <?php $query2 = "SELECT * FROM part_trans WHERE tid = '$getID'";
                            $result2 = $db->pdoQuery($query2)->results();
                            foreach ($result2 as $rows) {
                                $item_product = $rows['amount'];
                                $item_qty = $rows['note'];
                                $item_price = $rows['tdate'];
                                echo "<tr>
                        <td>$item_price</td>
						<td>$item_product</td>
                        <td>$item_qty</td>
                    </tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <!-- this row will not appear when printing -->
        <?php } ?>
    </div>
    <!-- /.content -->
</div>
<div id="send_invoice" class="modal fade">
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
<div id="send_sms" class="modal fade">
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
<div id="mark_payment" class="modal fade">
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
<div id="part_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Partial Payment Transaction Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4"><input type="text" id="amount" name="amount" class="form-control required"
                                                 placeholder="Amount"></div>
                    <div class="col-md-4"><input type="text" name="tnote" class="form-control"
                                                 placeholder="Payment Note"></div>
                     <div class="col-md-4"> <div class="input-group date mdate" id="tsn_date"> <input type="text" class="form-control required" name="tdate" value="<?php echo date('d-m-Y');  ?>" data-date-format="DD-MM-YYYY"/>
                    	<span class="input-group-addon"> <span class="icon-calendar"></span> </span> </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="partpay">Add</button>

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
