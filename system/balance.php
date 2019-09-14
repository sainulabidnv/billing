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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "balance.php")) {
    die("Internal Server Error!");
}
//income expenses tracker
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
    case "reports":
        reports();
        break;
    case "goal":
        goals();
        break;
    default:
        aclist();
        break;
}

function add()
{
    global $siteConfig;

    ?>


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
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Add Income/Expense Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="add_balance">
                    <input type="hidden" name="act" value="add_balance">

                    <div class="row">
                        <div class="col-xs-12">
                        
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Customer/Staff</label>

                                <div class="col-sm-5">
                                    
                                    <div class="row">
                                        <div class="col-sm-4"> <input type="text" class="form-control  margin-bottom" name="grahak_name" id="grahak_name" readonly > </div>
                                        <div class="col-sm-4"> <input type="button" id="clear-form" class="btn btn-primary  grahk-select  margin-bottom " value="Select "></div>
                                    </div>
                                     
                                    
                                    <input type="hidden" name="grahak_id" id="grahak_id" value="">
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Date</label>

                                <div class="col-sm-5">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required margin-bottom" name="bdate"
                                               value="<?php

                                               echo $siteConfig['date'];

                                               ?>" data-date-format="<?php

                                        echo $siteConfig['dformat2'];

                                        ?>"/>
                                        <span class="input-group-addon margin-bottom"> <span class="icon-calendar margin-bottom"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Amount</label>

                                <div class="col-sm-5">
                                    <input type="number" placeholder="Amount"
                                           class="form-control margin-bottom required"
                                           name="bamount">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Type Inc/Exp</label>

                                <div class="col-sm-5">


                                    <select name="stat" class="form-control margin-bottom">
                                        <option value="1">Income</option>
                                        <option value="0">Expense</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Category</label>

                                <div class="col-sm-5">
                                    <select name="category" required class="form-control margin-bottom">
                                        <option value="Other">Other</option>
                                        <option value="Salary">Salary</option>
                                        <option value="Rent">Rent</option>
                                        <option value="Petrol">Petrol</option>
                                     </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Note</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Note" class="form-control margin-bottom "
                                           name="bnote">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5">
                                    <input type="submit" id="action_add_balance" class="btn btn-success margin-bottom"
                                           value="Add Details" data-loading-text="Adding...">
                                </div>
                            </div>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $("#action_add_balance").click(function (e) {
            e.preventDefault();
            actionAddAc();
        });
        function actionAddAc() {

            var errorNum = farmCheck();

            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {

                $(".required").parent().removeClass("has-error");

                var $btn = $("#action_add_balance").button("loading");

                $.ajax({

                    url: 'request/account.php',
                    type: 'POST',
                    data: $("#add_balance").serialize(),
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

        }
    </script><?php

}

function goals()
{
    global $db;


    $row = $db->select('goals')->results();
    if ($row) {

        $acn = $row['income'];
        $holder = $row['expense'];
        $sbank = $row['sales'];
        $bank = $row['invoices'];
        $rsbank = $row['rsales'];
        $rbank = $row['rinvoices'];


    } else {
        die();
    }
    ?>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Set targets For <?php

                    $month = date('F, Y');
                    echo "$month"

                    ?></h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="update_account">
                    <input type="hidden" name="act" value="setgoal">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">

                                <label class="col-md-3 control-label margin-bottom">Expected Income</label>

                                <div class="col-md-9">
                                    <input type="number" class="form-control margin-bottom  required" name="ac_no"
                                           value="<?php

                                           echo $acn;

                                           ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-3 control-label margin-bottom">Expected Expenses</label>

                                <div class="col-sm-9">
                                    <input type="number" class="form-control margin-bottom required" name="holder_name"
                                           value="<?php

                                           echo $holder;

                                           ?>">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-3 control-label margin-bottom">Expected Sales</label>

                                <div class="col-sm-9">

                                    <input type="number" name="sbank_name" class="form-control margin-bottom required"
                                           aria-describedby="sizing-addon1" value="<?php

                                    echo $sbank;

                                    ?>">

                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-3 control-label margin-bottom">Expected Invoices</label>

                                <div class="col-sm-9">

                                    <input type="number" name="bank_name" class="form-control margin-bottom required"
                                           aria-describedby="sizing-addon1" value="<?php

                                    echo $bank;

                                    ?>">

                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-3 control-label margin-bottom">Expected Recurring Sales</label>

                                <div class="col-sm-9">

                                    <input type="number" name="rsbank_name" class="form-control margin-bottom required"
                                           aria-describedby="sizing-addon1" value="<?php

                                    echo $rsbank;

                                    ?>">

                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-3 control-label margin-bottom">Expected Recurring Invoices</label>

                                <div class="col-sm-9">

                                    <input type="number" name="rbank_name" class="form-control margin-bottom required"
                                           aria-describedby="sizing-addon1" value="<?php

                                    echo $rbank;

                                    ?>">

                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-3 control-label margin-bottom"></label>

                                <div class="col-sm-9 margin-bottom">
                                    <input type="submit" id="action_update_account"
                                           class="btn btn-success float-right" value="Set Goal"
                                           data-loading-text="Updating...">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row">

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $(document).on('click', "#action_update_account", function (e) {
            e.preventDefault();
            updateProduct();
        });
        function updateProduct() {

            var $btn = $("#action_update_account").button("loading");

            jQuery.ajax({

                url: 'request/account.php',
                type: 'POST',
                data: $("#update_account").serialize(),
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
    <?php

}



function aclist()
{
    global $db, $user, $siteConfig;
    //print_r($siteConfig);exit;

    $uid = empty($_GET['uid'])? '' : '&uid='.$_GET['uid'];
    $sdate = empty($_GET['sd'])? '' : '&sd='.$_GET['sd'];
    $edate = empty($_GET['ed'])? '' : '&ed='.$_GET['ed'];
    $cat = empty($_GET['cat'])? '' : '&cat='.$_GET['cat'];

    $quid = empty($_GET['uid'])? '' : ' && uid='.$_GET['uid'];
    $qsdate = empty($_GET['sd'])? '' : ' && bdate >= "'.date('Y-m-d',strtotime($_GET['sd'])).'"';
    $qedate = empty($_GET['ed'])? '' : ' && bdate <= "'.date('Y-m-d',strtotime($_GET['ed'])).'"';
    $qcat = empty($_GET['cat'])? '' : ' && category="'.$_GET['cat'].'"';

//echo $quid.$qsdate.$qedate.$qcat; exit;
    $tincome = $db->sum('ac_balance', 'amount', 'stat=1'.$quid.$qsdate.$qedate.$qcat);
    $texpense = $db->sum('ac_balance', 'amount', 'stat=0'.$quid.$qsdate.$qedate.$qcat);
    $tbalance = $tincome-$texpense;
    
    //$query = "SELECT DISTINCT uid FROM ac_balance Where uid !=0 ";
    $query = "SELECT DISTINCT b.uid, c.name FROM ac_balance AS b LEFT JOIN reg_customers AS c ON b.uid=c.id Where uid !=0 ";
    $userlist = $db->pdoQuery($query)->results();

    $cquery = "SELECT DISTINCT category FROM ac_balance  Where category !='' ";
    $catlist = $db->pdoQuery($cquery)->results();
    //echo '<pre>';
    //print_r($catlist);exit;

    ?>



    <div class="row">

    <div class="col-xs-12">

        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading ">
                <h4>Income and Expenses Transactions</h4>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#blist').DataTable({
                        stateSave: true,
                        "processing": true,
                        "serverSide": true,
                        "ajax": "request/listinex.php?page=income<?php echo $uid.$sdate.$edate.$cat; ?>"
                    });
                    $('#blist2').DataTable({
                        stateSave: true,
                        "processing": true,
                        "serverSide": true,
                        "ajax": "request/listinex.php?page=expense<?php echo $uid.$sdate.$edate.$cat; ?>"
                    });
                });
            </script>
            <div class="panel-body form-group form-group-sm"><p><a href="index.php?rdp=balance&op=add"
                                                                   class="btn btn-primary"><span
                                class="icon-plus"></span>Add New Income Expense Data</a> &nbsp; &nbsp; &nbsp; <a
                            href="index.php?rdp=balance&op=goal" class="btn btn-success"><span
                                class="icon-calendar"></span>
                        &nbsp; Set Targets For <?php

                        $month = date('F, Y');
                        echo "$month"

                        ?></a> &nbsp; &nbsp; &nbsp; <a href="index.php?rdp=balance&op=reports"
                                                       class="btn btn-warning"><span class="icon-calendar"></span>
                        &nbsp; Monthly Income/Expense Reports</a> &nbsp; &nbsp; &nbsp; <?php if ($user->group_id == 1) {
                        echo '<a
                        href="index.php?rdp=reports&op=monthly" class="btn btn-primary"><span
                            class="icon-calendar"></span> &nbsp; Monthly Sales Reports</a>';
                    } ?></p></div>
        </div>

        <div class="panel panel-default">
			<div class="panel-heading">
				<h4>Receipts List Vendor/Supplier Wise</h4>
			</div>
			<div class="panel-body tbl"> 
			<div class="vendortotal">
				<div class="col-md-4">
				<p> Total Income : <strong><?php echo $siteConfig['curr'].$tincome; ?> </strong> </p>
				<p> Total Expense : <strong><?php echo $siteConfig['curr'].$texpense; ?></strong> </p>
				<p> Balance : <strong><?php echo $siteConfig['curr'].$tbalance; ?></strong> </p>
				</div>
				
				<div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <form method="get" action="http://192.168.1.2/projects/billing/">
                            <div class="col-md-3">

                                <div class="form-group">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required" name="sd" value="<?php if($_GET['sd']!='') { echo date('d-m-Y',strtotime($_GET['sd']));} ?>" data-date-format="<?php echo $siteConfig['dformat2'];?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="ed" value="<?php if($_GET['ed']!='') { echo date('d-m-Y',strtotime($_GET['ed']));} ?>" data-date-format="<?php echo $siteConfig['dformat2'];?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group" >
                                        <select name="uid">
                                            <option value=""> All User </option> 
                                            <?php 
                                            foreach($userlist as $usr){ ?>
                                                <option <?php if(isset($_GET['uid']) and $_GET['uid'] ==$usr['uid']) { echo 'selected';} ?> value="<?php echo $usr['uid']; ?>"> <?php echo $usr['name']; ?> </option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group" >
                                        <select name="cat">
                                            <option value=""> All Category </option> 
                                            <?php 
                                            foreach($catlist as $cat){ ?>
                                                <option <?php if(isset($_GET['cat']) and $_GET['cat'] == $cat['category']) { echo 'selected';} ?> value="<?php echo $cat['category']; ?>"> <?php echo $cat['category']; ?> </option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                            	<input type="hidden" name="rdp" value="balance" />
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
        </div>
        <div class="panel panel-default">
            <div class="panel-heading ">
                <h4>Income Transactions</h4>
            </div>

            <div class="panel-body form-group form-group-sm tbl">

                <table id="blist" class="table-responsive cell-border" cellspacing="0">
                    <thead>
                    <tr>

                            <th>Date</th>
                            <th>Customer/Staff</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Note</th>
                            <th>Action</th>


                    </tr>
                    </thead>
                </table>

            </div>

            <div class="panel panel-default">
                <div class="panel-heading ">
                    <h4>Expense Transactions</h4>
                </div>
                <div class="panel-body form-group form-group-sm tbl">
                    <table id="blist2" class="table-responsive cell-border" cellspacing="0">
                        <thead>
                        <tr>

                            <th>Date</th>
                            <th>Customer/Staff</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Note</th>
                            <th>Action</th>


                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $(document).on('click', ".delete-btran", function (e) {
            e.preventDefault();

            var acnId = 'act=delete_btran&delete=' + $(this).attr('data-btran-id');
            var acn = $(this);

            $('#confirm').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                deleteBtran(acnId);
                $(acn).closest('tr').remove();
            });
        });
        function deleteBtran(acnId) {

            jQuery.ajax({

                url: 'request/account.php',
                type: 'POST',
                data: acnId,
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
    <div id="confirm" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete transaction</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this transaction?</p>
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
function reports()
{

    ?>
    <div class="row">

        <div class="col-xs-12">

            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading ">
                    <h4>Income and Expenses List</h4>
                </div>
                <div class="panel-body form-group form-group-sm"><p><a href="index.php?rdp=balance&op=add"
                                                                       class="btn btn-info btn-primary"><span
                                    class="icon-plus"></span>Add New Income Expense Data</a></p></div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading ">
                    <h4>Monthwise Income/Expences Transactions</h4>
                </div>
                <div class="panel-body form-group form-group-sm tbl">

                    <table id="blist" class="table-responsive cell-border" cellspacing="0">
                        <thead>
                        <tr>

                            <th>Year</th>
                            <th>Month</th>
                            <th>Total Income</th>
                            <th>Total Expenses</th>


                        </tr>
                        </thead><?php

                        global $db;
                        $wmonth = 2678400;
                        $month = date('m', time() - $wmonth);
                        $year = date('Y', strtotime("-1 month"));
                        $monthName = date("F", mktime(0, 0, 0, $month, 10));


                        $isum = $db->sum('ac_balance', 'amount', "(DATE(bdate) BETWEEN '$year-$month-01' AND '$year-$month-31') AND stat=1");
                        $esum = $db->sum('ac_balance', 'amount', "(DATE(bdate) BETWEEN '$year-$month-01' AND '$year-$month-31') AND stat=0");

                        echo '<tr><td>' . $year . '</td><td>' . $monthName . '</td><td>' . amountFormat($isum) .
                            '</td><td>' . amountFormat($esum) . '</td>';
                        for ($i = 2; $i <= 12; $i++) {
                            $monthNum = date('m', strtotime("-$i month"));
                            $month = date('m', strtotime("-$i month"));
                            $year = date('Y', strtotime("-$i month"));
                            $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));


                            $isum = $db->sum('ac_balance', 'amount', "(DATE(bdate) BETWEEN '$year-$month-01' AND '$year-$month-31') AND stat=1");
                            $esum = $db->sum('ac_balance', 'amount', "(DATE(bdate) BETWEEN '$year-$month-01' AND '$year-$month-31') AND stat=0");

                            echo '<tr><td>' . $year . '</td><td>' . $monthName . '</td><td>' . amountFormat($isum) .
                                '</td><td>' . amountFormat($esum) . '</td>';

                        }


                        ?></table>

                </div>


            </div>
        </div>
    </div>
    <div>
    <?php

}

?>