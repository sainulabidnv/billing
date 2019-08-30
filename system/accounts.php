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

if (stristr(htmlentities($_SERVER['PHP_SELF']), "accounts.php")) {
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

    case "addac":
        add();
        break;
    case "edit":
        editac($id);
        break;
    case "link":
        linkac();
        break;
    default:
        aclist();
        break;
}


function add()
{
    global $siteConfig;
    ?>


    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>New Bank A/c Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="add_account">
                    <input type="hidden" name="act" value="add_account">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">A/c Holder Name</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="A/c Holder"
                                           class="form-control margin-bottom  required" name="holder_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Bank</label>

                                <div class="col-sm-5">
                                    <input type="text" placeholder="Bank Name"
                                           class="form-control margin-bottom required" name="bank_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">A/c No.</label>

                                <div class="col-sm-5">


                                    <input type="text" name="ac_no" class="form-control margin-bottom required"
                                           placeholder="1234567890">

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Opening Balance</label>

                                <div class="col-sm-5">


                                    <input type="text" name="o_bal" class="form-control margin-bottom required"
                                           placeholder="1234567890">

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5">
                                    <input type="submit" id="action_add_account" class="btn btn-success margin-bottom"
                                           value="Add Account" data-loading-text="Adding...">
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
        $("#action_add_account").click(function (e) {
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

                var $btn = $("#action_add_account").button("loading");

                $.ajax({

                    url: 'request/account.php',
                    type: 'POST',
                    data: $("#add_account").serialize(),
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

function editac($id){
    global $db;
    $whereConditions = array('id' => $id);
    $row = $db->select('bank_ac', null, $whereConditions)->results();
    if ($row) {

        $acn = $row['acn'];
        $holder = $row['holder'];
        $bank = $row['bank'];


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
                <h4>Edit A/c <?php echo $acn; ?> </h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" id="update_account">
                    <input type="hidden" name="act" value="update_account">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Holder Name</label>

                                <div class="col-sm-5">
                                    <label>
                                        <input type="text" class="form-control margin-bottom  required"
                                               name="holder_name"
                                               value="<?php echo $holder; ?>">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">A/c No</label>

                                <div class="col-sm-5">
                                    <input type="text" class="form-control margin-bottom required" name="ac_no"
                                           value="<?php echo $acn; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom">Bank</label>

                                <div class="col-sm-5">

                                    <input type="text" name="bank_name" class="form-control margin-bottom required"
                                           aria-describedby="sizing-addon1" value="<?php echo $bank; ?>">

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5 margin-bottom">
                                    <input type="submit" id="action_update_account" class="btn btn-success float-right"
                                           value="Update account" data-loading-text="Updating...">
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
<?php }
function aclist()
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
                $('#alist').DataTable({
                    stateSave: true
                });
            });
        </script>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Accounts List</h4>
            </div>
            <div class="panel-body form-group form-group-sm tbl"><a href="index.php?rdp=accounts&op=addac"
                                                                    class="btn btn-info btn-danger"><span
                            class="icon-plus"></span>Add New Account</a>&nbsp; &nbsp; &nbsp; <a
                        href="index.php?rdp=accounts&op=link"
                        class="btn btn-info btn-success"><span
                            class="icon-plus"></span>Link Account to Sales</a><br><br>
                <?php global $db;

                $query = "SELECT * FROM bank_ac ORDER BY id ASC";
                $result = $db->pdoQuery($query)->results();

                if ($result) {

                    echo '<table id="alist" class="table"><thead><tr>

				<th>A/c No</th>
				<th>Balance</th>
				<th>Holder</th>
				<th>Bank</th>
				<th>Settings</th>

			  </tr></thead><tbody>';

                    foreach ($result as $row) {

                        echo '
			    <tr>
					<td>' . $row["acn"] . '</td>
					 <td>' . amountFormat($row["lastbal"]) . '</td>
				    <td>' . $row["holder"] . '</td>
				    <td>' . $row["bank"] . '</td>
				    <td><a href="index.php?rdp=transactions&op=actransactions&id=' . $row["id"] . '" class="btn btn-success btn-xs"><span class="icon-folder-open"></span>View</a> &nbsp; <a href="index.php?rdp=accounts&op=edit&id=' . $row["id"] . '" class="btn btn-primary btn-xs"><span class="icon-pencil"></span>Edit</a> <a data-account-id="' . $row['id'] . '" class="btn btn-danger btn-xs delete-account"><span class="icon-bin"></span></a></td>
			    </tr>
		    ';
                    }

                    echo '</tr></tbody></table></div>';

                } else {

                    echo "<p>There are no accounts to display.</p>";

                }
                ?>
            </div>
        </div>
    </div>
    <div>
    <script type="text/javascript">
        $(document).on('click', ".delete-account", function (e) {
            e.preventDefault();

            var acnId = 'act=delete_account&delete=' + $(this).attr('data-account-id');
            var acn = $(this);

            $('#confirm').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
                deleteAcn(acnId);
                $(acn).closest('tr').remove();
            });
        });
        function deleteAcn(acnId) {

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
                    <h4 class="modal-title">Delete account</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this account?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php }

function linkac()
{
    global $db;


    ?>
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>

        <div class="message"></div>
    </div>

    <div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Link Sales to bank account</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <?php $result = $db->select('conf')->results();
                $enb = $result['bank'];
                $acid = $result['acid'];
                if ($enb == 1) {
                    $whereConditions = array('id' => $acid);
                    $row = $db->select('bank_ac', null, $whereConditions)->results();
                    if ($row) {
                        $acn = $row['acn'];
                        $holder = $row['holder'];
                        $bank = $row['bank'];
                        echo "<u>Currently:</u> $bank - <strong>$acn</strong> [$holder] is linked to Sales Transactions<hr>";
                    } else {
                        $dataArray = array('bank' => 0);
                        $aWhere = array('id' => 1);
                        $status = $db->update('conf', $dataArray, $aWhere)->rStatus();
                    }
                } else {
                    echo "Sorry! There is no linked account found to sales.<hr>";
                } ?>
                <form method="post" id="link_account">
                    <input type="hidden" name="act" value="link_account">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">

                                <label class="col-sm-4 control-label margin-bottom">Bank Account </label>

                                <div class="col-sm-5"><select name="acid" class="form-control margin-bottom">
                                        <?php
                                        $flag = true;
                                        $query = "SELECT id,acn FROM bank_ac ORDER BY id";
                                        if ($result = $db->pdoQuery($query)->results()) {
                                            foreach ($result as $row) {
                                                echo '<option value="' . $row['id'] . '">' . $row['acn'] . '</option>';
                                            }
                                        } else {
                                            echo "Please add a bank account first";
                                            $flag = false;
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5 margin-bottom">
                                    <?php if ($flag) {
                                        echo '<input type="submit" id="action_link_account" class="btn btn-success float-right"
                                           value="Link this account" data-loading-text="Updating...">';
                                    } ?>
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
            $(document).on('click', "#action_link_account", function (e) {
                e.preventDefault();
                updateLink();
            });
            function updateLink() {

                var $btn = $("#action_link_account").button("loading");

                jQuery.ajax({

                    url: 'request/account.php',
                    type: 'POST',
                    data: $("#link_account").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $btn.button("reset");
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
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
<?php }