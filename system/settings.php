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
if (stristr(htmlentities($_SERVER['PHP_SELF']), "settings.php"))
{
    die("Internal Server Error!");
}
if ($user->group_id > 1)
{
    die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
}
if (isset($_GET['op']))
{
    $op = $_GET['op'];
} else
{
    $op = "";
}
if (isset($_GET['id']))
{
    $id = $_GET['id'];
} else
{
    $id = 0;
}

switch ($op)
{
    case "company":
        company();
        break;
    case "billing":
        billing();
        break;
    case "date":
        dtz();
        break;
    case "terms":
        bterms();
        break;
    case "smtp":
        smtpmail();
        break;
    case "gateway":
        pgateway();
        break;
    case "database":
        dbase();
        break;

}
function company()
{



    global $db;
    //$query = "SELECT cname,address,address2,phone,email FROM panel";

    $row = $db->select('panel',array('cname,address,address2,phone,email'),null)->results();
    if ($row)
    {

        $cname = $row['cname'];
        $cadd = $row['address'];
        $cadd2 = $row['address2'];
        $contact = $row['phone'];
        $email = $row['email'];


    }

?>
<link rel="stylesheet" href="lib/css/jquery.fileupload.css">
<script type="text/javascript">
 		$(document).on('click', "#action_update_company", function(e) {
		e.preventDefault();
		updateCompany();
	});  	function updateCompany() {
        var errorNum = farmCheck();
        

		if (errorNum > 0) {
		    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
		} else{
   		var $btn = $("#action_update_company").button("loading");
   		

        jQuery.ajax({

        	url: 'request/rsetting.php',
            type: 'POST', 
            data: $("#update_company").serialize(),
            dataType: 'json', 
            success: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
				$("html, body").animate({ scrollBottom: $('#notify').offset().top }, 1000);
				
					$btn.button("reset");
				

			},
			error: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
				$("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
				$btn.button("reset");
			} 
    	});

   	}}
  </script>
	
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Edit <?php

    echo $cname;

?> Details</h4>
			</div>

			<div class="panel-body form-group form-group-sm">
				<form method="post" id="update_company">
					<input type="hidden" name="act" value="update_company">
							<div class="row">
					<div class="col-xs-12"><div class="form-group">
                                               <label class="col-sm-4 control-label margin-bottom">Company Name</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Comapny Name" class="form-control margin-bottom  required" name="company_name" value="<?php

    echo $cname;

?>">
                                                </div>
                                            </div>

											<div class="form-group">
                                               <label class="col-sm-4 control-label margin-bottom">Company Address</label>
                                                <div class="col-sm-6">
												<input type="text" placeholder="Address Line1" class="form-control margin-bottom  required" name="c_address" value="<?php

    echo $cadd;

?>">
                                                    
                                                </div></div>
												<div class="form-group">
                                               <label class="col-sm-4 control-label margin-bottom">Company Address Line2</label>
                                                <div class="col-sm-6">
												<input type="text" placeholder="Address Line2" class="form-control margin-bottom  required" name="c_address2" value="<?php

    echo $cadd2;

?>">
                                                    
                                                </div></div>

												<div class="form-group">
                                               <label class="col-sm-4 control-label margin-bottom">Contact Number</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Contact Number" class="form-control margin-bottom  required" name="numberc" value="<?php

    echo $contact;

?>">
                                                </div>

                                            </div><div class="form-group">
											      <label class="col-sm-4 control-label margin-bottom">Email</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Email" class="form-control margin-bottom  required" name="emailc" value="<?php

    echo $email;

?>">
                                                </div> </div>	<div class="form-group">
                                              <label class="col-sm-4 control-label margin-bottom"></label>
                                                <div class="col-sm-6">
                                                   <input type="submit" id="action_update_company" class="btn btn-success margin-bottom" value="Update" data-loading-text="Updating...">
                                                </div>
                                            </div>
												</div></div></form><div id="notify" class="alert alert-success margin-bottom" style="display:none;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<div class="message"></div>
