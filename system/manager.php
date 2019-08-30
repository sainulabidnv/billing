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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "manager.php")) {
    die("Internal Server Error!");
}
global $db;
if ($user->group_id > 2) {
    die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
}
if (isset($_POST['update']) == 'true') {


    $wmonth = 2678400;
    $month = date('m', time() - $wmonth);
    $nextmonth = date('F', strtotime("+1 month"));
    $year = date('Y', time() - $wmonth);
    $monthName = date("F", mktime(0, 0, 0, $month, 10));
    $aWhere = array('mnth' => $monthName, 'yer' => $year);
    $raj = $db->delete('summary', $aWhere);
    $isum = 0;
    $isum = $db->sum('invoices', 'total', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='paid'");
    $dsum = 0;
    $dsum = $db->sum('invoices', 'total', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='due'");
    sleep(1);
    $dcoun = 0;
    $dcoun = $db->count('invoices', "(DATE(tsn_date) BETWEEN '$year-$month-01' AND '$year-$month-31') AND status='due'");

    $dataArray = array('mnth' => $monthName, 'yer' => $year, 'paid' => intval($isum), 'due' => intval($dsum), 'unpaid' => $dcoun);

    $bill_csd = $db->insert('summary', $dataArray)->getLastInsertId();

    echo "<div class='alert alert-success text-center'><h4>Statistics Updated</h4> for <strong>$monthName, $year</strong> You do need to repeat this action until <strong> 1st week of $nextmonth</strong></div>";
    echo '<div class="row">

    <div class="col-lg-12">
<br>
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
        </div>
    
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Statistics Update for 12 Months</h4>
            </div>
            <div class="panel-body"><p>This is a special feature where business owner can regenerate last 12&#039;s months sales data. This action is applicable only,if you make some changes in more than 1 month old invoices data. Additionally, in case of large amount of transactions in last 12 months, It may take some time to get data. It fully depends on no. of transactions in last 12 months and your server processing power.</p><div id="upi" class="myup"><h4>Are you sure to refresh last 12 months sales reports ?</h4><a class="btn btn-danger btn-lg update_reports"  title="Regenerate Reports" data-loading-text="Please wait">Regenerate Reports</a></div><div id="upir" style="display:none">
                <div id="notify" class="alert alert-info" >
            
            <div class="message">Please wait it may take more than 12 seconds...</div>
        </div>
            </div></div></div>';

    ?>


    <script type="text/javascript">
        $(document).on('click', ".update_reports", function (e) {
            e.preventDefault();

            var upRq = 'act=superupdate';


            $('#update_reports').modal({backdrop: 'static', keyboard: false}).one('click', '#superupdate', function () {
                updateRepo(upRq);
                $("#upi").hide();
                $("#upir").show();
            });
        });
        function updateRepo(upRq) {

            jQuery.ajax({

                url: 'request/updatereports.php',
                type: 'POST',
                data: upRq,
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

                    $("#notify").addClass("alert-success").fadeIn();
                    $("#notify .message").html("<strong>Success:</strong> Last 12 months reports regenerated.");
                    $("html, body").scrollTop($("body").offset().top);
                    $("#upir").hide();

                }
            });

        }</script>
    <div id="update_reports" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update last 12 months reports</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update last 12 months reports?</p>

                    <p>It will take at least 12 or more seconds. Please do not close browser during update process.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="superupdate">Yes</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <?php

}


if (isset($_POST['recupdate']) == 'true') {


    $wmonth = 2678400;
    $month = date('m', time() - $wmonth);
    $nextmonth = date('F', strtotime("+1 month"));
    $year = date('Y', time() - $wmonth);
    $monthName = date("F", mktime(0, 0, 0, $month, 10));
    $aWhere = array('mnth' => $monthName, 'yer' => $year);
    $raj = $db->delete('rec_summary', $aWhere);
    $isum = $db->sum('rec_part_trans', 'amount', "(DATE(tdate) BETWEEN '$year-$month-01' AND '$year-$month-31') ");


    $dcoun = 0;


    $dataArray = array('mnth' => $monthName, 'yer' => $year, 'paid' => intval($isum));

    $bill_csd = $db->insert('rec_summary', $dataArray)->getLastInsertId();

    echo "<div class='alert alert-success text-center'><h4>Statistics Updated</h4> for <strong>$monthName, $year</strong> You do need to repeat this action until <strong> 1st week of $nextmonth</strong></div>";
    echo '<div class="row">

    <div class="col-lg-12">
<br>
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <div class="message"></div>
        </div>
    
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Statistics Update for 12 Months</h4>
            </div>
            <div class="panel-body"><p>This is a special feature where business owner can regenerate last 12&#039;s months <strong>recurring sales</strong> data. This action is applicable only,if you make some changes in more than 1 month old invoices data. Additionally, in case of large amount of transactions in last 12 months, It may take some time to get data. It fully depends on no. of transactions in last 12 months and your server processing power.</p><div id="upi" class="myup"><h4>Are you sure to refresh last 12 months sales reports ?</h4><a class="btn btn-danger btn-lg update_reports"  title="Regenerate Reports" data-loading-text="Please wait">Regenerate Reports</a></div><div id="upir" style="display:none">
                <div id="notify" class="alert alert-info" >
            
            <div class="message">Please wait it may take more than 12 seconds...</div>
        </div>
            </div></div></div>';

    ?>


    <script type="text/javascript">
        $(document).on('click', ".update_reports", function (e) {
            e.preventDefault();

            var upRq = 'act=recsuperupdate';


            $('#update_reports').modal({backdrop: 'static', keyboard: false}).one('click', '#superupdate', function () {
                updateRepo(upRq);
                $("#upi").hide();
                $("#upir").show();
            });
        });
        function updateRepo(upRq) {

            jQuery.ajax({

                url: 'request/updatereports.php',
                type: 'POST',
                data: upRq,
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

                    $("#notify").addClass("alert-success").fadeIn();
                    $("#notify .message").html("<strong>Success:</strong> Last 12 months reports regenerated.");
                    $("html, body").scrollTop($("body").offset().top);
                    $("#upir").hide();

                }
            });

        }</script>
    <div id="update_reports" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Update last 12 months reports</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update last 12 months reports?</p>

                    <p>It will take at least 12 or more seconds. Please do not close browser during update process.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="superupdate">Yes</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <?php

}


