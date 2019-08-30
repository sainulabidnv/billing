<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  E-mail: support@expressinvoice.xyz
 *  Website: https://www.expressinvoice.xyz
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * If you Purchased from Codecanyon, Please read the full License from
 *  * here- http://skyresoft.com/licenses/standard/
 * ***********************************************************************
 */
$siteConfig['name'] = "Reset Password";
require_once("../includes/system.php");
require_once("../includes/config.php");
include('../includes/header-login.php');

?>
<script type="text/javascript">
    $(document).ready(function () {
        $('form').on('submit', function () {
            var form = $(this);
            var button = $(':submit', form);

            button.button('loading');


            $.post(form.attr('action'), form.serialize(), function (notify) {

                form.find('.error').remove();
                form.find('.has-error').removeClass('has-error');

                if (notify.form && !$.isEmptyObject(notify.form)) {
                    // Display errors
                    for (var name in notify.form) {
                        if (notify.form.hasOwnProperty(name)) {
                            form.find('[name=' + name + ']').focus().parent().addClass('has-error')
                                .find('input')
                                .before('<small class="error text-danger">' + notify.form[name] + '</small>');
                        }
                    }

                    // Re-Enables the button
                    button.button('reset');
                }
                else {
                    // Success
                    button.replaceWith('<div class="alert alert-success">' + notify.confirm + '</div>');

                    if (form.data('success')) {
                        setTimeout(function () {
                            window.location = form.data('success');
                        }, 4000);
                    }


                    //form.find('fieldset').attr('disabled','disabled');
                }
            }, 'json');

            return false;
        }).on('change', 'input', function () {

            // Clears the error status

            var group = $(this).parents('.form-group:first');

            group.find('.error').remove();
            group.removeClass('has-error');
        })
    });

</script>
<div id="my-tab-content" class="tab-content">


    <div class="tab-pane active" id="login">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">

                    <img class="text-center profile-img" src="images/logo.jpg"
                         alt="">
                </div>
                <div class="modal-body ">
                    <form class="form-signin" method="post" action="../request/change_password.php"
                          data-success="../index.php">
                        <div class="form-group">
                            <label>Password Reset Code:</label>
                            <input name="c" type="text" class="form-control" value="<?php

                            echo getVar("c")

                            ?>" required autofocus>
                        </div>
                        <div class="form-group">
                            <label>New Password:</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Confirm New Password:</label>
                            <input name="Password2" type="password" class="form-control" required>
                        </div>


                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Change Password</button>
                            <a href="" data-toggle="tab">Back to login ?</a></span>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</div>