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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "info.php")) {
    die("Internal Server Error!");
}

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
//about
switch ($op) {
    case "support":
        support();
        break;
    case "tips":
        tips();
        break;
    default:
        info();
        break;
}


function support()
{

    ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Express Invoice Support</h4>

                    <div class="clear"></div>
                </div>
                <div class="panel-body form-group form-group-sm">
                    <div class="row">
                        <div class="col-sm-12">Hi,<p>Are you facing and software reated issue? There are two ways to get
                                support.</p>

                            <p><strong>With Skyresoft</strong>, You can write us on <a
                                        href="http://skyresoft.com/" target="_blank">skyresoft.com/user/ultimatekode</a>
                            </p>

                            <p>Additionally , you can write us on <a href="mailto:support@skyresoft.com">support@skyresoft.com</a>
                                for additional information.</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

}

function tips()
{

    ?>
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Express Invoice Support</h4>

                <div class="clear"></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">Dear Software user,<br>We made this software with months of continue work.
                        However We tried to to make this software bug free and good user interface and easy to set up.
                        Even these things still you are facing any issue or need any support feel free to write us on
                        support@skyresoft.com
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

}

function info()
{

    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Express Invoice Info</h4>

            <div class="clear"></div>
        </div>
        <div class="panel-body form-group form-group-sm">
            <div class="row">
                <div class="col-sm-12">
                    <div style="text-align: center;"><h1>EXPRESS INVOICE</h1>

                        <h2>version: 7.2</h2>

                        <h3>&copy; 2017 <a href="https://www.skyresoft.com">www.skyresoft.com</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

}

?>