</div>
												<div class="panel panel-default margin-bottom">
			<div class="panel-heading">
				<h4>Change Logo</h4>
			</div><div class="panel-body form-group form-group-sm">	
			<div id="imgArea"><img src="images/logo.jpg" style='max-width:400px;'></div><br>
			<span class="btn btn-primary fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select Only JPG image file...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
    <br>
    <br>
    <!-- The global progress bar -->
    <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <!-- The container for the uploaded files -->
    
    </div><div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Logo File Instruction</h3>
        </div>
        <div class="panel-body">
            <ul>
                <li>The maximum file size for logo image is <strong>1 MB</strong>.</li>
                <li>Only image files (<strong>JPG,JPEG</strong>) are allowed for logo.</li>
                <li>This logo will appear on printable invoices, quotes and receipts. </li>
                <li>Size should be at least 200x200 and not be large than 500x500.</li>
                <li>Please use good quality image.</li>

              
            </ul>
        </div>
    </div>
	<script src="lib/js/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="lib/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="lib/js/jquery.fileupload.js"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = '<?php echo SITE ?>/includes/logo.php';
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                //$('<p/>').text(file.name).appendTo('#files');
$("#imgArea img").load(function() {
             $(this).hide();
             $(this).fadeIn('slow');
              }).attr('src', 'images/logo.jpg?'+ new Date().getTime());
            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>
	</div></div></div>
<?php

}
function billing()
{
    global $db;
    
    $row = $db->select('panel',array('cvatno,vatr,vatr2,vatst,vinc,crncy,fcrncy,pref'),null)->results();
    $cvatno = $row['cvatno'];
    $vatr = $row['vatr'];
	$vatr2 = $row['vatr2'];
    $vatst = $row['vatst'];
    $vinc = $row['vinc'];
    $curr = $row['crncy'];
    $fcurr = $row['fcrncy'];
    $pref = $row['pref'];
    $show_payment_mode = get_settings('show_payment_mode');
?>
<script type="text/javascript">
 		$(document).on('click', "#action_update_billing", function(e) {
		e.preventDefault();
		updateCompany();
	});  	function updateCompany() {
		var errorNum = farmCheck();

		if (errorNum > 0) {
		    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
		} else{
   		var $btn = $("#action_update_billing").button("loading");
   		

        jQuery.ajax({

        	url: 'request/rsetting.php',
            type: 'POST', 
            data: $("#update_billing").serialize(),
            dataType: 'json', 
            success: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
				$("html, body").animate({ scrollBottom: $('#notify').offset().top }, 1000);
				
					$btn.button("reset");
				

			},
			error: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
				$("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
				$btn.button("reset");
			} 
    	});

   	}}
  </script>
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Billing and TAX Settings</h4>
			</div>

			<div class="panel-body form-group form-group-sm">
				<form method="post" id="update_billing">
					<input type="hidden" name="act" value="update_billing">
					
												<div class="form-group">
                                              <label class="col-sm-4 control-label margin-bottom">Currency</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Currency" class="form-control margin-bottom required" name="currency"  value="<?php

    echo $curr;

?>">
                                                </div>
                                            </div>
														<div class="form-group">
                                              <label class="col-sm-4 control-label margin-bottom">Currency Format</label>
                                                <div class="col-sm-6">
												<select name="fcurrency" class="form-control margin-bottom">
													<option value="0" selected>Default 00,000.00</option>
													<option value="1">00.000,00</option>
												</select>
                                                                                                  </div>
                                            </div>
                                          
													<div class="form-group">
                                              <label class="col-sm-4 control-label margin-bottom">Invoice No. Prefix</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Invoice Prefix" class="form-control margin-bottom required" name="prefix"  value="<?php

    echo $pref;

?>">
                                                </div>
                                            </div>
                                          
											<div class="form-group">
                                              <label class="col-sm-4 control-label margin-bottom">Company TAX/VAT NO</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Company TAX/VAT NO" class="form-control margin-bottom required" name="cvatno"  value="<?php

    echo $cvatno;

?>">
                                                </div>
                                            </div>
											<div class="form-group">
                                                <label class="col-sm-4 control-label margin-bottom">Enable TAX/VAT</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group margin-bottom">
								                        <input type="checkbox" name="vstat" value="1" <?php if ($vatst == 1)  { ?> checked<?php } ?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label margin-bottom">Show Payment mode in bill</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group margin-bottom">
								                        <input type="checkbox" name="show_payment_mode" value="1" <?php if ($show_payment_mode == 1)  { ?> checked<?php } ?>>
                                                    </div>
                                                </div>
                                            </div>

											<div class="form-group">
                                                <label class="col-sm-4 control-label margin-bottom">SGST  RATE</label>
                                                <div class="col-sm-6">  
                                                	<div class="col-sm-3 input-group margin-bottom"> <input type="number" name="vrat" class="form-control required" placeholder="0.00" aria-describedby="sizing-addon1"  value="<?php echo $vatr;?>"><span class="input-group-addon">%</span></div>
                                             	</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label margin-bottom">CGST  RATE</label>
                                                <div class="col-sm-6">  
                                                	<div class="col-sm-3 input-group margin-bottom"> <input type="number" name="vrat2" class="form-control required" placeholder="0.00" aria-describedby="sizing-addon1"  value="<?php echo $vatr2;?>"><span class="input-group-addon">%</span></div>
                                             	</div>
                                            </div>
											<div class="form-group">
                                                <label class="col-sm-4 control-label"></label>
                                                <div class="col-sm-6">
                                                    <div class="input-group margin-bottom"> <label class="control-label">TAX/VAT Inclusive on Total&nbsp; </label>
								<input type="checkbox" name="vinc" value="1" <?php

    if ($vinc == 1)
    {

?> checked<?php

    }
	

