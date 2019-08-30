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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "vendor.php")) {
    die("Internal Server Error!");
}
//customer management
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

switch ($op) {
    case "add":
        add();
        break;
    case "edit":
        editc($id);
        break;
    case "reports":
        rcplist($id);
        break;
    default:
        clist();
        break;
}
function add()
{

    ?>

    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <div class="message"></div>
    </div>

    <form method="post" id="create_customer">
        <input type="hidden" name="act" value="create_vendor">

        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>New Vendor/Supplier Details</h4>
                        <div class="clear"></div>
                    </div>
                    <div class="panel-body form-group form-group-sm">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Vendor Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Vendor Name"
                                               class="form-control margin-bottom  required" name="grahak_name"
                                               id="grahak_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom required"
                                               name="grahak_adrs1" id="grahak_adrs1" placeholder="Address">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Address Line 2</label>
                                    <div class="col-sm-10">

                                        <input type="text" class="form-control  margin-bottom required"
                                               name="grahak_adrs2" id="grahak_adrs2"
                                               placeholder="City, Country, Postal Code">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Contact Number</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom required"
                                               name="grahak_phone" id="grahak_phone" placeholder="Phone number">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom" name="grahak_email"
                                               id="grahak_email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">TAX ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom" name="grahak_tax"
                                               id="grahak_tax" placeholder="TAX ID">
                                    </div>
                                </div>
                                <div class="form-group ">

                                    <div class="col-sm-5 margin-bottom">
                                        <input type="submit" id="action_create_customer"
                                               class="btn btn-success float-left" value="Create Vendor"
                                               data-loading-text="Creating...">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

    </form><?php

}

