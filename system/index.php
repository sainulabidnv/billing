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
if ($user->isSigned())
    redirect("index.php?rdp=home");

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
                            form.find('[name=username]').focus().parent().addClass('has-error')
                                .after('<div class="alert alert-danger"><strong>Alert! </strong>&nbsp; ' + notify.form[name] + '</div>');
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
                        }, 1500);
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <img class="text-center profile-img" src="images/logo.jpg"
                         alt="">
                </div>
                <div class="modal-body">
                    <form class="form col-md-12 center-block" method="post" action="request/employee.php?op=login"
                          data-success="index.php?rdp=home" accept-charset="UTF-8" role="form"><input type="hidden"
                                                                                                      name="action"
                                                                                                      value="login">

                        <div class="form-group">
                            <input type="text" class="form-control " placeholder="Email" name="username">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control " placeholder="Password" name="password">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary  btn-block" type="submit">Sign In</button>
                            <a href="#forget" data-toggle="tab">Forgot password?</a></span>
                        </div>
                        
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="forget">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <img class="text-center profile-img" src="images/logo.jpg"
                         alt="">
                </div>
                <div class="modal-body">
                    <form class="form col-md-12 center-block" method="post" action="request/reset_password.php">
                        <div class="form-group">
                            <input name="email" type="text" required autofocus class="form-control"
                                   placeholder="Email Address">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-warning btn-lg btn-block" type="submit">Reset</button>
                            <span class="pull-right"><a href="">Back to login?</a></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12">

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>