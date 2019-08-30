<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "profit.php")) {
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
                <h4>Profit Calculations</h4>

                <div class="clear"></div>
            </div>
            <div class="panel-body form-group form-group-sm">
                <div class="row">
                    <div class="panel-body"><h4>By Invoice Date</h4>

                        <form method="post" id="add_product">

                            <input type="hidden" name="act" value="add_product">

                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label">From Date</label>

                                <div class="col-sm-5">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="fdate"
                                               value="<?php global $siteConfig;

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
                                    <input type="submit" id="action_cal_pro" class="btn btn-success float-right"
                                           value="Calculate"
                                           data-loading-text="Calculateing...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="getinvoice">
                        </form>
                    </div>
                    <div id="notify" class="alert alert-success" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>

                        <div class="message"></div>
                    </div>
                    <script type="text/javascript">
                        $("#action_cal_pro").click(function (e) {
                            e.preventDefault();
                            actionCalDate();
                        });
                        function actionCalDate() {

                            var errorNum = farmCheck();
                            if (errorNum > 0) {
                                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                                $("#notify").animate(1000);
                            } else {

                                $(".required").parent().removeClass("has-error");

                                var $btn = $("#action_cal_pro").button("loading");

                                $.ajax({

                                    url: 'request/profit.php',
                                    type: 'POST',
                                    data: $("#add_product").serialize(),
                                    dataType: 'json',
                                    success: function (data) {
                                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                                        $("#notify").animate(1000);
                                        $btn.button("reset");
                                    },
                                    error: function (data) {
                                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                                        $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                                        $("#inotify").animate(1000);
                                        $btn.button("reset");
                                    }

                                });
                            }

                        }
                    </script>

                </div>
                <hr>
                <div class="row">
                    <div class="panel-body"><h4>By Invoices Number</h4>

                        <form method="post" id="iadd_product">
                            <input type="hidden" name="act" value="iadd_product">

                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label">Starting Number</label>

                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="ist"/>

                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label">End Number</label>

                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="iend"/>

                                    </div>
                                </div>
                            </div>
                            <div class="row margin-bottom">

                                <label class="col-sm-2 control-label margin-bottom"></label>

                                <div class="col-sm-5">
                                    <input type="submit" id="iaction_cal_pro" class="btn btn-success float-right"
                                           value="Calculate"
                                           data-loading-text="Calculateing...">
                                </div>
                            </div>
                            <input type="hidden" name="op" value="rgetinvoice">
                        </form>
                    </div>
                    <div id="inotify" class="alert alert-success" style="display:none;">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>

                        <div class="message"></div>
                    </div>
                    <script type="text/javascript">
                        $("#iaction_cal_pro").click(function (e) {
                            e.preventDefault();
                            iactionCalDate();
                        });
                        function iactionCalDate() {

                            var errorNum = farmCheck();
                            if (errorNum > 0) {
                                $("#inotify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                                $("#inotify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                                $("#inotify").animate(1000);
                            } else {

                                $(".required").parent().removeClass("has-error");

                                var $btn = $("#iaction_cal_pro").button("loading");

                                $.ajax({

                                    url: 'request/profit.php',
                                    type: 'POST',
                                    data: $("#iadd_product").serialize(),
                                    dataType: 'json',
                                    success: function (data) {
                                        $("#inotify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                                        $("#inotify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                                        $("#inotify").animate(1000);
                                        $btn.button("reset");
                                    },
                                    error: function (data) {
                                        $("#inotify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                                        $("#inotify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                                        $("#inotify").animate(1000);
                                        $btn.button("reset");
                                    }

                                });
                            }

                        }
                    </script>

                </div>


            </div>
        </div>
    </div>
</div>
