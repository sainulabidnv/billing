<?php

if (stristr(htmlentities($_SERVER['PHP_SELF']), "checkout.php")) {
    die("Internal Server Error!");
}
if (isset($_GET['id'])) {
    $getID = intval($_GET['id']);
} else {
    die();
};
$validtoken = hash_hmac('ripemd160', $getID, IKEY . 'XcM');

$row = $db->select('invoices', array('tid', 'total', 'tsn_due', 'status', 'csd'), array('tid' => $getID))->results();

$bill_number = $row['tid'];


$bill_yog = $row['total'];

$bill_due_date = dateformat($row['tsn_due']);
$bill_status = $row['status'];
$cidd = $row['csd'];


if ($cidd > 0) {
    $whereConditions = array('id' => $cidd);
    $row1 = $db->select('reg_customers', null, $whereConditions)->results();

    $grahak_name = $row1['name'];
    $grahak_adrs1 = $row1['address1'];
    $grahak_adrs2 = $row1['address2'];
    $grahak_phone = $row1['phone'];
    $grahak_email = $row1['email'];

} else {
    $whereConditions = array('tid' => $getID);
    $row1 = $db->select('customers', null, $whereConditions)->results();


    $grahak_name = $row1['name'];
    $grahak_adrs1 = $row1['address1'];
    $grahak_adrs2 = $row1['address2'];
    $grahak_phone = $row1['phone'];
    $grahak_email = $row1['email'];
}
$query = "SELECT * FROM payment";
$pay = $db->pdoQuery($query)->results();
$ppmail = $pay[5]['optvalue'];
$enablep = $pay[4]['optvalue'];
$pkey = $pay[1]['optvalue'];
$curr = $pay[3]['optvalue'];
$skey = $pay[0]['optvalue'];
$email = $pay[2]['optvalue'];
$paypalcurr = $pay[6]['optvalue'];


?>
<link rel="stylesheet" href="lib/css/bootstrapValidator-min.css"/>


<style type="text/css">
    .col-centered {
        display: inline-block;
        float: none;
        text-align: left;
        margin-right: -4px;
    }

    .row-centered {
        margin-left: 9px;
        margin-right: 9px;
    }

    .mp {
        padding: 10px;
    }
</style>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>