?>><div class="alert alert-info">eg. Price is $100 and 12% tax<br>Sub Total: $89.44+10.73=Total $100</div>
							</div>

                                                </div>
                                            </div><div class="form-group ">
                                              <label class="col-sm-4 control-label"></label>
                                                <div class="col-sm-6">
                                                   <input type="submit" id="action_update_billing" class="btn btn-success  margin-bottom" value="Update" data-loading-text="Success"><div id="notify" class="alert alert-success  margin-bottom" style="display:none;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<div class="message"></div>
</div>
                                                </div>
                                            </div>	<br><div class="form-group">
                                              <label class="col-sm-4 control-label margin-bottom">Billing Terms</label>
                                                <div class="col-sm-6"><br>
                                                  <a href="index.php?rdp=settings&op=terms" class="btn btn-danger btn-lg">Edit Invoice/Quote/Receipt Terms</a>
                                                </div>
                                            </div></form>
										
                                          
											</div></div>
</div>
	</div>


<?php

}
function dtz()
{
    global $db, $siteConfig;
    $row = $db->select('panel',array('dfomat,zone'),null)->results();

    $dformat = $row['dfomat'];
    $dzone = $row['zone'];

?><script type="text/javascript">
 		$(document).on('click', "#action_update_date", function(e) {
		e.preventDefault();
		updateCompany();
	});  	function updateCompany() {
		var errorNum = farmCheck();

		if (errorNum > 0) {
		    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
		} else{
   		var $btn = $("#action_update_date").button("loading");
   		

        jQuery.ajax({

        	url: 'request/rsetting.php',
            type: 'POST', 
            data: $("#update_date").serialize(),
            dataType: 'json', 
            success: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
				$("html, body").animate({ scrollBottom: $('#notify').offset().top }, 1000);
				
					$btn.button("reset");
				

			},
			error: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
				$("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
				$btn.button("reset");
			} 
    	});

   	}}
  </script><div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Date format and Timezone Settings</h4>
			</div>

			<div class="panel-body form-group form-group-sm">
				<form method="post" id="update_date">	<div class="form-group">
                                                <label class="col-sm-4 control-label ">DATE FORMAT</label>
                                                <div class="col-sm-6 margin-bottom">
                                                <?php

    echo "<strong>Current Date Format is " . $siteConfig['dformat2'] .
        "</strong><br><br>";

?>
													<select  class="form-control" name="dformat">
													 
														<option value="1">DD/MM/YYYY</option>
														<option value="2">YYYY/MM/DD</option>
														<option value="3">MM/DD/YYYY</option>
													</select>
                                                </div>
                                            </div>
					<input type="hidden" name="act" value="update_date">							<div class="form-group">
                                                <label class="col-sm-4 control-label">Time zone</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group margin-bottom">
								<select class="form-control" name="dzone">
								<option value="<?php

    echo $dzone;

?>" selected><?php

    echo $dzone;