function editc($id)
{
    global $db;

    $whereConditions = array('id' => $id);
    $row = $db->select('reg_vendors', null, $whereConditions)->results();
    if ($row) {


        $grahak_name = $row['name'];
        $grahak_adrs1 = $row['address1'];
        $grahak_adrs2 = $row['address2'];
        $grahak_phone = $row['phone'];
        $grahak_email = $row['email'];
        $grahak_tax = $row['taxid'];


    } else {
        die();
    }

    ?>
    <script type="text/javascript">
        $(document).on('click', "#action_update_vendor", function (e) {
            e.preventDefault();
            updateGrahk();
        });
        function updateGrahk() {

            var $btn = $("#action_update_vendor").button("loading");

            jQuery.ajax({

                url: 'request/e_control.php',
                type: 'POST',
                data: $("#update_vendor").serialize(),
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
    <form method="post" id="update_vendor">
        <input type="hidden" name="act" value="update_vendor">
        <input type="hidden" name="id" value="<?php

        echo $id;

        ?>">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Editing Vendor (<?php

                            echo $grahak_name;

                            ?>)</h4>
                        <div class="clear"></div>
                    </div>
                    <div class="panel-body form-group form-group-sm">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Vendor Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Vendor Name"
                                               class="form-control margin-bottom  required" name="grahak_name"
                                               id="grahak_name" value="<?php

                                        echo $grahak_name;

                                        ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom required"
                                               name="grahak_adrs1" id="grahak_adrs1" placeholder="Address" value="<?php

                                        echo $grahak_adrs1;

                                        ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Address Line 2</label>
                                    <div class="col-sm-10">

                                        <input type="text" class="form-control  margin-bottom required"
                                               name="grahak_adrs2" id="grahak_adrs2"
                                               placeholder="City, Country, Postal Code" value="<?php

                                        echo $grahak_adrs2;

                                        ?>">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Contact Number</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom required"
                                               name="grahak_phone" id="grahak_phone" placeholder="Phone number"
                                               value="<?php

                                               echo $grahak_phone;

                                               ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom" name="grahak_email"
                                               id="grahak_email" placeholder="Email" value="<?php

                                        echo $grahak_email;

                                        ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">TAX ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control margin-bottom" name="grahak_tax"
                                               id="grahak_tax" placeholder="TAX ID" value="<?php

                                        echo $grahak_tax;

                                        ?>">
                                    </div>
                                </div>
                                <div class="form-group ">

                                    <div class="col-sm-5 margin-bottom">
                                        <input type="submit" id="action_update_vendor"
                                               class="btn btn-success float-left" value="Update Vendor"
                                               data-loading-text="Creating...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <div class="message"></div>
    </div>
    <?php

}

function rcplist($id)
{

    echo "<script type='text/javascript'>
$(document).ready(function() {
    $('#ilist').DataTable( {
        stateSave: true
    } );
} );
</script>";

    global $db, $siteConfig;
    if ($id > 0) {

        $query = "SELECT * FROM receipts WHERE csd='$id' ORDER BY tid ";

        $result = $db->pdoQuery($query)->results();

        if ($result) {
			
		 $tcsum = $db->sum('receipts', 'total', "csd='$id' ");
		 $paid = $db->sum('receipts', 'ramm', "csd='$id' ");

            echo '
			
			<div id="receiptPayment" class="modal fade">
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
<div style="padding:10px;"> <a data-csd="'.$id.'" class="btn btn-success receiptPayment" title="Partial Payment"><span class="icon-drawer"></span>Pay Now </a> &nbsp; <a href="index.php?rdp=payments&op=v&id=' . $id . '" class="btn btn-info "><span class="icon-file-text2"></span>View Payments</a></div>
		<div id="notify" class="alert alert-success" style="display:none;">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<div class="message"></div>
		</div>
	
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Receipts List Vendor/Supplier Wise</h4>
			</div>
			<div class="panel-body tbl"> 
			<div class="vendortotal">
				<div class="col-md-4">
				<p> Total Amount : <strong>'.$siteConfig['curr'].$tcsum.'</strong> </p>
				<p> Total Paid : <strong>'.$siteConfig['curr'].$paid.'</strong> </p>
				<p> Due : <strong>'.$siteConfig['curr'].($tcsum-$paid).'</strong> </p>
				</div>
				
				<div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <form method="get" action="view/payment-view.php">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required" name="f" value="'.date('d-m-Y',strtotime("-1 month")).'" data-date-format="'.$siteConfig['dformat2'].'"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="t" value="'. date('d-m-Y').'" data-date-format="'.$siteConfig['dformat2'].'"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
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
                            	<input type="hidden" name="vid" value="'.$id.'" />
                                <button type="submit" class="btn btn-primary "> Report </button>
                            </div>
                            <div class="clearfix"></div>
                            </form>
                		</div>
                    </div>
                </div>
                <div class="clearfix"></div>
				
				<hr>
			</div>
			
			<table id="ilist" class="table-responsive cell-border" cellspacing="0"><thead><tr>';

            echo '<th>Receipts</h4></th><th>Issue Date</h4></th>
				
				<th>Total</h4></th>
				<th>Status</h4></th>
				
				<th>Settings</h4></th>

			  </tr></thead><tbody>';

            foreach ($result as $row) {

                echo '
				<tr>
					<td>' . $row["tid"] . ' <a href="view/receipt-view.php?id=' . $row["tid"] .
                    '" class="btn btn-success btn-xs"><span class="icon-print"></span>Print</a></td>';

                echo '<td>' . $row["tsn_date"] . '</td>';
				echo '<td>' . $row["total"] . '</td><td> ';

                if ($row['status'] == "partial") {
                    echo '<span class="label label-warning">Partial</span> ';
                } elseif ($row['status'] == "paid") {
                    echo '<span class="label label-success">Paid</span> ';
                } else echo '<span class="label label-danger">Due</span> ';

                echo '  </td><td><a href="index.php?rdp=receipt&op=edit&id=' . $row["tid"] .
                    '" class="btn btn-primary btn-xs" title="Edit"><span class="icon-pencil"></span>Edit</a>&nbsp; &nbsp;<a href="view/receipt-view.php?id=' .
                    $row["tid"] . '&download=1" class="btn btn-info btn-xs"  title="Download"><span class="icon-download2"></span></a></td>
			    </tr>
			';

            }

            echo '</tr></tbody></table>';

        } else {

            echo '<div class="panel panel-default"><div class="alert alert-danger"><strong>Oops!! There are no purchase to display by this customer.</strong></div></div>';

        }
    }
    ?>
    </div>
    </div>
    </div>
    <div>


        <?php

        }
        function clist()
        {

        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#cust').DataTable({
                    stateSave: true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "request/listvendor.php"
                });
            });
            $(document).on('click', ".delete-vendor", function (e) {
                e.preventDefault();

                var userId = 'act=delete_vendor&delete=' + $(this).attr('data-grahk-id');
                var user = $(this);

                $('#delete_vendor').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                    deleteVendor(userId);
                    $(user).closest('tr').remove();
                });
            });
            function deleteVendor(userId) {

                jQuery.ajax({

                    url: 'request/e_control.php',
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

            }</script>
        <div class="row">

            <div class="col-xs-12">
                <div id="notify" class="alert alert-success" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <div class="message"></div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Vendors/Suppliers List</h4>
                    </div>
                    <div class="panel-body form-group form-group-sm"><a href="index.php?rdp=vendor&op=add"
                                                                        class="btn btn-primary"><span
                                    class="icon-plus"></span>Add New Supplier/Vendor Data</a></div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body form-group form-group-sm tbl">
                        <table id="cust" class="table-responsive cell-border" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Settings</th>

                            </tr>

                            </thead>

                        </table>

                    </div>
                </div>
            </div>
            <div>

                <div id="delete_vendor" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete Vendor</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this vendor?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete
                                </button>
                                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div><?php

}

?>