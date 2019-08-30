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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "functions.php")) {
    die("Internal Server Error!");
}
//date format
function dateformat($date)
{
    global $siteConfig;
    $newdate = strtotime($date);
    $date = date($siteConfig['dformat'], $newdate);
    return $date;
}

//amount format
function amountFormat($number)
{
    global $siteConfig;
    //Format money as per country
    if ($siteConfig['fcurr'] == 1) {
        return number_format($number, 2, ',', '.');
    } else {
        return number_format($number, 2, '.', ',');
    }
}

function p($txt, $s = 3)
{
    if (is_array($txt)) {
        aPrint($txt);
        return;
    }
    echo "<h{$s}>{$txt}</h{$s}>";
}


/**
 * Prints an array in a readable form
 * @param array $a
 */
function aPrint(array $a)
{
    echo "<pre>";
    print_r($a);
    echo "</pre>";
}

/**
 * Redirects the user
 *
 * @param bool|string $url
 * @param int $time
 */
function redirect($url = false, $time = 0)
{
    $url = $url ? $url : $_SERVER['HTTP_REFERER'];

    if (!headers_sent()) {
        if (!$time) {
            header("Location: {$url}");
        } else {
            header("refresh: $time; {$url}");
        }
    } else {
        echo "<script> setTimeout(function(){ window.location = '{$url}' }," . ($time *
                1000) . ")</script>";
    }
}

/**
 * Gets a content of a GET variable either by name or position in the path
 * @param $index
 *
 * @return mixed
 */
function getVar($index)
{
    $tree = explode("/", @$_GET['path']);
    $tree = array_filter($tree);

    if (is_int($index)) {
        $res = @$tree[$index - 1];
    } else {
        $res = @$_GET[$index];
    }
    return $res;
}

/**
 * Triggers a 404 error
 */
function send404()
{
    if (!headers_sent()) {
        header("HTTP/1.0 404 Not Found");

        die();
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

//product list model
function prdPL()
{
    ?>
    <select class="form-control margin-bottom itemName" style="width:500px" name="itemName"></select>
    <script type="text/javascript">
        $('.itemName').select2({
            placeholder: 'Select an item',
            ajax: {
                url: 'request/plist.php',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {

                    return {
                        results: data


                    };
                },
                cache: true
            }
        });


    </script>

    <?php
}

//product list model
function rprdPL()
{

    ?>
    <select class="form-control margin-bottom itemName" style="width:500px" name="itemName"></select>
    <script type="text/javascript">
        $('.itemName').select2({
            placeholder: 'Select an item',
            ajax: {
                url: 'request/plistf.php',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {

                    return {
                        results: data


                    };
                },
                cache: true
            }
        });


    </script>

    <?php

}

//customer search model
function cstPL()
{

    ?>
    <script>
        $(document).ready(function () {
            $("#search-box").keyup(function () {
                $.ajax({
                    type: "POST",
                    url: "request/customer.php",
                    data: 'keyword=' + $(this).val(),
                    beforeSend: function () {
                        $("#search-box").css("background", "#FFF url(images/loader.gif) no-repeat 265px");
                    },
                    success: function (data) {
                        $("#suggesstion-box").show();
                        $("#suggesstion-box").html(data);
                        $("#search-box").css("background", "#FFF");
                    }
                });
            });
            $('#ctable').DataTable({
                stateSave: true,
                "searching": false,
                "lengthChange": false,
                "language": {
                    "emptyTable": "Please enter some characters"
                }
            });
        });

    </script>
    <div class="frmSearch">
        <input type="text" id="search-box" placeholder="Enter Here Customer Name........" class="form-control">
    </div><br>
    <div class="tbl">
        <table id="ctable" class="table cell-border" cellspacing="0">
            <thead>
            <tr>

                <th>Name</th>
                <th class="hidden-xs">Address</th>
                <th>Phone</th>
                <th class="hidden-xs">Email</th>
                <th></th>

            </tr>
            </thead>
            <tbody id="suggesstion-box"></tbody>
        </table>
    </div><?php

}

//vendor list
function vndPL()
{

    ?>
    <script>
        $(document).ready(function () {
            $("#search-box").keyup(function () {
                $.ajax({
                    type: "POST",
                    url: "request/vendor.php",
                    data: 'keyword=' + $(this).val(),
                    beforeSend: function () {
                        $("#search-box").css("background", "#FFF url(images/loader.gif) no-repeat 265px");
                    },
                    success: function (data) {
                        $("#suggesstion-box").show();
                        $("#suggesstion-box").html(data);
                        $("#search-box").css("background", "#FFF");
                    }
                });
            });
            $('#ctable').DataTable({
                stateSave: true
            });
        });


    </script>
    <div class="frmSearch bottom-margin">
        <input type="text" id="search-box" placeholder="Enter Vendor Name" class="form-control">
    </div><br>
    <div class="tbl">
        <table id="ctable">
            <thead>
            <tr>

                <th>Name</th>
                <th class="hidden-xs">Address</th>
                <th>Phone</th>
                <th class="hidden-xs">Email</th>
                <th></th>

            </tr>
            </thead>
            <tbody id="suggesstion-box"></tbody>
        </table>
    </div><?php

}

?>