?></option>
    <option value="Pacific/Midway">(UTC-11:00) Midway Island</option>
    <option value="Pacific/Samoa">(UTC-11:00) Samoa</option>
    <option value="Pacific/Honolulu">(UTC-10:00) Hawaii</option>
    <option value="US/Alaska">(UTC-09:00) Alaska</option>
    <option value="America/Los_Angeles">(UTC-08:00) Pacific Time (US &amp; Canada)</option>
    <option value="America/Tijuana">(UTC-08:00) Tijuana</option>
    <option value="US/Arizona">(UTC-07:00) Arizona</option>
    <option value="America/Chihuahua">(UTC-07:00) Chihuahua</option>
    <option value="America/Chihuahua">(UTC-07:00) La Paz</option>
    <option value="America/Mazatlan">(UTC-07:00) Mazatlan</option>
    <option value="US/Mountain">(UTC-07:00) Mountain Time (US &amp; Canada)</option>
    <option value="America/Managua">(UTC-06:00) Central America</option>
    <option value="US/Central">(UTC-06:00) Central Time (US &amp; Canada)</option>
    <option value="America/Mexico_City">(UTC-06:00) Guadalajara</option>
    <option value="America/Mexico_City">(UTC-06:00) Mexico City</option>
    <option value="America/Monterrey">(UTC-06:00) Monterrey</option>
    <option value="Canada/Saskatchewan">(UTC-06:00) Saskatchewan</option>
    <option value="America/Bogota">(UTC-05:00) Bogota</option>
    <option value="US/Eastern">(UTC-05:00) Eastern Time (US &amp; Canada)</option>
    <option value="US/East-Indiana">(UTC-05:00) Indiana (East)</option>
    <option value="America/Lima">(UTC-05:00) Lima</option>
    <option value="America/Bogota">(UTC-05:00) Quito</option>
    <option value="Canada/Atlantic">(UTC-04:00) Atlantic Time (Canada)</option>
    <option value="America/Caracas">(UTC-04:30) Caracas</option>
    <option value="America/La_Paz">(UTC-04:00) La Paz</option>
    <option value="America/Santiago">(UTC-04:00) Santiago</option>
    <option value="Canada/Newfoundland">(UTC-03:30) Newfoundland</option>
    <option value="America/Sao_Paulo">(UTC-03:00) Brasilia</option>
    <option value="America/Argentina/Buenos_Aires">(UTC-03:00) Buenos Aires</option>
    <option value="America/Argentina/Buenos_Aires">(UTC-03:00) Georgetown</option>
    <option value="America/Godthab">(UTC-03:00) Greenland</option>
    <option value="America/Noronha">(UTC-02:00) Mid-Atlantic</option>
    <option value="Atlantic/Azores">(UTC-01:00) Azores</option>
    <option value="Atlantic/Cape_Verde">(UTC-01:00) Cape Verde Is.</option>
    <option value="Africa/Casablanca">(UTC+00:00) Casablanca</option>
    <option value="Europe/London">(UTC+00:00) Edinburgh</option>
    <option value="Etc/Greenwich">(UTC+00:00) Greenwich Mean Time : Dublin</option>
    <option value="Europe/Lisbon">(UTC+00:00) Lisbon</option>
    <option value="Europe/London">(UTC+00:00) London</option>
    <option value="Africa/Monrovia">(UTC+00:00) Monrovia</option>
    <option value="UTC">(UTC+00:00) UTC</option>
    <option value="Europe/Amsterdam">(UTC+01:00) Amsterdam</option>
    <option value="Europe/Belgrade">(UTC+01:00) Belgrade</option>
    <option value="Europe/Berlin">(UTC+01:00) Berlin</option>
    <option value="Europe/Berlin">(UTC+01:00) Bern</option>
    <option value="Europe/Bratislava">(UTC+01:00) Bratislava</option>
    <option value="Europe/Brussels">(UTC+01:00) Brussels</option>
    <option value="Europe/Budapest">(UTC+01:00) Budapest</option>
    <option value="Europe/Copenhagen">(UTC+01:00) Copenhagen</option>
    <option value="Europe/Ljubljana">(UTC+01:00) Ljubljana</option>
    <option value="Europe/Madrid">(UTC+01:00) Madrid</option>
    <option value="Europe/Paris">(UTC+01:00) Paris</option>
    <option value="Europe/Prague">(UTC+01:00) Prague</option>
    <option value="Europe/Rome">(UTC+01:00) Rome</option>
    <option value="Europe/Sarajevo">(UTC+01:00) Sarajevo</option>
    <option value="Europe/Skopje">(UTC+01:00) Skopje</option>
    <option value="Europe/Stockholm">(UTC+01:00) Stockholm</option>
    <option value="Europe/Vienna">(UTC+01:00) Vienna</option>
    <option value="Europe/Warsaw">(UTC+01:00) Warsaw</option>
    <option value="Africa/Lagos">(UTC+01:00) West Central Africa</option>
    <option value="Europe/Zagreb">(UTC+01:00) Zagreb</option>
    <option value="Europe/Athens">(UTC+02:00) Athens</option>
    <option value="Europe/Bucharest">(UTC+02:00) Bucharest</option>
    <option value="Africa/Cairo">(UTC+02:00) Cairo</option>
    <option value="Africa/Harare">(UTC+02:00) Harare</option>
    <option value="Europe/Helsinki">(UTC+02:00) Helsinki</option>
    <option value="Europe/Istanbul">(UTC+02:00) Istanbul</option>
    <option value="Asia/Jerusalem">(UTC+02:00) Jerusalem</option>
    <option value="Europe/Helsinki">(UTC+02:00) Kyiv</option>
    <option value="Africa/Johannesburg">(UTC+02:00) Pretoria</option>
    <option value="Europe/Riga">(UTC+02:00) Riga</option>
    <option value="Europe/Sofia">(UTC+02:00) Sofia</option>
    <option value="Europe/Tallinn">(UTC+02:00) Tallinn</option>
    <option value="Europe/Vilnius">(UTC+02:00) Vilnius</option>
    <option value="Asia/Baghdad">(UTC+03:00) Baghdad</option>
    <option value="Asia/Kuwait">(UTC+03:00) Kuwait</option>
    <option value="Europe/Minsk">(UTC+03:00) Minsk</option>
    <option value="Africa/Nairobi">(UTC+03:00) Nairobi</option>
    <option value="Asia/Riyadh">(UTC+03:00) Riyadh</option>
    <option value="Europe/Volgograd">(UTC+03:00) Volgograd</option>
    <option value="Asia/Tehran">(UTC+03:30) Tehran</option>
    <option value="Asia/Muscat">(UTC+04:00) Abu Dhabi</option>
    <option value="Asia/Baku">(UTC+04:00) Baku</option>
    <option value="Europe/Moscow">(UTC+04:00) Moscow</option>
    <option value="Asia/Muscat">(UTC+04:00) Muscat</option>
    <option value="Europe/Moscow">(UTC+04:00) St. Petersburg</option>
    <option value="Asia/Tbilisi">(UTC+04:00) Tbilisi</option>
    <option value="Asia/Yerevan">(UTC+04:00) Yerevan</option>
    <option value="Asia/Kabul">(UTC+04:30) Kabul</option>
    <option value="Asia/Karachi">(UTC+05:00) Islamabad</option>
    <option value="Asia/Karachi">(UTC+05:00) Karachi</option>
    <option value="Asia/Tashkent">(UTC+05:00) Tashkent</option>
    <option value="Asia/Calcutta">(UTC+05:30) Chennai</option>
    <option value="Asia/Kolkata">(UTC+05:30) Kolkata</option>
    <option value="Asia/Calcutta">(UTC+05:30) Mumbai</option>
    <option value="Asia/Calcutta">(UTC+05:30) New Delhi</option>
    <option value="Asia/Calcutta">(UTC+05:30) Sri Jayawardenepura</option>
    <option value="Asia/Katmandu">(UTC+05:45) Kathmandu</option>
    <option value="Asia/Almaty">(UTC+06:00) Almaty</option>
    <option value="Asia/Dhaka">(UTC+06:00) Astana</option>
    <option value="Asia/Dhaka">(UTC+06:00) Dhaka</option>
    <option value="Asia/Yekaterinburg">(UTC+06:00) Ekaterinburg</option>
    <option value="Asia/Rangoon">(UTC+06:30) Rangoon</option>
    <option value="Asia/Bangkok">(UTC+07:00) Bangkok</option>
    <option value="Asia/Bangkok">(UTC+07:00) Hanoi</option>
    <option value="Asia/Jakarta">(UTC+07:00) Jakarta</option>
    <option value="Asia/Novosibirsk">(UTC+07:00) Novosibirsk</option>
    <option value="Asia/Hong_Kong">(UTC+08:00) Beijing</option>
    <option value="Asia/Chongqing">(UTC+08:00) Chongqing</option>
    <option value="Asia/Hong_Kong">(UTC+08:00) Hong Kong</option>
    <option value="Asia/Krasnoyarsk">(UTC+08:00) Krasnoyarsk</option>
    <option value="Asia/Kuala_Lumpur">(UTC+08:00) Kuala Lumpur</option>
    <option value="Australia/Perth">(UTC+08:00) Perth</option>
    <option value="Asia/Singapore">(UTC+08:00) Singapore</option>
    <option value="Asia/Taipei">(UTC+08:00) Taipei</option>
    <option value="Asia/Ulan_Bator">(UTC+08:00) Ulaan Bataar</option>
    <option value="Asia/Urumqi">(UTC+08:00) Urumqi</option>
    <option value="Asia/Irkutsk">(UTC+09:00) Irkutsk</option>
    <option value="Asia/Tokyo">(UTC+09:00) Osaka</option>
    <option value="Asia/Tokyo">(UTC+09:00) Sapporo</option>
    <option value="Asia/Seoul">(UTC+09:00) Seoul</option>
    <option value="Asia/Tokyo">(UTC+09:00) Tokyo</option>
    <option value="Australia/Adelaide">(UTC+09:30) Adelaide</option>
    <option value="Australia/Darwin">(UTC+09:30) Darwin</option>
    <option value="Australia/Brisbane">(UTC+10:00) Brisbane</option>
    <option value="Australia/Canberra">(UTC+10:00) Canberra</option>
    <option value="Pacific/Guam">(UTC+10:00) Guam</option>
    <option value="Australia/Hobart">(UTC+10:00) Hobart</option>
    <option value="Australia/Melbourne">(UTC+10:00) Melbourne</option>
    <option value="Pacific/Port_Moresby">(UTC+10:00) Port Moresby</option>
    <option value="Australia/Sydney">(UTC+10:00) Sydney</option>
    <option value="Asia/Yakutsk">(UTC+10:00) Yakutsk</option>
    <option value="Asia/Vladivostok">(UTC+11:00) Vladivostok</option>
    <option value="Pacific/Auckland">(UTC+12:00) Auckland</option>
    <option value="Pacific/Fiji">(UTC+12:00) Fiji</option>
    <option value="Pacific/Kwajalein">(UTC+12:00) International Date Line West</option>
    <option value="Asia/Kamchatka">(UTC+12:00) Kamchatka</option>
    <option value="Asia/Magadan">(UTC+12:00) Magadan</option>
    <option value="Pacific/Fiji">(UTC+12:00) Marshall Is.</option>
    <option value="Asia/Magadan">(UTC+12:00) New Caledonia</option>
    <option value="Asia/Magadan">(UTC+12:00) Solomon Is.</option>
    <option value="Pacific/Auckland">(UTC+12:00) Wellington</option>
    <option value="Pacific/Tongatapu">(UTC+13:00) Nuku'alofa</option>