if (isset($_POST['srn'])) {
    $srn = $_POST['srn'];

    ?>
    <div class="row">

    <div class="col-lg-12">

        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Manage Invoices</h4>
            </div>

            <div class="panel-body tbl">

                <?php

                $query = "SELECT tid,tsn_date,status FROM invoices WHERE tid='$srn'";

                $whereConditions = array('tid' => $srn);
                $row = $db->select('invoices', array('tid,tsn_date,status'), $whereConditions)->results();

                if ($row) {

                    echo ' <div class="table-responsive"><table id="manage" class="table cell-border" cellspacing="0"><th>Invoice #</th>
                                <th>Date</th>
                                <th >Status</th>
                                 <th>Settings</th>

              </tr></thead><tbody>';


                    echo '
                <tr>
                    <td>' . $row["tid"] . ' <a href="view/invoice-view.php?id=' . $row["tid"] .
                        '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a></td>';
                    echo '<td>' . $row["tsn_date"] . '</td><td> ';

                    if ($row['status'] == "due") {
                        echo '<span class="label label-danger">Due</span> ';
                    } elseif ($row['status'] == "paid") {
                        echo '<span class="label label-success">Paid</span> ';
                    }

                    echo '
                    </td><td><a href="index.php?rdp=edit-invoice&id=' . $row["tid"] .
                        '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/invoice-view.php?id=' .
                        $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                        $row['tid'] . '" class="btn btn-danger btn-xs delete-invoice"  title="Delete"><span class="icon-bin"></span></a></td>
                </tr>
            ';


                    echo '</tr></tbody></table></div>';

                } else {

                    echo "<p>There is no invoice to display.</p>";

                }

                ?>
            </div>
        </div>
    </div>
    <div>
        <script type="text/javascript">
            $(document).on('click', ".delete-invoice", function (e) {
                e.preventDefault();

                var billNo = 'act=delete_invoice&delete=' + $(this).attr('data-invoice-id');
                var invoice = $(this);

                $('#delete_invoice').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                    deleteInvoice(billNo);
                    $(invoice).closest('tr').remove();
                });
            });
            function deleteInvoice(billNo) {

                jQuery.ajax({

                    url: 'request/cst.php',
                    type: 'POST',
                    data: billNo,
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
                        $("html, body").scrollTop($("body").offset().top);

                    }
                });

            }</script>
        <div id="delete_invoice" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Invoice</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this invoice?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    <?php

}

?>