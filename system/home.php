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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "home.php")) {
    die("Internal Server Error!");
}
//home page
$today = date("Y-m-d");
$cmonth = date("m");
$cyear = date("Y");
$query = " SELECT tsn_date,SUM(total) as itotal FROM invoices GROUP BY DATE(tsn_date) ORDER BY DATE(tsn_date) DESC LIMIT 7";
$result = $db->pdoQuery($query)->results();

?>
<script>
    var lineChartData = {
        labels: [<?php

            foreach (array_reverse($result) as $row) {
                echo '"' . $row['tsn_date'] . '",';
            }

            ?>],
        datasets: [
            {
                label: "Paid Amount",
                fillColor: "rgba(1, 193, 66, .2)",
                strokeColor: "rgba(1, 193, 66, 1)",
                pointColor: "rgba(1, 193, 66, 1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [<?php

                    foreach (array_reverse($result) as $row) {
                        $ttl = $row['itotal'];
                        echo $ttl . ',';
                    }
                    ;

                    ?>]
            }


        ]
    };
    window.onload = function () {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });


    }
</script>
<!-- Info boxes -->
<div class="row">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><span class="icon-paste"></span></span>

            <div class="info-box-content">
                <span class="info-box-text">Invoices Today</span>
                <span class="info-box-number"><?php


                    $irs = $db->count('invoices', "DATE(tsn_date) ='$today'");
                    echo $irs;

                    ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><span class="icon-clipboard"></span></span>

            <div class="info-box-content">
                <span class="info-box-text">Sales Today</span>
                <span class="info-box-number"><?php


                    $isum = $db->sum('invoices', 'total', "tsn_date='$today'");


                    echo $siteConfig['curr'], amountFormat($isum);

                    ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><span class="icon-stack"></span></span>

            <div class="info-box-content">
                <span class="info-box-text">Invoices in Month</span>
                <span class="info-box-number"><?php

                    $irs = $db->count('invoices', "DATE(tsn_date) BETWEEN '$cyear-$cmonth-01' AND '$cyear-$cmonth-31'");
                    echo $irs;
                    ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><span class="icon-coin-dollar"></span></span>

            <div class="info-box-content">
                <span class="info-box-text">Sales in Month</span>
                <span class="info-box-number"><?php


                    $isum = $db->sum('invoices', 'total', "(DATE(tsn_date) BETWEEN '$cyear-$cmonth-01' AND '$cyear-$cmonth-31')");
                    echo $siteConfig['curr'], amountFormat($isum);

                    ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->


<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Reports</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i></button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="text-center">
                            <strong>Sales: Last 7 Active Days</strong>
                        </p>

                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas class="main-chart" id="canvas" height="200" width="600"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <p class="text-center">
                            <strong>Targets For <?php

                                $month = date('F, Y');
                                echo "$month"

                                ?></strong>
                        </p>

                        <?php

                        $month = date('Y-m');

                        $query2 = "SELECT SUM(CASE WHEN stat=1 THEN amount ELSE 0 END) AS winc, SUM(CASE WHEN stat=0 THEN amount ELSE 0 END) AS wexp FROM ac_balance WHERE (DATE(bdate) BETWEEN '$month-01' AND '$month-31') LIMIT 1;";

                        $grow = $db->select('goals')->results();
                        $srow = $db->pdoQuery($query2)->results();

                        $inc = $grow['income'];
                        $winc = $srow[0]['winc'];
                        $wexp = $srow[0]['wexp'];
                        $exp = $grow['expense'];
                        $sal = $grow['sales'];
                        $inv = $grow['invoices'];

                        echo '<div class="progress-group">
                    <span class="progress-text">Expected Income</span>
                    <span class="progress-number"><b>' . $siteConfig['curr'], $winc .
                            '</b>/' . $inc . '</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: ' .
                            $winc * 100 / $inc . '%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Expected Expenses</span>
                    <span class="progress-number"><b>' . $siteConfig['curr'], $wexp .
                            '</b>/' . $exp . '</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red" style="width: ' .
                            $wexp * 100 / $exp . '%"></div>
                    </div>
                  </div>
                    <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Expected Sales</span>
                    <span class="progress-number"><b>' . $siteConfig['curr'], $isum .
                            '</b>/' . $sal . '</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-yellow" style="width: ' .
                            $isum * 100 / $sal . '%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Expected Invoices</span>
                    <span class="progress-number"><b>' . $irs . '</b>/' . $inv .
                            '</span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: ' .
                            $irs * 100 / $inv . '%"></div>';

                        ?>
                    </div>
                </div>
                <!-- /.progress-group -->

                <!-- /.progress-group -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- ./box-body -->

    <!-- /.box-footer -->
