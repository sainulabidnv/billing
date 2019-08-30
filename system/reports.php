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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "reports.php")) {
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
    case "monthly":
        reports();
        break;
    case "employee":
        invbyemp($id);
        break;
    case "customer":
        invlist($id);
        break;
    default:
        rprt();
        break;
}

function invbyemp($id)
{
    global $db, $user;

    if ($id > 0) {

        $uiid = $user->id;
        if ($uiid != $id) {

            if ($user->group_id > 1) {

                die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
            }
        } else {
            echo '<script type="text/javascript">
$(document).ready(function() {
    $("#slist2").DataTable( {
		 stateSave: true,
		 "processing": true,
        "serverSide": true,
        "ajax": "request/listinex.php?page=employee&id=' . $id . '"
       
    } );
} );</script>';

            $whereConditions = array('id' => $id);
            $result1 = $db->select('employee', null, $whereConditions)->results();

            $user = $result1['username'];
            $fuser = $result1['first_name'];
            $luser = $result1['last_name'];
            echo '<div class="panel panel-default">	<div class="panel-heading">';
            echo "<h4>Invoices by $fuser $luser ($user)</h4>";
            echo '</div><div class="panel-body form-group form-group-sm tbl">';

            ?>
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="table-responsive">
                <table id="slist2" class="table cell-border" cellspacing="0">
                    <thead>
                    <tr>

                        <th>Invoice</h4></th>

                        <th>Issue Date</h4></th>

                        <th>Status</h4></th>

                        <th>Options</h4></th>

                    </tr>
                    </thead>
                </table>
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
                            <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete
                            </button>
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
            </div>
            <?php

        }
        if ($uiid != $id) {
            echo '<script type="text/javascript">
$(document).ready(function() {
    $("#slist3").DataTable( {
		 stateSave: true,
		 "processing": true,
        "serverSide": true,
        "ajax": "request/listinex.php?page=employee&id=' . $id . '"
       
    } );
} );</script>';

            $whereConditions = array('id' => $id);
            $result1 = $db->select('employee', null, $whereConditions)->results();

            $user = $result1['username'];
            $fuser = $result1['first_name'];
            $luser = $result1['last_name'];
            echo '<div class="panel panel-default">	<div class="panel-heading">';
            echo "<h4>Invoices by $fuser $luser ($user)</h4>";
            echo '</div><div class="panel-body form-group form-group-sm tbl">';

            ?>
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="table-responsive">
                <table id="slist3" class="table cell-border" cellspacing="0">
                    <thead>
                    <tr>

                        <th>Invoice</h4></th>

                        <th>Issue Date</h4></th>

                        <th>Status</h4></th>

                        <th>Options</h4></th>

                    </tr>
                    </thead>
                </table>
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
                            <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete
                            </button>
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
            </div> <?php

        }
    } else {


        ?><div class="row">

        <div class="col-xs-12">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <script type="text/javascript">

                $(document).ready(function () {
                    $('#elist').DataTable({
                        stateSave: true
                    });
                });
            </script>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Employee List</h4>
                </div>
                <div class="panel-body form-group form-group-sm tbl">
                    <?php

                    $query = "SELECT * FROM employee WHERE activated=1 ORDER BY username ASC";


                    $results = $db->pdoQuery($query)->results();
                    echo ' <div class="table-responsive"><table id="elist" class="table cell-border" cellspacing="0"><thead><tr>

				<th>Name</h4></th>
				<th>Username</h4></th>
				<th>Email</h4></th>
				<th>Role</h4></th>
				<th>Settings</h4></th>

			  </tr></thead><tbody>';
                    foreach ($results as $row) {

                        $role = $row["group_id"];
                        switch ($role) {
                            case 1:
                                $role = "Owner";
                                break;

                            case 2:
                                $role = "Manager";
                                break;
                            case 3:
                                $role = "Sales Team";
                                break;
                        }

                        echo '
			    <tr>
			    	<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
					<td>' . $row["username"] . '</td>
				    <td>' . $row["email"] . '</td>
				    <td>' . $role . '</td>
				    <td><a href="index.php?rdp=reports&op=employee&id=' . $row["id"] .
                            '" class="btn btn-success btn-xs"><span class="icon-list"></span></span>Track Sales </a>';

                        print '</td>
			    </tr>
		    ';


                    }
                    echo '</tr></tbody></table></div><br><div class="alert alert-success">Sales Team member can create, print the invoice and view invoice list, not rights to modify or delete an invoice.</div>
				<div class="alert alert-info">Manager has rights to Create, Edit, Delete invoices and complete access to Stock Management.</div>
				<div class="alert alert-danger">You are a SuperAdmin, have all rights including modification of company details, invoice settings.</div>';


                    ?>
                </div>
            </div>
        </div>
        <div><?php
    }

}


