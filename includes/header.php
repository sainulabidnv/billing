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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "header.php")) {
    die("Internal Server Error!");
}
header("Content-type: text/html; charset=UTF-8");

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php

        echo $siteConfig['name'];

        ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link href="lib/css/main.css" rel="stylesheet">
    <script src="lib/js/modernizr-2.6.2.min.js"></script>
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/ui.min.css">
    <link rel="stylesheet" href="lib/css/skin-blue.min.css">
    <link href="lib/css/icon.css" rel="stylesheet">
    <link href="lib/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="lib/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="lib/css/styles.css" rel="stylesheet">
    <link href="lib/css/select2.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <script src="lib/js/jQuery-2.2.0.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="lib/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="lib/js/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="lib/js/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="lib/js/app.min.js"></script>
    <script src="lib/js/moment.js"></script>
    <script src="lib/js/jquery.dataTables.min.js"></script>
    <script src="lib/js/bootstrap-datetimepicker.min.js"></script>
    <script src="lib/js/chart.min.js"></script>
    <script src="lib/js/Chart.Line.js"></script>
    <script src="lib/js/ibasic.js"></script>
    <script src="lib/js/control.js"></script>
    <script src="lib/js/select2.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition skin-blue fixed sidebar-mini">
<span id="hdata" data-curr="<?php echo $siteConfig['curr']; ?>" data-vat="<?php echo $siteConfig['vatrate']; ?>" data-vat2="<?php echo $siteConfig['vatrate2']; ?>"></span>
<!-- Site wrapper -->
<div class="wrapper">
    <script type="text/javascript">
        $(document).ready(function () {
            $(function () {
                var current_page_URL = location.href;

                $(".sidebar-menu li a").each(function () {

                    if ($(this).attr("href") !== "#") {

                        var target_URL = $(this).prop("href");

                        if (target_URL == current_page_URL) {
                            // $('.sidebar a').parents('li, ul').removeClass('active');


                            $(this).closest('.treeview').addClass('active');
                            //$(this).closest('li').addClass('active');

                            return false;
                        }
                    }
                });
            });
        });
    </script>
    <header class="main-header">
        <!-- Logo -->
        <a href="index.php?rdp=home" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->

            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><?php

                echo $siteConfig['name']

                ?></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-menu"></span>

            </a>
            <a href="#" class="sidebar-toggle active" data-toggle="dropdown">
                Shortcuts<span class="icon-circle-down"></span>

            </a>

            <ul class="dropdown-menu bg-green">

                <li><a href="index.php?rdp=new-invoice"><span class="icon-file-text"></span> New Invoice</a></li>
                <li><a href="index.php?rdp=quote&op=create"><span class="icon-pencil"></span> New Quote</a></li>
                <?php if (($user->group_id) <= 2) {
                    echo '<li><a href="index.php?rdp=receipt&op=create"><span class="icon-drive"></span> New Receipt</a></li>';
                } ?>
                <li><a href="index.php?rdp=recurring&op=create"><span class="icon-paste"></span> Recurring Invoice</a>
                </li>

            </ul>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->

                    <!-- Notifications: style can be found in dropdown.less -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs"><?php

                  echo $user->first_name . '&nbsp; ' . $user->last_name

                  ?></span> &nbsp;<span class="icon-circle-down"></span>

                        </a>
                        <ul class="dropdown-menu">

                            <!-- inner menu: contains the actual data -->


                            <li>
                                <a href="index.php?rdp=user&op=edit" class="btn btn-info">Profile</a>
                            </li>
                            <li>
                                <a href="index.php?rdp=reports&op=employee&id=<?php

                                echo $user->id

                                ?>" class="btn btn-info">Track Your Sales</a>
                            </li>
                            <li><a href="request/employee.php?op=logout" class="btn btn-info">Sign out</a></li>
                        </ul>
                    </li>


                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->

            <!-- search form -->
            <form method="POST" action="index.php?rdp=manager" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="srn" class="form-control" placeholder="Search Invoice no..."><input
                            type="hidden"
                            name="search"
                            value="true">
                    <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><span
                            class="icon-search"></span>
                </button>
              </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">

                <li class="<?php

                if ($page == 'home') {
                    echo "active";
                }

                ?>"><a href="index.php?rdp=home"><span class="clp icon-meter"></span> Dashboard</a></li>
                <li id="sales" class="treeview">
                    <a href="#">
                        <span class="clp icon-clipboard"></span>
                        Sales <span class="caret"></span>

                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=new-invoice"><span class="icon-file-text"></span> New Invoice</a>
                        </li>
                        <li><a href="index.php?rdp=invoices"><span class="icon-files-empty"></span> Manage Invoices</a>
                        </li>
                        <li><a href="index.php?rdp=quote&op=create"><span class="icon-pencil"></span> New Quote</a></li>
                        <li><a href="index.php?rdp=quote"><span class="icon-book"></span> Manage Quotes</a></li>
                    </ul>
                </li>
                
                <li>
                    <?php

                    if (($user->group_id) <= 2)
                    {

                    ?>
                <li class="treeview ">
                    <a href="#">
                        <span class="clp icon-database"></span>
                        Purchase <span class="caret"></span>

                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=receipt&op=create"><span class="icon-drive"></span> New Purchase
                                Receipt</a>
                        </li>
                        <li><a href="index.php?rdp=receipt"><span class="icon-files-empty"></span> Manage Receipts</a>
                        </li>
                        
                    </ul>
                </li>
                
                <li class="treeview ">
                    <a href="#">
                        <span class="clp icon-database"></span>
                        Products <span class="caret"></span>

                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=product&op=cat"><span class="clp icon-list"></span>Products
                                Categories</a></li>

                        <li><a href="index.php?rdp=product&op=add"><span class="icon-plus"></span> Add New Product</a>
                        </li>

                        
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#"><span class="clp icon-user-tie"></span> Clients <span class="caret"></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=customer"><span class="icon-address-book"></span> Manage Customer</a>
                        </li>

                        <li><a href="index.php?rdp=customer&op=add"><span class="icon-user-plus"></span> Add New
                                Customer</a></li>

                    </ul>
                </li>
                
                <li class="treeview">
                    <a href="index.php?rdp=vendor"><span class="icon-truck"></span> Suppliers/Vendors</a>
                    
                </li>
                
                <li class="treeview">
                    <a href="#"><span class="clp icon-library"></span> Banking <span class="caret"></span></a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=accounts"><span class="icon-list"></span>Accounts</a></li>
                        <li><a href="index.php?rdp=transactions"><span class="icon-drive"></span>Transactions</a></li>

                    </ul>
                </li>
                <li>
                    <a href="index.php?rdp=balance"><span class="clp icon-coin-dollar"></span> Income & Expenses</span>
                    </a>

                </li>

                <li>
                    <a href="index.php?rdp=notes"><span class="clp icon-file-text2"></span>Notes</span>
                    </a>

                </li>

                <li class="treeview ">
                    <a href="#">
                        <span class="clp icon-database"></span>
                        Damaged Stock <span class="caret"></span>

                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=damage"><span class="clp icon-list"></span>Receipts List</a></li>
                        <li><a href="index.php?rdp=damage&op=create"><span class="icon-plus"></span> Create New Receipt</a>
                        </li>
                        <li><a href="index.php?rdp=damage&op=ilist"><span class="clp icon-list"></span>All Items
                                List</a></li>
                    </ul>
                </li>
                <li class="treeview ">
                    <a href="#">
                        <span class="clp icon-flag"></span>
                        Return <span class="caret"></span>

                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=return"><span class="clp icon-list"></span>Receipts List</a></li>
                        <li><a href="index.php?rdp=return&op=create"><span class="icon-plus"></span> Create New Receipt</a>
                        </li>
                        <li><a href="index.php?rdp=return&op=ilist"><span class="clp icon-list"></span>All Items
                                List</a></li>
                    </ul>
                </li>
                <?php

                }
				?>
				
                <li id="sales" class="treeview">
                    <a href="#">
                        <span class="clp icon-paste"></span>
                        Recurring Sales <span class="caret"></span>

                    </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=recurringhome"><span class="icon-display"></span> Dashboard</a>
                        </li>
                        <li><a href="index.php?rdp=recurring&op=create"><span class="icon-file-text"></span> New
                                Recurring Invoice</a>
                        </li>
                        <li><a href="index.php?rdp=recurring"><span class="icon-files-empty"></span> Recurring Invoices</a>
                        </li>
                        <?php

                        if (($user->group_id) <= 2) {

                            ?>
                            <li><a href="index.php?rdp=services"><span class="clp icon-list"></span> Manage Services</a>
                            </li>
                            <li><a href='index.php?rdp=rec-reports'><span class="icon-stats-bars"></span> Recurring
                                    Sales
                                    Reports</a></li>
                        <?php } ?>
                    </ul>
                </li>
                
				
				<?php 
                if (($user->group_id) == 1)
                {

                ?>

                <li class="treeview"><a href='#'><span class="clp icon-stats-dots"></span> Reports &amp; Data <span
                                class="caret"></span></a>
                    <ul class="treeview-menu">
                        <li><a href='index.php?rdp=reports'><span class="icon-stats-bars"></span> Reports Dashboard</a>
                        </li>
                        <li><a href='index.php?rdp=reports&op=monthly'><span class="icon-file-text2"></span> Sales
                                Reports</a></li>
                        <li><a href='index.php?rdp=reports&op=customer'><span class="icon-users"></span> Sales
                                Customerwise</a></li>
                        <li><a href='index.php?rdp=reports&op=employee'><span class="icon-user-check"></span> Sales
                                Employeewise</a></li>
                        <li><a href="index.php?rdp=profit"><span class="icon-file-text"></span> Profit Calculator</a>
                        </li>
                        <li><a href='index.php?rdp=export'><span class="icon-download"></span>Export &amp; Download Data</a>
                        </li>
                        <li><a href="index.php?rdp=settings&op=database"><span class="icon-database"></span>
                                Database Management</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><span class="icon-cog"></span> Settings <span class="caret"></span> </a>
                    <ul class="treeview-menu">
                        <li><a href="index.php?rdp=user"><span class="icon-users"></span> Manage Employee</a></li>
                        <li><a href="index.php?rdp=settings&op=company"><span class="icon-cogs"></span> Company Settings</a>
                        </li>
                        <li><a href="index.php?rdp=settings&op=billing"><span class="icon-paste"></span> Billing &amp;
                                TAX</a></li>
                        <li><a href="index.php?rdp=settings&op=date"><span class="icon-earth"></span> Date and
                                Format</a></li>
                        <li><a href="index.php?rdp=settings&op=gateway"><span class="icon-credit-card"></span> Payment
                                Gateway</a></li>
                        <li><a href="index.php?rdp=settings&op=smtp"><span class="icon-mail4"></span> SMTP and Email</a>
                        </li>

                    </ul>
                    <?php

                    }

                    ?>
                <li class="treeview"><a href='#'><span class="clp icon-info"></span> About</a>
                    <ul class="treeview-menu">
                        <li><a href='index.php?rdp=info&op=support'><span class="icon-lifebuoy"></span> Support</a></li>
                        <li><a href='index.php?rdp=info&op=tips'><span class="icon-magic-wand"></span> Tips</a></li>
                        <li><a href='index.php?rdp=info'><span class="icon-keyboard"></span> About</a></li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper">
        <section class="content">