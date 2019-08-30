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
<?php

//employee management
if (stristr(htmlentities($_SERVER['PHP_SELF']), "user.php")) {
    die("Internal Server Error!");
}
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 0;
}

switch ($op) {
    case "add":
        add();
        break;
    case "edit":
        editp();
        break;
    case "resetpass":
        resetpass($id);
        break;
    default:
        plist();
        break;
}

function add()
{
global $user;
if ($user->group_id > 1) {
    die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
}
$d = @$_SESSION["regData"];
unset($_SESSION["regData"]);

?>


<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>New User/Employee Details</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">


                        <form method="post" action="request/employee.php?op=register" data-success="index.php?rdp=user">
                            <div class="form-group">
                                <label>Username:</label>
                                <input name="username" type="text" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>First Name:</label>
                                <input name="first_name" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Last Name:</label>
                                <input name="last_name" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Email: </label>
                                <input name="email" type="text" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Phone: </label>
                                <input name="phone" type="text" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Password:</label>
                                <input name="password" type="password" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Confirm Password:</label>
                                <input name="Password2" type="password" required class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Permissions And Job Level: </label>
                                <select name="group_id" class="form-control">
                                    <option value="3">Sales Team</option>
                                    <option value="2">Manager</option>
                                    <option value="1">Owner</option>
                                </select></div>
                            <div class="alert alert-success"><strong>Sales Team</strong> member can create, print the
                                invoice and view invoice list, not rights to modify or delete an invoice.
                            </div>
                            <div class="alert alert-info"><strong>Manager</strong> has rights to Create, Edit, Delete
                                invoices and complete access to Stock and customer Management.
                            </div>
                            <div class="alert alert-danger">You are a <strong>Owner</strong>, have all rights including
                                modification of company details, invoice settings. Please do not assign the owner group
                                to a ordinary employee. It may be risky for your business data.
                            </div>


                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Add</button>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <?php

        }

        function editp()
        {
        global $user;

        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Update Account and Password</h4>
                    </div>
                    <div class="panel-body form-group form-group-sm">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">

                                <form method="post" action="request/employee.php?op=update">
                                    <div class="form-group">
                                        <label>Username:</label>
                                        <input disabled name="username" type="text" value="<?php

                                        echo $user->username

                                        ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>First Name:</label>
                                        <input name="first_name" type="text" value="<?php

                                        echo $user->first_name

                                        ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Last Name:</label>
                                        <input name="last_name" type="text" value="<?php

                                        echo $user->last_name

                                        ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Email: </label>
                                        <input name="email" type="text" required value="<?php

                                        echo $user->email

                                        ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label>Phone: </label>
                                        <input name="phone" type="text" value="<?php

                                        echo $user->phone

                                        ?>" class="form-control">
                                    </div>

                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">Update</button>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="panel-body form-group form-group-sm">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <h4>Change Password</h4>

                                <hr/>

                                <form method="post" action="request/employee.php?op=changepass">
                                    <div class="form-group">
                                        <label>Current Password:</label>
                                        <input name="Currentpassword" type="password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>New Password:</label>
                                        <input name="password" type="password" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm New Password:</label>
                                        <input name="Password2" type="password" class="form-control" required>
                                    </div>
                                    <input name="c" type="hidden" value="<?php

                                    echo getVar("c")

                                    ?>">

                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">Change Password</button>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <?php

                    }
                    function plist()
                    {


                    global $db, $user;

                    if ($user->group_id > 1) {
                        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
                    }

                    ?>


                    <div class="row">

                        <div class="col-xs-12">
                            <div id="notify" class="alert alert-success" style="display:none;">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>

                                <div class="message"></div>
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#elist').DataTable({
                                        stateSave: true
                                    });
                                });
                            </script>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4>Employee List</h4>
                                </div>
                                <div class="panel-body form-group form-group-sm tbl">
                                    <?php

                                    echo '<a class="btn btn-lg btn-primary" href="index.php?rdp=user&op=add"><span class="icon-user-plus"></span> Add New Employee</a><br><br>';
                                    $query = "SELECT * FROM employee WHERE activated=1 ORDER BY group_id ASC";


                                    $results = $db->pdoQuery($query)->results();
                                    echo ' <div class="table-responsive"><table id="elist" class="table cell-border" cellspacing="0"><thead><tr>

				<th data-field="name"data-sortable="true">Name</th>
				<th data-field="user"data-sortable="true">Username</th>
				<th>Email</th>
				<th data-field="role"data-sortable="true">Role</th>
				<th>Settings</th>

			  </tr></thead><tbody>';
                                    foreach ($results as $row) {
                                        $id = $row['id'];
                                        $role = $row["group_id"];
                                        switch ($role) {
                                            case 1:
                                                $role = "Owner";
                                                break;
                                            case 2:
                                                $role = "Manager";
                                                break;
                                            case 3:
                                                $role = "Sales Team";
                                                break;
                                        }

                                        echo '
			    <tr>
			    	<td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
					<td>' . $row["username"] . '</td>
				    <td>' . $row["email"] . '</td>
				    <td>' . $role . '</td>
				    <td><a href="index.php?rdp=user&op=resetpass&id=' . $id .
                                            '" class="btn btn-primary btn-xs"><span class="icon-pencil"></span>Reset Password Code</a>';
                                        if ($user->id != $id) {
                                            print '&nbsp; &nbsp;<a data-user-id="' . $id .
                                                '" class="btn btn-danger btn-xs delete-user"><span class="icon-bin"></span></a>';
                                        }
                                        print '</td>
			    </tr>
		    ';


                                    }
                                    echo '</tbody></table></div><br><div class="alert alert-success">Sales Team member can create, print the invoice and view invoice list, not rights to modify or delete an invoice.</div>
				<div class="alert alert-info">Manager has rights to Create, Edit, Delete invoices and complete access to Stock Management.</div>
				<div class="alert alert-danger">You are a SuperAdmin, have all rights including modification of company details, invoice settings.</div>';

                                    ?>
                                    <script type="text/javascript">
                                        $(document).on('click', ".delete-user", function (e) {
                                            e.preventDefault();

                                            var userId = 'act=delete_user&delete=' + $(this).attr('data-user-id');
                                            var user = $(this);

                                            $('#delete_user').modal({
                                                backdrop: 'static',
                                                keyboard: false
                                            }).one('click', '#delete', function () {
                                                deleteUser(userId);
                                                $(user).closest('tr').remove();
                                            });
                                        });


                                        function deleteUser(userId) {

                                            jQuery.ajax({

                                                url: 'request/e_control.php',
                                                type: 'POST',
                                                data: userId,
                                                dataType: 'json',
                                                success: function (data) {
                                                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                                                    $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                                                    $("html, body").animate({scrollTop: $('BODY').offset().top}, 1000);

                                                },
                                                error: function (data) {
                                                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                                                    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                                                    $("html, body").animate({scrollTop: $('BODY').offset().top}, 1000);

                                                }
                                            });

                                        }
                                    </script>
                                    <div id="delete_user" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                    </button>
                                                    <h4 class="modal-title">Delete user</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this user?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" data-dismiss="modal" class="btn btn-primary"
                                                            id="delete">Delete
                                                    </button>
                                                    <button type="button" data-dismiss="modal" class="btn">Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div><?php

                                    }
                                    function resetpass($id)
                                    {
                                    global $user, $db;
                                    if ($user->group_id > 1) {
                                        die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
                                    }

                                    $whereConditions = array('id' => $id);
                                    $ccd = $db->select('employee', array('confirmation'), $whereConditions)->results();
                                    $cod = $ccd['confirmation'];

                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4>Reset Password Code</h4>
                                                </div>
                                                <div class="panel-body form-group form-group-sm">
                                                    <div class="row">
                                                        <div class="col-sm-6 col-sm-offset-3">
                                                            <h4>Reset Password Code is <br><br><?php

                                                                echo $cod

                                                                ?></h4>

                                                            <p>To get password reset code of any user you need request
                                                                it from login page. <a
                                                                        href="request/employee.php?op=logout"
                                                                        class='btn btn-info'>GET Code for
                                                                    this user</a></p>
                                                        </div>

                                                        <?php

                                                        }

                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>

