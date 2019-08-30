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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "transactions.php")) {
    die("Internal Server Error!");
}
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
    case "addts":
        add();
        break;
    case "actransactions":
        transbyacc($id);
        break;
    default:
        tslist();
        break;
}

function add()
{

    ?>


    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>New Bank Transaction Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="add_transaction">
                    <input type="hidden" name="act" value="add_transaction">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Transaction ID</label>

                                <div class="col-sm-5">
                                    <input type="number" placeholder="12345678"
                                           class="form-control margin-bottom  required" name="tsno">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Transaction Amount </label>

                                <div class="col-sm-5">
                                    <input type="number" placeholder="10000" class="form-control margin-bottom required"
                                           name="tamount">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Type</label>

                                <div class="col-sm-5">
                                    <select name="stat" class="form-control margin-bottom required">
                                        <option value="Cr">Credit</option>
                                        <option value="Dr">Debit</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Bank Account </label>

                                <div class="col-sm-5"><select name="acno" class="form-control margin-bottom">
                                        <?php global $db, $siteConfig;
                                        $query = "SELECT id,acn FROM bank_ac ORDER BY id";
                                        $result = $db->pdoQuery($query)->results();
                                        foreach ($result as $row) {
                                            echo '<option value="' . $row['id'] . '">' . $row['acn'] . '</option>';
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Date</label>

                                <div class="col-sm-5">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required margin-bottom" name="tdate"
                                               value="<?php echo $siteConfig['date']; ?>"
                                               data-date-format="<?php echo $siteConfig['dformat2']; ?>"/>
                                        <span class="input-group-addon margin-bottom">
    <span class="icon-calendar margin-bottom"></span>
    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Note </label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Sent by Example Ltd "
                                           class="form-control margin-bottom required" name="tnote">
                                </div>
                            </div>


                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom"></label>

                                <div class="col-sm-5">
                                    <input type="submit" id="action_add_transaction"
                                           class="btn btn-success margin-bottom" value="Add transaction"
                                           data-loading-text="Adding...">
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
        $("#action_add_transaction").click(function (e) {
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

                var $btn = $("#action_add_transaction").button("loading");

                $.ajax({

                    url: 'request/transaction.php',
                    type: 'POST',
                    data: $("#add_transaction").serialize(),
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
    </script><?php }

function tslist()
{

    ?>


    <div class="row">

    <div class="col-xs-12">

        <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#bal').DataTable({
                    stateSave: true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "request/listtrans.php"
                });
            });
        </script>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Banking Transactions</h4>
            </div>
            <div class="panel-body form-group form-group-sm tbl"><a href="index.php?rdp=transactions&op=addts"
                                                                    class="btn btn-info btn-danger"><span
                            class="icon-plus"></span>New Transaction</a><br><br>
                <div class="table-responsive">
                    <table id="bal" class="table cell-border" cellspacing="0">
                        <thead>
                        <tr>

                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>A/c</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Note</th>


                        </tr>
                        </thead>
                    </table>
                </div>


            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $(document).on('click', ".delete-transaction", function (e) {
            e.preventDefault();

            var acnId = 'act=delete_transaction&delete=' + $(this).attr('data-transaction-id');
            var acn = $(this);

            $('#confirm').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                deleteAcn(acnId);
                $(acn).closest('tr').remove();
            });
        });
        function deleteAcn(acnId) {

            jQuery.ajax({

                url: 'request/transaction.php',
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
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete
                    </button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php }
function transbyacc($id)
{
    global $db, $siteConfig;
    $whereConditions = array('id' => $id);
    $row = $db->select('bank_ac', null, $whereConditions)->results();
    if ($row) {

        $acn = $row['acn'];
        $holder = $row['holder'];
        $bank = $row['bank'];
        $bal = $row['lastbal'];


    } else {
        die();
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
                $('#bal').DataTable({
                    stateSave: true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "request/listtrans.php?page=byac&id=<?php echo $id ?>"
                });
            });
        </script>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Transactions in <?php echo "$acn [ $holder / $bank ]"; ?></h4>
            </div>
            <div class="panel-body form-group form-group-sm tbl"><a href="index.php?rdp=transactions&op=addts"
                                                                    class="btn btn-info btn-sm  btn-danger "><span
                            class="icon-plus"></span>New Transaction</a><br><br>

                <div id="upir" style="display:none">

                    <div id="notify" class="alert alert-success" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>

                        <div class="message"></div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="bal" class="table cell-border" cellspacing="0">
                        <thead>
                        <tr>

                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Note</th>


                        </tr>
                        </thead>
                    </table>
                </div>


            </div>
        </div>
    </div>
    <div>
        <script type="text/javascript">


            $(document).on('click', ".delete-transaction", function (e) {
                e.preventDefault();

                var acnId = 'act=delete_transaction&delete=' + $(this).attr('data-transaction-id');
                var acn = $(this);

                $('#confirm').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                    deleteAcn(acnId);
                    $(acn).closest('tr').remove();
                });
            });
            function deleteAcn(acnId) {

                jQuery.ajax({

                    url: 'request/transaction.php',
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
                        <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete
                        </button>
                        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="update_reports" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Check current balace in this account ?</h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary" id="superupdate">Yes</button>
                        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

<?php }