<script src="lib/js/bootstrap-formhelpers-min.js"></script>
<script type="text/javascript" src="lib/js/bootstrapvalidator-min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#payment-form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'icon-checkmark',
                invalid: 'icon-cross',
                validating: 'icon-hour-glass'
            },
            submitHandler: function (validator, form, submitButton) {
                // createToken returns immediately - the supplied callback submits the form if there are no errors
                Stripe.card.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val(),
                    name: $('.card-holder-name').val(),
                }, stripeResponseHandler);
                return false; // submit from callback
            },
            fields: {

                cardholdername: {
                    validators: {
                        notEmpty: {
                            message: 'The card holder name is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 6,
                            max: 70,
                            message: 'The card holder name must be more than 6 and less than 70 characters long'
                        }
                    }
                },
                cardnumber: {
                    selector: '#cardnumber',
                    validators: {
                        notEmpty: {
                            message: 'The credit card number is required and can\'t be empty'
                        },
                        creditCard: {
                            message: 'The credit card number is invalid'
                        },
                    }
                },
                expMonth: {
                    selector: '[data-stripe="exp-month"]',
                    validators: {
                        notEmpty: {
                            message: 'The expiration month is required'
                        },
                        digits: {
                            message: 'The expiration month can contain digits only'
                        },
                        callback: {
                            message: 'Expired',
                            callback: function (value, validator) {
                                value = parseInt(value, 10);
                                var year = validator.getFieldElements('expYear').val(),
                                    currentMonth = new Date().getMonth() + 1,
                                    currentYear = new Date().getFullYear();
                                if (value < 0 || value > 12) {
                                    return false;
                                }
                                if (year == '') {
                                    return true;
                                }
                                year = parseInt(year, 10);
                                if (year > currentYear || (year == currentYear && value > currentMonth)) {
                                    validator.updateStatus('expYear', 'VALID');
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                },
                expYear: {
                    selector: '[data-stripe="exp-year"]',
                    validators: {
                        notEmpty: {
                            message: 'The expiration year is required'
                        },
                        digits: {
                            message: 'The expiration year can contain digits only'
                        },
                        callback: {
                            message: 'Expired',
                            callback: function (value, validator) {
                                value = parseInt(value, 10);
                                var month = validator.getFieldElements('expMonth').val(),
                                    currentMonth = new Date().getMonth() + 1,
                                    currentYear = new Date().getFullYear();
                                if (value < currentYear || value > currentYear + 100) {
                                    return false;
                                }
                                if (month == '') {
                                    return false;
                                }
                                month = parseInt(month, 10);
                                if (value > currentYear || (value == currentYear && month > currentMonth)) {
                                    validator.updateStatus('expMonth', 'VALID');
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                },
                cvv: {
                    selector: '#cvv',
                    validators: {
                        notEmpty: {
                            message: 'The cvv is required and can\'t be empty'
                        },
                        cvv: {
                            message: 'The value is not a valid CVV',
                            creditCardField: 'cardnumber'
                        }
                    }
                },
            }
        });
    });
</script>
<script type="text/javascript">
    // this identifies your website in the createToken call below
    Stripe.setPublishableKey('<?php echo $pkey ?>');

    function stripeResponseHandler(status, response) {
        if (response.error) {
            // re-enable the submit button
            $('.submit-button').removeAttr("disabled");
            // show hidden div
            document.getElementById('a_x200').style.display = 'block';
            // show the errors on the form
            $(".payment-errors").html(response.error.message);
        } else {
            var form$ = $("#payment-form");
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            // and submit
            form$.get(0).submit();
        }
    }


</script>
<?php
require 'lib/stripe/Stripe.php';
$error = '';
$success = '';
if (isset($_GET['token']) == $validtoken) {


    $success = "<div class='alert alert-success'><strong>Success!</strong> Invoice #$bill_number Payment successful.
				<a href='index.php?rdp=view-invoice&id=$bill_number' class='btn btn-info btn-lg'><span class='icon-file-text2' aria-hidden='true'></span> View </a>&nbsp; &nbsp;<a href='view/invoice-view.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/invoice-view.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a></div></div>";
    $aWhere = array('tid' => $bill_number);
    $dataArray = array('status' => 'paid', 'pmethod' => '1');
    $status = $db->update('invoices', $dataArray, $aWhere)->rStatus();
}
if ($_POST) {
    Stripe::setApiKey($skey);


    try {
        $amount = str_replace('.', '', $bill_yog);
        if (!isset($_POST['stripeToken']))
            throw new Exception("The Stripe Token was not generated correctly");
        $transaction = Stripe_Charge::create(array("amount" => $amount,
            "currency" => $curr,
            "card" => $_POST['stripeToken'],
            "description" => "Invoice #$bill_number $grahak_email "));
        $payment = $transaction->id;

        $success = "<div class='alert alert-success'><strong>Success!</strong> Invoice #$bill_number Payment successful.
				<a href='index.php?rdp=view-invoice&id=$bill_number' class='btn btn-info btn-lg'><span class='icon-file-text2' aria-hidden='true'></span> View </a>&nbsp; &nbsp;<a href='view/invoice-view.php?id=$bill_number' class='btn btn-warning btn-lg'><span class='icon-print' aria-hidden='true'></span> Print </a> &nbsp; <a href='view/invoice-view.php?id=$bill_number&download=1' class='btn btn-info btn-lg'><span class='icon-download2' aria-hidden='true'></span> Download </a></div></div>";
        $aWhere = array('tid' => $bill_number);
        $dataArray = array('status' => 'paid', 'pmethod' => '1');
        $status = $db->update('invoices', $dataArray, $aWhere)->rStatus();
    } catch (Exception $e) {
        $error = '<div class="alert alert-danger">
			  <strong>Error!</strong> ' . $e->getMessage() . '
			  </div>';
    }
}
?>
<div class="alert alert-danger" id="a_x200" style="display: none;"><strong>Error!</strong> <span
            class="payment-errors"></span></div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="payment-success">
                <?php echo $success;
                echo $error;
                echo "</div>";
                if ($success == '') { ?>
                <div class="row ">

                    <h2 class="page-header text-center">Secure Checkout Page
                        <!-- <small>Secondary Text</small> -->
                    </h2>


                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">&nbsp;&nbsp;

                    </div>
                </div>


                <!-- Page Header -->


                <!-- /.row -->

                <!-- Projects Row -->
                <div class="box">
                    <div class="col-md-6 panel panel-default  mp">

                        <noscript>
                            <div class="bs-callout bs-callout-danger">
                                <h4>JavaScript is not enabled!</h4>

                                <p>This payment form requires your browser to have JavaScript enabled. Please activate
                                    JavaScript and reload this page. Check <a href="http://enable-javascript.com"
                                                                              target="_blank">enable-javascript.com</a>
                                    for more
                                    informations.</p>
                            </div>
                        </noscript>


                        <!-- Form Name -->
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">

                            <!-- Identify your business so that you can collect the payments. -->
                            <input type="hidden" name="business" value="<?php echo $ppmail ?>">

                            <!-- Specify a Buy Now button. -->
                            <input type="hidden" name="cmd" value="_xclick">

                            <!-- Specify details about the item that buyers will purchase. -->
                            <input type="hidden" name="item_name" value="Invoice <?php echo $bill_number ?>">
                            <input type="hidden" name="item_number" value="<?php echo $bill_number ?>">
                            <input type="hidden" name="amount" value="<?php echo $bill_yog; ?>">
                            <input type="hidden" name="currency_code" value="<?php echo $paypalcurr ?>">

                            <!-- Specify URLs -->
                            <input type='hidden' name='cancel_return'
                                   value='<?php echo SITE . "/index.php?rdp=checkout&id=$bill_number"; ?>'>
                            <input type='hidden' name='return'
                                   value='<?php echo SITE . "/index.php?rdp=checkout&id=$bill_number&token=$validtoken"; ?>'>


                            <legend class="mp">Billing Details</legend>
                            <div class="form-group">
                                <div class="col-sm-3">Invoice</div>
                                <div class="col-sm-7">
                                    <strong>#<?php echo $bill_number ?></strong></div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-3">Due Date</div>
                                <div class="col-sm-7">
                                    <strong><?php echo $bill_due_date ?><br>&nbsp;</strong></div>

                            </div>
                            <!-- Street -->
                            <div class="form-group">
                                <div class="col-sm-3">Name</div>
                                <div class="col-sm-7">
                                    <strong><?php echo $grahak_name ?></strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3">Address</div>
                                <div class="col-sm-7">
                                    <strong><?php echo $grahak_adrs1 ?></strong></div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-3">&nbsp;</div>
                                <div class="col-sm-7">
                                    <strong><?php echo $grahak_adrs2 ?></strong></div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3">Phone</div>
                                <div class="col-sm-7">
                                    <strong><?php echo $grahak_phone ?></strong></div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-3">Email</div>
                                <div class="col-sm-7">
                                    <strong><?php echo $grahak_email ?></strong></div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-3"> Total amount</div>
                                <div class="col-sm-7"><br>
                                    <strong><?php echo $siteConfig['curr'];
                                        echo $bill_yog ?></strong></div>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-3">&nbsp;</div>
                                <div class="col-sm-7">
                                    &nbsp;</div>
                            </div>
                            <div class="form-group">
                                <!-- Display the payment button. -->
                                <input type="image" name="submit" border="0"
                                       src="https://www.paypalobjects.com/en_US/i/btn/x-click-but6.gif"
                                       alt="PayPal - The safer, easier way to pay online">
                                <img alt="" border="0" width="1" height="1"
                                     src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"></div>

                        </form>

                    </div>
                    <div class="col-md-6">

                        <div class="panel panel-default mp">


                            <legend class="mp">Payment Details

                                <img class="img-responsive pull-right"
                                     src="images/card/card.png">
                            </legend>

                            <form action="" method="POST" id="payment-form" class="form-horizontal">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="cardNumber">CARD HOLDER NAME</label>

                                                <div class="input-group">
                                                    <input
                                                            type="tel"
                                                            class="form-control"
                                                            name="cardholdername"
                                                            placeholder="Card Holder Name"
                                                            class="card-holder-name form-control"
                                                    />
                                                    <span class="input-group-addon"><span
                                                                class="icon-user-tie"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="cardNumber">CARD NUMBER</label>

                                                <div class="input-group">
                                                    <input type="number" id="cardnumber" maxlength="19"
                                                           placeholder="Card Number" class="card-number form-control">
                                                    <span class="input-group-addon"><span
                                                                class="icon-credit-card"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-3">Expiry Date</label>

                                            <div class="col-sm-8">
                                                <div class="form-inline">
                                                    <select name="select2" data-stripe="exp-month"
                                                            class="card-expiry-month stripe-sensitive required form-control">
                                                        <option value="01" selected="selected">01</option>
                                                        <option value="02">02</option>
                                                        <option value="03">03</option>
                                                        <option value="04">04</option>
                                                        <option value="05">05</option>
                                                        <option value="06">06</option>
                                                        <option value="07">07</option>
                                                        <option value="08">08</option>
                                                        <option value="09">09</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                    <span> / </span>
                                                    <select name="select2" data-stripe="exp-year"
                                                            class="card-expiry-year stripe-sensitive required form-control">
                                                    </select>
                                                    <script type="text/javascript">
                                                        var select = $(".card-expiry-year"),
                                                            year = new Date().getFullYear();

                                                        for (var i = 0; i < 12; i++) {
                                                            select.append($("<option value='" + (i + year) + "' " + (i === 0 ? "selected" : "") + ">" + (i + year) + "</option>"))
                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-sm-3" for="textinput">CVV/CVV2</label>

                                            <div class="col-sm-3">
                                                <input type="number" id="cvv" placeholder="CVV" maxlength="4"
                                                       class="card-cvc form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="control-group">
                                            <div class="controls">

                                                <button class="btn btn-primary btn-sm" type="submit">Pay Now</button>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="display:none;">
                                        <div class="col-xs-12">
                                            <p class="payment-errors"></p>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <?php } ?>
                    </div>

                </div>
            </div>