</div>
<!-- /.box -->

<!-- /.col -->


<div class="row">
    <!-- Left col -->
    <div class="col-md-8">
        <!-- MAP & BOX PANE -->

        <!-- /.box -->
        <div class="row">

            <!-- /.col -->


            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- TABLE: LATEST ORDERS -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Latest Orders</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $query = "SELECT tid,status,csd
FROM invoices

ORDER BY tid DESC LIMIT 10";

                        $result = $db->pdoQuery($query)->results();
                        foreach ($result as $row) {
                            switch ($row['status']) {
                                case "paid" :
                                    $out = '<span class="label label-success">Paid</span> ';
                                    break;
                                case "due" :
                                    $out = '<span class="label label-danger">Due</span> ';
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
                            echo '<tr>
                    <td>' . $row["tid"] . ' </td>
                    <td>' . $out . '</td>
                    <td><a href="index.php?rdp=view-invoice&id=' . $row["tid"] .
                                '" class="btn btn-info btn-xs"><span class="icon-file-text2"></span>View</a> &nbsp; <a href="view/invoice-view.php?id=' . $row["tid"] .
                                '" class="btn btn-success btn-xs"><span class="icon-print"></span> Print</a>&nbsp; &nbsp;&nbsp; <a data-invoice-id="' .
                                $row['tid'] . '" data-csd="' . $row['csd'] .
                                '" class="btn btn-primary btn-xs send-invoice" title="Email"><span class="icon-mail4"></span>Email </a> &nbsp; <a data-invoice-id="' .
                                $row['tid'] . '" data-csd="' . $row['csd'] .
                                '" class="btn btn-info btn-xs send-sms" title="SMS"><span class="icon-star-empty"></span>SMS </a> &nbsp; <a href="index.php?rdp=edit-invoice&id=' .
                                $row["tid"] . '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil-square"></span> Edit</a>&nbsp; &nbsp;<a href="view/invoice-view.php?id=' .
                                $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                                $row['tid'] . '" class="btn btn-danger btn-xs delete-invoice"  title="Delete"><span class="icon-bin"></span></a>
     </td>
                  </tr>';
                        }

                        ?>


                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <a href="index.php?rdp=new-invoice" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
                <a href="index.php?rdp=invoices" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->

    <div class="col-md-4">
        <!-- Info Boxes Style 2 -->


        <!-- /.box -->

        <!-- PRODUCT LIST -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Products Stock Alert</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    <?php

                    $query = "SELECT pid,product_name,qty FROM products ORDER BY qty ASC LIMIT 10";

                    $result = $db->pdoQuery($query)->results();
                    foreach ($result as $row) {
                        echo '<li class="item">
    
    
 
    
    
    <a href="index.php?rdp=product&op=edit&id=' . $row["pid"] .
                            '" class="product-title" title="Update">' . $row['product_name'] .
                            '<span class="label label-danger pull-right">' . $row['qty'] . '</span></a>
  
</li>';
                    }

                    ?>

                </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
                <a href="index.php?rdp=product" class="uppercase">View All Products</a>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
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