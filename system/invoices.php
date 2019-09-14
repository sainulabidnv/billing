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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "invoices.php")) {
    die("Internal Server Error!");
}
//invoices

 
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="panel-heading">Manage Invoices</div>
            <div class="panel-body tbl">


                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#invo').DataTable({
                            stateSave: true,
                            "processing": true,
                            "serverSide": true,
                            "ajax": "request/listinvoice.php"
                        });
                    });</script>
                <div class="table-responsive">
                    <table id="invo" class="table cell-border" cellspacing="0">
                        <thead>
                        <tr>
                            <th>#Invoice</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Options</th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
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

<div id="fileupload" class="modal fade">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">File upload  </h4>
            </div>
            <div class="modal-body">
            <div style="max-width:100%">
                <?php include_once('system/fileupload.php'); ?>
            </div>
            </div>
            <div class="modal-footer">
                 <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
        </div>
    </div>
</div>