</select> </div><div class="alert alert-info margin-bottom">Incorrect time zone may cause for wrong dates in invoices</div>
							

                                                </div>
                                            </div>
													<div class="form-group">
                                              <label class="col-sm-4 control-label"></label>
                                                <div class="col-sm-6">
                                                   <input type="submit" id="action_update_date" class="btn btn-success margin-bottom" value="Update" data-loading-text="Updating...">
                                                </div>
                                            </div>
						
					
						
					
				
				</form>
			</div><div id="notify" class="alert alert-success" style="display:none;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<div class="message"></div></div></div></div><?php

}
function dbase()
{

?>
<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Database Manager</h4>
					<div class="clear"></div>
				</div>
				<div class="panel-body form-group form-group-sm">
					<div class="row"><div class="panel-body"><p>There is a separate 3rd party module added in Invoice Express for Database management with this module you can manage you database. This module is a complete A to Z solution for <strong>database management</strong>. You can create and download the <strong>backup database</strong>. <div class="alert alert-danger" >
						Be careful this is very sensitive section. If you do not know what should I do than please do not use this section.
					</div><div class="alert alert-success" >If you not like to use this module, its okay and optional !! Software will work normally without any issue.</div><a href="lib/adminer/index.php" class="btn btn-primary btn-lg">Launch Database manager</a></div>
			

	</div></div></div></div></div><?php

}
function bterms()
{
    global $db;
    $row2 = $db->select('billing_terms')->results();
    $iterm = $row2['terms'];
    $qterm = $row2['qterms'];
    $rterm = $row2['rterms'];
    $ifoot = $row2['footer']

?><script type="text/javascript">
$(document).on('click', "#action_update_terms", function(e) {
		e.preventDefault();
		updateTerms();
	});  	function updateTerms() {
		var errorNum = farmCheck();

		if (errorNum > 0) {
		    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
		} else{
   		var $btn = $("#action_update_terms").button("loading");
   		

        jQuery.ajax({

        	url: 'request/rsetting.php',
            type: 'POST', 
            data: $("#update_terms").serialize(),
            dataType: 'json', 
            success: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
				$("html, body").animate({ scrollBottom: $('#notify').offset().top }, 1000);
				
					$btn.button("reset");
				

			},
			error: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
				$("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
				$btn.button("reset");
			} 
    	});

   	}}
	</script><div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Edit Billing Terms</h4>
			</div>