function rprt()
{
    global $user;
    if ($user->group_id > 1) {
        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
    } else {
        global $db;
        $today = date("Y-m-d");
        $cmonth = date("m");
        $cyear = date("Y");

        ?>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><span class="icon-file-text2"></span></span>

                    <div class="info-box-content">
                        <span class="info-box-text">&nbsp;</span>
                        <span class="info-box-number"><a href="index.php?rdp=reports&op=monthly">Sales
                                Reports </a></span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                    Last 12 Months
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><span class="icon-profile"></span></span>

                    <div class="info-box-content">
                        <span class="info-box-text">&nbsp;</span>
                        <span class="info-box-number"><a href="index.php?rdp=reports&op=employee">Employees</a></span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                    Sales Reports
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><span class="icon-circle-right"></span></span>

                    <div class="info-box-content">
                        <span class="info-box-text">&nbsp;</span>
                        <span class="info-box-number"><a href="index.php?rdp=reports&op=customer">Customers</a></span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                    Track Sales
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- /.col -->
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><span class="ich icon-cubes"></span></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Listed Products</span>
                        <span class="info-box-number"><?php

                            $rs = $db->count('products');
                            echo $rs;

                            ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><span class="ich icon-users"></span></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Team Members</span>
                        <span class="info-box-number"><?php

                            $rs = $db->count('employee', 'activated=1');
                            echo $rs;

                            ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><span class="ich icon-user-tie"></span></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Registered Customer</span>
                        <span class="info-box-number"><?php

                            $rs = $db->count('reg_customers');
                            echo $rs;

                            ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- /.col -->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">12 Months Statistics
                        <form method="post" action="index.php?rdp=manager">
                            <input type="hidden" name="update"><input type="submit" class="btn btn-success btn-sm"
                                                                      value="Refresh Statistics">
                        </form>
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <canvas class="main-chart" id="canvas" height="200" width="600"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php

    $query = "SELECT mnth,paid,due,unpaid FROM summary WHERE  id > ((SELECT 
            MAX(id)
        FROM
            summary) - 12) ORDER BY id ASC LIMIT 12";

    $result = $db->pdoQuery($query)->results();

    ?>
        <script>

            var lineChartData = {
                labels: [<?php

                    foreach ($result as $row) {
                        echo '"' . $row['mnth'] . '",';
                    }
                    ;

                    ?>],
                datasets: [
                    {
                        label: "Paid Amount",
                        fillColor: "rgba(220,220,220,0.1)",
                        strokeColor: "rgba(1, 193, 66, 1)",
                        pointColor: "rgba(1, 193, 66, 1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [<?php

                            foreach ($result as $row) {
                                echo '"' . $row['paid'] . '",';
                            }
                            ;

                            ?>]
                    },
                    {
                        label: "UnPaid Amount",
                        fillColor: "rgba(255, 0, 0, 0.1)",
                        strokeColor: "rgba(255, 0, 66, 1)",
                        pointColor: "rgba(255, 0, 66, 1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [<?php

                            foreach ($result as $row) {
                                echo '"' . $row['due'] . '",';
                            }
                            ;

                            ?>]
                    },
                    {
                        label: "UnPaid Invoices",
                        fillColor: "rgba(0, 55, 255, 0.1)",
                        strokeColor: "rgba(0, 0, 255, 1)",
                        pointColor: "rgba(0, 0, 255, 1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: [<?php

                            foreach ($result as $row) {
                                echo '"' . $row['unpaid'] . '",';
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
        <?php

    }
}

