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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "rec-reports.php")) {
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
    if ($user->id != $id) {
        if ($user->group_id > 1) {
            die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
        }
    }
    echo '<script type="text/javascript">
$(document).ready(function() {
    $("#slist2").DataTable( {
		 stateSave: true,
		 "processing": true,
        "serverSide": true,
        "ajax": "request/listinex.php?page=remployee&id=' . $id . '"
       
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
        <table id="slist2" class="table-responsive cell-border" cellspacing="0">
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
    <div id="rdelete_invoice" class="modal fade">
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
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="rdelete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <?php

} else {
if ($user->group_id > 1) {
    die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
}

?>
<div class="row">

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
                echo ' <div class="table-responsive"><table id="elist" class="table-responsive cell-border" cellspacing="0"><thead><tr>

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
				    <td><a href="index.php?rdp=rec-reports&op=employee&id=' . $row["id"] .
                        '" class="btn btn-success btn-xs"><span class="icon-list"></span></span>Track Sales </a>';

                    print '</td>
			    </tr>
		    ';


                }
                echo '</tr></tbody></table></div><br><div class="alert alert-success">Sales Team member can create, print the invoice and view invoice list, not rights to modify or delete an invoice.</div>
				<div class="alert alert-info">Manager has rights to Create, Edit, Delete invoices and complete access to Stock Management.</div>
				<div class="alert alert-danger">You are a SuperAdmin, have all rights including modification of company details, invoice settings.</div>';


                }

                ?>
            </div>
        </div>
    </div>
    <div><?php

        }


        function rprt()
        {
            global $user;
            if ($user->group_id > 1) {
                die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
            }
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
                            <span class="info-box-number"><a href="index.php?rdp=rec-reports&op=monthly">Sales
                                    Reports</a></span>

                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                    Last 12 Months Recurring Sales
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
                            <span class="info-box-number"><a href="index.php?rdp=rec-reports&op=employee">Employees</a></span>

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
                            <span class="info-box-number"><a href="index.php?rdp=rec-reports&op=customer">Customers</a></span>

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
                            <span class="info-box-text">Listed Services</span>
                            <span class="info-box-number"><?php

                                $rs = $db->count('services');
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
                        <div class="panel-heading">12 Months Recurring Sales Statistics
                            <form method="post" action="index.php?rdp=manager">
                                <input type="hidden" name="recupdate"><input type="submit"
                                                                             class="btn btn-success btn-sm"
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

        $query = "SELECT mnth,paid FROM rec_summary WHERE  id > ((SELECT 
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

        function reports()
        {

            global $user;
            if ($user->group_id > 1) {
                die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
            }
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
                                <input type="hidden" name="recupdate" value="true"><input type="submit"
                                                                                          class="btn btn-success btn-sm"
                                                                                          value="Refresh Reports">
                            </form>
                        </div>
                        <br><br>

                        <div class="panel-body tbl"><?php

                            global $db;
                            $query = "SELECT * FROM rec_summary ORDER BY id DESC";

                            $result = $db->pdoQuery($query)->results();

                            ?>
                            <div class="table-responsive">
                                <table id="mlist" class="table-responsive cell-border" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Year/Month</th>
                                        <th>Sales</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    foreach ($result as $row) {
                                        $month = $row["mnth"];
                                        $year = $row["yer"];
                                        $sales = amountFormat($row["paid"]);

                                        echo "<tr><td>$year/$month</td><td>$sales</td>";
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
        global $user;
        if ($user->group_id > 1) {
            die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
        }
        echo "<script type='text/javascript'>
$(document).ready(function() {
    $('#ilist').DataTable( {
        stateSave: true
    } );
} );
</script>";

        global $db, $siteConfig;
        if ($id > 0) {
            $whereConditions = array('id' => $id);
            $result1 = $db->select('reg_customers', null, $whereConditions)->results();

            $user = $result1['name'];
            $fuser = $result1['email'];
            $luser = $result1['phone'];
            $ruser = $result1['rdate'];

            $query = "SELECT tid,tsn_date,status,csd,active FROM rec_invoices WHERE csd='$id' ORDER BY tid DESC";

            $result = $db->pdoQuery($query)->results();

            if ($result) {
                $tcsum = $db->sum('rec_invoices', 'ramm', "csd='$id'");

                echo '<div class="row">

	<div class="col-lg-12">

		<div id="notify" class="alert alert-success" style="display:none;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<div class="message"></div>
		</div>
	
		<div class="panel panel-default">
			<div class="panel-heading"><h4>Invoices List</h4></div>
			<div class="panel-body tbl">';
                echo "<p>Customer : $user <br>Contact : $luser ($fuser)<br>Registered on: $ruser</p><p><strong>Summary</strong><br>Paid Amount: <strong>" . $siteConfig['curr'] . " $tcsum</strong><br></p>";
                echo '<br> <div class="table-responsive"><table id="ilist" class="table-responsive cell-border" cellspacing="0"><thead><tr>';

                echo '<th>Invoice</h4></th><th>Issue Date</h4></th>
				
				<th>Status</h4></th>
				
				<th>Options</h4></th>

			  </tr></thead><tbody>';

                foreach ($result as $row) {

                    echo '
				<tr>
					<td>' . $row["tid"] . '</td>';

                    echo '<td>' . $row["tsn_date"] . '</td><td> ';

                    if ($row['active'] == 0) {

                        $out = '<span class="label label-primary">Recurring</span> ';
                    } else {


                        $out = '<span class="label label-warning">Canceled</span> ';


                    }

                    echo $out . '</td><td><a href="index.php?rdp=viewinvoice&id=' . $row["tid"] .
                        '" class="btn btn-info btn-xs"><span class="icon-file-text2"></span>View</a> &nbsp; <a href="view/invoice-rec.php?id=' . $row["tid"] .
                        '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a> &nbsp; <a href="index.php?rdp=recurring&op=edit&id=' . $row["tid"] .
                        '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp; <a href="view/invoice-rec.php?id=' .
                        $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a>&nbsp; &nbsp;<a data-invoice-id="' .
                        $row['tid'] . '" class="btn btn-danger btn-xs rdelete-invoice"  title="Delete"><span class="icon-bin"></span></a></td>
			    </tr>
			';

                }

                echo '</tr></tbody></table></div>';

            } else {

                echo '<div class="panel panel-default"><div class="alert alert-danger"><strong>Oops!! There are no purchase to display by this customer.</strong></div></div>';

            }
            ?>
            <div id="rdelete_invoice" class="modal fade">
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
                            <button type="button" data-dismiss="modal" class="btn btn-primary" id="rdelete">Delete
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
        } else {

        ?>
        <div class="row">

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
                            "ajax": "request/listcustomer.php?op=rec"
                        });
                    });</script>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Customer List</h4>
                    </div>
                    <div class="panel-body form-group form-group-sm tbl">
                        <div class="table-responsive">
                            <table id="clist" class="table-responsive cell-border" cellspacing="0">
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

                        $('#delete_customer').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).one('click', '#delete', function () {
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
                                <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete
                                </button>
                                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php

                }

                ?>
                <script src="lib/js/rec-control.js"></script>