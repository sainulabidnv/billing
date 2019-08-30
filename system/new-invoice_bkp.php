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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "new-invoice.php")) {
    die("Internal Server Error!");
}
//create new invoice
function getSerial()
{
    global $db;
    $row = $db->select('invoices', null, null, 'ORDER BY tid DESC')->results();
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

<form method="post" id="create_invoice" class="fmargin">
    <input type="hidden" name="act" value="create_invoice">

    <div class="row ">

        <div class="panel panel-primary ">
            <div class="panel-body chead">
                <div class="col-md-6">
                    <h3>New Invoice</h3></div>


                <div class="col-md-2">
                    <div class="form-group ">
                        <small>Invoice Date</small>
                        <div class="input-group date mdate" id="tsn_date">
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
                        <small>Invoice Due Date</small>
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
                    <small>Invoice Number</small>
                    <div class="input-group ">
    <span class="input-group-addon">#<?php

        echo $siteConfig['pref']

        ?></span>
                        <input type="number" name="invoice_id" id="invoice_id" class="form-control required"
                               placeholder="Invoice Number" aria-describedby="sizing-addon1" value="<?php

                        getSerial();

                        ?>">
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="row bg-light-blue color-palette margin-t padding-f">


        <div class="ol-xs-12 col-md-5 col-lg-4 mol-4"><a href="#" class="btn btn-warning btn-xs add-row"><span
                        class="icon-plus"></span><strong>Add empty</strong></a> PRODUCTS ROW
        </div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">QTY</div>
        <div class="col-xs-12 col-md-6 col-lg-2 mol-2">UNIT PRICE</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">SGST</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1">CGST</div>
        <div class="col-xs-12 col-md-3 col-lg-1 mol-1" style="border-right:0">SUBTOTAL</div>

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
                    Customer Information


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
                               placeholder="Tax ID" tabindex="8">
                    </div>
                    <div class="form-group no-margin-bottom">
                        <a href="#" class="grahk-select btn btn-success">Select existing customer</a>&nbsp; <input
                                type="button" id="clear-form" class="btn btn-danger dropdown-toggle" value="Clear"/>&nbsp;
                        &nbsp; <span class="crepeat"><input type="checkbox" name="crepeat" value="yes"> Add to registered customer</span>
                    </div>
                </div>

            </div>


        </div>
        <div class=" col-md-6">

            <div class="panel panel-primary">
                <div class="panel-heading panel-summary"><span class="icon-cart"></span>
                    Summary


                </div>
                <div id="bill_totl" class="panel padding-b">
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
                                       placeholder="Discount" value="0"><span class="input-group-addon">%</span></div>
                        </div>
                    </div>
                    <div class="row margin-bottom padding">
                        <div class="col-xs-8 text-right">
                            <strong>Total Discount:</strong>
                        </div>
                        <div class="col-xs-4 text-right">

                            <?php

                            echo $siteConfig['curr'];

                            ?><span class="bill-fdisc">0.00</span>
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
                                <option value="paid">Paid</option>
                                <option value="due" selected>Due</option>
                                <option value="partial">Partial</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 text-right "><br>
                            <strong>Payment Mode:</strong>
                        </div>
                        <div class="col-xs-4 text-right margin-t">
                            <select name="payment" class="form-control">

                                <option value="0">Cash Payment</option>
                                <option value="1">Card Payment</option>
                                <option value="4">Bank/Cheque Payment</option>

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
        <div class="col-md-6 padding-f">
            <div class="col-xs-8">

            </div>
            <div class="col-xs-4">
                <input type="submit" id="act-newinvoive" class="btn btn-success float-right" value="Create Invoice"
                       data-loading-text="Creating...">
            </div>
        </div>


    </div>
</form>
<small class="float-right"><strong>Creator Employee ID <em><?php

            echo $user->username

            ?></em></strong></small>
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
    <div class="modal-dialog lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select an existing customer</h4>
            </div>
            <div class="modal-body">
                <?php

                cstPL();

                ?>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
        </div>
    </div>
</div>