<div class="panel-body form-group form-group-sm">
				<form method="post" id="update_terms">
					<input type="hidden" name="act" value="update_terms">   
												<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">Invoice Terms</label>
                                                <div class="col-sm-8"><textarea name="terms" rows="4" class="form-control margin-bottom required"><?php

    echo $iterm;

?></textarea>
                                                    
                                                </div>
                                            </div>
											<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">Quote Terms</label>
                                                <div class="col-sm-8"><textarea name="qterms" rows="4" class="form-control margin-bottom required"><?php

    echo $qterm;

?></textarea>
                                                    
                                                </div>
                                            </div>
											<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">Receipt Terms</label>
                                                <div class="col-sm-8"><textarea name="rterms" rows="4" class="form-control margin-bottom required"><?php

    echo $rterm;

?></textarea>
                                                    
                                                </div>
                                            </div>

                                          
													<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">Footer Note</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="Invoice Prefix" class="form-control margin-bottom required" name="footer"  value="<?php

    echo $ifoot;

?>"><div class="alert alert-info margin-bottom"><strong>Tips:</strong>Keep short length of footer note</div>
                                                </div>
                                            </div><div class="form-group">
                                              <label class="col-sm-4 control-label"></label>
                                                <div class="col-sm-6"><br>
                                                   <input type="submit" id="action_update_terms" class="btn btn-success" value="Update" data-loading-text="Success">
                                                </div>
                                            </div></form></div><div id="notify" class="alert alert-success" style="display:none;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<div class="message"></div>
		</div></div></div></div>
	<?php

}
function smtpmail()
{
    global $db;
    $row2 = $db->select('sys_smtp')->results();
    $host = $row2['Host'];
    $port = $row2['Port'];
    $auth = $row2['Auth'];
    $username = $row2['Username'];
    $password = $row2['password'];
    $sender = $row2['Sender'];

?><script type="text/javascript">
$(document).on('click', "#action_update_smtp", function(e) {
		e.preventDefault();
		updateSmtp();
	});  	function updateSmtp() {
		var errorNum = farmCheck();

		if (errorNum > 0) {
		    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
		} else{
   		var $btn = $("#action_update_smtp").button("loading");
   		

        jQuery.ajax({

        	url: 'request/rsetting.php',
            type: 'POST', 
            data: $("#update_smtp").serialize(),
            dataType: 'json', 
            success: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
				$("html, body").animate({ scrollBottom: $('#notify').offset().top }, 1000);
				
					$btn.button("reset");
				

			},
			error: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
				$("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
				$btn.button("reset");
			} 
    	});

   	}}
	</script>
	
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Edit SMTP Server Settings</h4>
			</div>
