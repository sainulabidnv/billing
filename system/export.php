<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "export.php")) {
    die("Internal Server Error!");
}
if ($user->group_id > 2) {
    die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
}
?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Data Export Manager</h4>

                <div class="clear"></div>
            </div>
            <div class="panel-body form-group form-group-sm">
                
                
                <div class="row">
                    <div class="panel-body"><h4>Export Full Backup</h4>

                        <form method="post" action="request/fullbackup.php">


                            <div class="row margin-bottom">


                                <div class="col-sm-5">
                                    <input type="submit" class="btn btn-success " value="Full Backup"
                                           data-loading-text="Downloading...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="getcustomer">
                        </form>
                    </div>


                </div>
                <hr />
                <div class="row">
                    <div class="panel-body"><h4>Export Invoices</h4>

                        <form method="post" action="request/downloadreports.php">


                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label">From Date</label>

                                <div class="col-sm-5">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="fdate" value="<?php

                                        echo $siteConfig['date'];

                                        ?>" data-date-format="<?php

                                        echo $siteConfig['dformat2'];

                                        ?>"/>
                                        <span class="input-group-addon">
    <span class="icon-calendar"></span>
    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label">To Date</label>

                                <div class="col-sm-5">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required" name="tdate" value="<?php

                                        echo $siteConfig['date'];

                                        ?>" data-date-format="<?php

                                        echo $siteConfig['dformat2'];

                                        ?>"/>
                                        <span class="input-group-addon">
    <span class="icon-calendar"></span>
    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5">
                                    <input type="submit" class="btn btn-success float-right" value="Download Invoices"
                                           data-loading-text="Downloading...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="getinvoice">
                        </form>
                    </div>


                </div>
                <hr>
                <div class="row">
                    <div class="panel-body"><h4>Export Recurring Invoices</h4>

                        <form method="post" action="request/downloadreports.php">


                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label">From Date</label>

                                <div class="col-sm-5">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="fdate" value="<?php

                                        echo $siteConfig['date'];

                                        ?>" data-date-format="<?php

                                        echo $siteConfig['dformat2'];

                                        ?>"/>
                                        <span class="input-group-addon">
    <span class="icon-calendar"></span>
    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label">To Date</label>

                                <div class="col-sm-5">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required" name="tdate" value="<?php

                                        echo $siteConfig['date'];

                                        ?>" data-date-format="<?php

                                        echo $siteConfig['dformat2'];

                                        ?>"/>
                                        <span class="input-group-addon">
    <span class="icon-calendar"></span>
    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5">
                                    <input type="submit" class="btn btn-success float-right" value="Download Invoices"
                                           data-loading-text="Downloading...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="rgetinvoice">
                        </form>
                    </div>


                </div>
                <hr>
                <div class="row">
                    <div class="panel-body"><h4>Export Registered Customer Data</h4>

                        <form method="post" action="request/downloadreports.php">


                            <div class="row margin-bottom">


                                <div class="col-sm-5">
                                    <input type="submit" class="btn btn-success " value="Download Customer Data"
                                           data-loading-text="Downloading...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="getcustomer">
                        </form>
                    </div>


                </div>
                <hr>
                <div class="row">
                    <div class="panel-body"><h4>Export UnRegistered Customer Data</h4>

                        <form method="post" action="request/downloadreports.php">


                            <div class="row margin-bottom">


                                <div class="col-sm-5">
                                    <input type="submit" class="btn btn-success "
                                           value="Download UnRegistered Customer Data"
                                           data-loading-text="Downloading...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="unregcustomer">
                        </form>
                    </div>


                </div>
                <hr>
                <div class="row">
                    <div class="panel-body"><h4>Export Product List</h4>

                        <form method="post" action="request/downloadreports.php">


                            <div class="row margin-bottom">


                                <div class="col-sm-5">
                                    <input type="submit" class="btn btn-success " value="Download Product List Data"
                                           data-loading-text="Downloading...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="productlist">
                        </form>
                    </div>


                </div>
                <hr>

                <div class="row">
                    <div class="panel-body"><h4>Export Sales Reports Data</h4>

                        <form method="post" action="request/downloadreports.php">


                            <div class="row margin-bottom">


                                <div class="col-sm-5">
                                    <input type="submit" class="btn btn-success " value="Download Sales Reports Data"
                                           data-loading-text="Downloading...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="salesreports">
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
