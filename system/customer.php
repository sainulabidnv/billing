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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "customer.php")) {
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
    case "invoices":

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
        <input type="hidden" name="act" value="create_customer">

        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>New Customer Details</h4>

                        <div class="clear"></div>
                    </div>
                    <div class="panel-body form-group form-group-sm">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Customer Name</label>

                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Customer Name"
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
                                               class="btn btn-success float-left" value="Create Customer"
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
    $row = $db->select('reg_customers', null, $whereConditions)->results();
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

    <form method="post" id="update_customer">
        <input type="hidden" name="act" value="update_customer">
        <input type="hidden" name="id" value="<?php

        echo $id;

        ?>">

        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Editing Customer (<?php

                            echo $grahak_name;

                            ?>)</h4>

                        <div class="clear"></div>
                    </div>
                    <div class="panel-body form-group form-group-sm">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label margin-bottom">Customer Name</label>

                                    <div class="col-sm-10">
                                        <input type="text" placeholder="Customer Name"
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
                                        <input type="submit" id="action_update_customer"
                                               class="btn btn-success float-left" value="Update Customer"
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

function clist()
{

    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#cust').DataTable({
                stateSave: true,
                "processing": true,
                "serverSide": true,
                "ajax": "request/listcustomer.php"
            });
        });</script>
    <div class="row">

        <div class="col-xs-12">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Customer List</h4>
                </div>
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
            </div><?php

}

?>