<div class="panel-body form-group form-group-sm"><div class="alert alert-info"><strong>Note:</strong>These settings are required to send successfully billing regarding emails to client</div>
				<form method="post" id="update_smtp">
					<input type="hidden" name="act" value="update_smtp">
							<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">SMTP Host</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="smtp.example.com" class="form-control margin-bottom required" name="host"  value="<?php

    echo $host;

?>">
                                                </div>
                                            </div>
		<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">SMTP Port</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="25" class="form-control margin-bottom required" name="port"  value="<?php

    echo $port;

?>">
                                                </div>
                                            </div>
										<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">Server Authorization</label>
                                                <div class="col-sm-8">
                                                    <select name="auth" class="form-control margin-bottom required">
														<?php

    if ($auth == 'true')
    {

?><option value="true" selected>Yes</option>
														<option value="false">No</option><?php

    } else
    {

?><option value="false" selected>No</option>
														<option value="true">Yes</option><?php

    }

?>
                                                    </select><br>
                                                </div>
                                            </div>
													<div class="form-group">
                                              <label class="col-xs-2 control-label margin-bottom">SMTP User name</label>
                                                <div class="col-xs-8">
                                                    <input type="text" placeholder="username" class="form-control margin-bottom required" name="username"  value="<?php

    echo $username;

?>">
                                                </div>
                                            </div>
													<div class="form-group">
                                              <label class="col-xs-2 control-label margin-bottom">SMTP Password</label>
                                                <div class="col-xs-8">
                                                    <input type="password" placeholder="password" class="form-control margin-bottom required" name="password"  value="<?php

    echo $password;

?>">
                                                </div>
                                            </div>
													<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom">Sender Email</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="abc@example.com" class="form-control margin-bottom required" name="sender"  value="<?php

    echo $sender;