function reports()
{

    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#mlist').DataTable({
                stateSave: true
            });
        });
    </script>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Monthly Reports
                    <form method="post" action="index.php?rdp=manager">
                        <input type="hidden" name="update" value="true"><input type="submit"
                                                                               class="btn btn-success btn-sm"
                                                                               value="Refresh Reports">
                    </form>
                </div>
                <br><br>

                <div class="panel-body tbl"><?php

                    global $db;
                    $query = "SELECT * FROM summary ORDER BY id DESC";

                    $result = $db->pdoQuery($query)->results();

                    ?>
                    <div class="table-responsive">
                        <table id="mlist" class="table cell-border" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Year/Month</th>
                                <th>Sales</th>
                                <th>Pending Amount</th>
                                <th>Unpaid Invoices</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($result as $row) {
                                $month = $row["mnth"];
                                $year = $row["yer"];
                                $sales = amountFormat($row["paid"]);
                                $due = amountFormat($row["due"]);
                                $unpaid = $row["unpaid"];
                                echo "<tr><td>$year/$month</td><td>$sales</td><td>$due</td><td>$unpaid</td>";
                            }

                            ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><?php

}


function invlist($id)
{

    echo "<script type='text/javascript'>
$(document).ready(function() {
    $('#ilist').DataTable( {
        stateSave: true
    } );
} );
</script>";

    global $db, $siteConfig;
    $itotaldue = 0;
    $qtotaldue = 0;
    if ($id > 0) {
        $whereConditions = array('id' => $id);
        $result1 = $db->select('reg_customers', null, $whereConditions)->results();

        $user = $result1['name'];
        $fuser = $result1['email'];
        $luser = $result1['phone'];
        $ruser = $result1['rdate'];


        echo '
		<div id="customPayment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Custom Payment</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4"><input type="text" id="amount" name="amount" class="form-control required"
                                                 placeholder="Amount"></div>
                    <div class="col-md-4"><input type="text" name="tnote" class="form-control"
                                                 placeholder="Payment Note"></div>
					<div class="col-md-4"> <div class="input-group date mdate" id="tsn_date"> <input type="text" class="form-control required" name="tdate" value="'. date('d-m-Y').'" data-date-format="DD-MM-YYYY"/>
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
		
		<div class="row">

	<div class="col-lg-12">
<div style="padding:10px;"> <a data-csd="'.$id.'" class="btn btn-success customPayment" title="Partial Payment"><span class="icon-drawer"></span>Pay Now </a> &nbsp; <a href="index.php?rdp=payments&id=' . $id . '" class="btn btn-info "><span class="icon-file-text2"></span>View Payments</a></div>
		<div id="notify" class="alert alert-success" style="display:none;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<div class="message"></div>
		</div>
	
		<div class="panel panel-default">
			<div class="panel-heading"><h4>Invoices List</h4></div>
			<div class="panel-body tbl">';
        echo "<p>Customer : $user <br>Contact : $luser ($fuser)<br>Registered on: $ruser<hr>";

        $query = "SELECT tid,tsn_date,status,csd,total FROM invoices WHERE csd='$id' ORDER BY tid DESC";

        $result = $db->pdoQuery($query)->results();

        if ($result) {

            $tcsum = $db->sum('invoices', 'total', "csd='$id' AND status='paid'");
            $pcsum = $db->sum('invoices', 'ramm', "csd='$id' AND status='partial'");
            $dcsum = $db->sum('invoices', 'total', "csd='$id' AND status='due'");
            $dpcsum = $db->sum('invoices', 'total', "csd='$id' AND status='partial'");
            $totalpaid = $tcsum + $pcsum;
            $totaldue = $dcsum + $dpcsum;
            $fsum = $tcsum + $totaldue;
            $itotaldue = $totaldue - $pcsum;

            echo "<div class='col-md-4'> Summary<br>Total Purchases: <strong>" . $siteConfig['curr'] . " $fsum</strong><br>Paid Amount: <strong>" . $siteConfig['curr'] . " $totalpaid</strong><br>Due Amount: <strong>" . $siteConfig['curr'] . " $itotaldue</strong></p></div>";
            ?>
            <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <form method="get" action="view/payment-view.php">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required" name="f" value="<?php echo date('d-m-Y',strtotime("-1 month")); ?>" data-date-format="<?php echo $siteConfig['dformat2']; ?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="t" value="<?php echo date('d-m-Y'); ?>" data-date-format="<?php echo $siteConfig['dformat2']; ?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group" >
                                        <select name="d"><option value="0"> View</option> <option value="1"> download</option> </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                            	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary "> Report </button>
                            </div>
                            <div class="clearfix"></div>
                            </form>
                		</div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr />
            <?php
			echo '<br> <div class="table-responsive"><table id="ilist" class="table cell-border" cellspacing="0"><thead><tr>';
			

            echo '<th>Invoice</h4></th><th>Issue Date</h4></th><th>Total</h4></th>
				
				<th>Status</h4></th>
				
				<th>Options</h4></th>

			  </tr></thead><tbody>';

            foreach ($result as $row) {

                echo '
				<tr>
					<td>' . $row["tid"] . '</td>';

                echo '<td>' . $row["tsn_date"] . '</td>'; 
				echo '<td>' . $row["total"] . '</td><td> ';

                switch ($row['status']) {
                    case "paid" :
                        $out = '<span class="label label-success">Paid</span> ';
                        break;
                    case "due" :
                        $out = '<span class="label label-danger">Due</span> ';
                        break;
					case "partial" :
                        $out = '<span class="label label-warning">Partial</span> ';
                        break;
                    case "canceled" :
                        $out = '<span class="label label-info">Canceled</span> ';
                        break;
                    default :
                        $out = '<span class="label label-info">Pending</span> ';
                        break;
                }

                echo $out . '</td><td><a href="index.php?rdp=view-invoice&id=' . $row["tid"] .
                    '" class="btn btn-info btn-xs"><span class="icon-file-text2"></span>View</a> &nbsp; <a href="view/invoice-view.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-primary btn-xs send-invoice" title="Email"><span class="icon-mail4"></span>Email </a> &nbsp; <a data-invoice-id="' .
                    $row['tid'] . '" data-csd="' . $row['csd'] .
                    '" class="btn btn-info btn-xs send-sms" title="SMS"><span class="icon-star-empty"></span>SMS </a> &nbsp; <a href="index.php?rdp=edit-invoice&id=' . $row["tid"] .
                    '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp; <a href="view/invoice-view.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                    $row['tid'] . '" class="btn btn-danger btn-xs delete-invoice"  title="Delete"><span class="icon-bin"></span></a></td>
			    </tr>
			';

            }

            echo '</tr></tbody></table></div>';

        } else {

            echo '<div class="panel panel-default"><div class="alert alert-danger"><strong>Oops!! There are no purchase to display by this customer.</strong></div></div>';

        }
        echo '</div></div>';

        ?>
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
        </div>
        <?php
    } else {

        ?><div class="row">

        <div class="col-xs-12">
        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#clist').DataTable({
                    stateSave: true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "request/listcustomer.php"
                });
            });</script>


        <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Customer List</h4>
        </div>
        <div class="panel-body form-group form-group-sm tbl">
        <div class="table-responsive">
            <table id="clist" class="table cell-border" cellspacing="0">
                <thead>
                <tr>

                    <th>Name</th>
                    <th>Address</h4></th>
                    <th>Phone</h4></th>
                    <th>Settings</h4></th>

                </tr>
                </thead>
            </table>
        </div>
        <?php


    }

    ?>
    </div>
    </div>
    </div>
    <div>
        <script type="text/javascript">
            $(document).on('click', ".delete-customer", function (e) {
                e.preventDefault();

                var userId = 'act=delete_customer&delete=' + $(this).attr('data-grahk-id');
                var user = $(this);

                $('#delete_customer').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                    deleteCustomer(userId);
                    $(user).closest('tr').remove();
                });
            });
            function deleteCustomer(userId) {

                jQuery.ajax({

                    url: 'request/cst.php',
                    type: 'POST',
                    data: userId,
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
        <div id="delete_customer" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Customer</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this customer?</p>
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