?>">
                                                </div>
                                            </div>
												

                                          
													<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom"></label>
                                                <div class="col-sm-6"><br>
                                                   <input type="submit" id="action_update_smtp" class="btn btn-success" value="Update" data-loading-text="Success">
                                                </div>
                                            </div></form></div><div id="notify" class="alert alert-success" style="display:none;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<div class="message"></div>
		</div></div>
	<?php

}
function pgateway()
{
    global $db;
	$query="SELECT * FROM payment";
$pay = $db->pdoQuery($query)->results();
$ppmail=$pay[5]['optvalue'];
$enablep=$pay[4]['optvalue'];
$pkey=$pay[1]['optvalue'];
$curr=$pay[3]['optvalue'];
$skey=$pay[0]['optvalue'];
$email=$pay[2]['optvalue'];
$paypalcurr=$pay[6]['optvalue'];

?><script type="text/javascript">
$(document).on('click', "#action_update_smtp", function(e) {
		e.preventDefault();
		updateSmtp();
	});  	function updateSmtp() {
		var errorNum = farmCheck();

		if (errorNum > 0) {
		    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
		    $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
		    $("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
		} else{
   		var $btn = $("#action_update_smtp").button("loading");
   		

        jQuery.ajax({

        	url: 'request/rsetting.php',
            type: 'POST', 
            data: $("#update_smtp").serialize(),
            dataType: 'json', 
            success: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
				$("html, body").animate({ scrollBottom: $('#notify').offset().top }, 1000);
				
					$btn.button("reset");
				

			},
			error: function(data){
				$("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
				$("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
				$("html, body").animate({ scrollTop: $('#notify').offset().top }, 1000);
				$btn.button("reset");
			} 
    	});

   	}}
	</script>
	
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Edit Payment Gateway Settings</h4>
			</div>
<div class="panel-body form-group form-group-sm"><div class="alert alert-info"><strong>Note:</strong>These settings are required to receive successfully payment from credit/debit cards.</div>
				<form method="post" id="update_smtp">
					<input type="hidden" name="act" value="payment_gateway">
							<div class="form-group">
                                              <label class="col-sm-3 control-label margin-bottom">Email</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="For payment regrading info" class="form-control margin-bottom required" name="pemail"  value="<?php

    echo $email;

?>">
                                                </div>
                                            </div>
							<div class="form-group">
                                              <label class="col-sm-3 control-label margin-bottom">Stripe Secret Key</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="sk_live_aJkt5We5hQuwrqyv7We5rbMb" class="form-control margin-bottom required" name="stripe_sk"  value="<?php

    echo $skey;

?>">
                                                </div>
                                            </div>
		<div class="form-group">
                                              <label class="col-sm-3 control-label margin-bottom">Stripe Publishable Key</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="pk_live_aJkt5We5hQuwrqyv7We5rbMb" class="form-control margin-bottom required" name="stripe_pk"  value="<?php

    echo $pkey;

?>">
                                                </div>
                                            </div>


										<div class="form-group">
                                              <label class="col-sm-3 control-label margin-bottom">Stripe Currency Code</label>
                                                <div class="col-sm-8">
                                                     <input type="text" placeholder="3 character code eg: usd,eur" class="form-control margin-bottom required" name="ccode"  value="<?php

    echo $curr;

?>">
                                                </div>
                                            </div>
																			<div class="form-group">
                                              <label class="col-sm-3 control-label margin-bottom">Paypal Email</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="Enter Paypal email" class="form-control margin-bottom required" name="paypalmail"  value="<?php

    echo $ppmail;

?>">
                                                </div>
                                            </div>	<div class="form-group">
                                              <label class="col-sm-3 control-label margin-bottom">Paypal Currency Code</label>
                                                <div class="col-sm-8">
                                                    <input type="text" placeholder="Enter Paypal email" class="form-control margin-bottom required" name="paypalcurr"  value="<?php

    echo $paypalcurr;

?>">
                                                </div>
                                            </div>		<div class="form-group">
                                              <label class="col-sm-3 control-label margin-bottom">Enable Paypal</label>
                                                <div class="col-sm-8">
                                                    <select name="enablepaypal" class="form-control margin-bottom required">
														<?php

    if ($enablep == 'true')
    {

?><option value="true" selected>Yes</option>
														<option value="false">No</option><?php

    } else
    {

?><option value="false" selected>No</option>
														<option value="true">Yes</option><?php

    }

?>
                                                    </select><br>
                                                </div>
                                            </div>
	
                                          
													<div class="form-group">
                                              <label class="col-sm-2 control-label margin-bottom"></label>
                                                <div class="col-sm-6"><br>
                                                   <input type="submit" id="action_update_smtp" class="btn btn-success" value="Update" data-loading-text="Success">
                                                </div>
                                            </div></form></div><div id="notify" class="alert alert-success" style="display:none;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<div class="message"></div>
		</div></div>
	<?php

}
?>