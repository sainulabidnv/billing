
function farmCheck() {

    var errorNum = 0;

    $(".required").each(function (i, obj) {

        if ($(this).val() === '') {
            $(this).parent().addClass("has-error");
            errorNum++;
        } else {
            $(this).parent().removeClass("has-error");
        }


    });
    $(".vrequired").each(function (i, obj) {

        if ($(this).val() === '0') {
            $(this).parent().addClass("has-error");
            errorNum++;
        } else {
            $(this).parent().removeClass("has-error");
        }


    });

    return errorNum;
}
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
$(document).ready(function () {
    $("#act-newinvoive").click(function (e) {
        e.preventDefault();
        NewInvoice();
    });
	    $('#tsn_date, #tsn_due').datetimepicker({
        showClose: false
    });

    function NewInvoice() {

        var errorNum = farmCheck();
        var $btn;
        if (errorNum > 0) {
            $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
            $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
            $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
        } else {

            var $btn = $("#act-newinvoive").button("loading");

            $(".required").parent().removeClass("has-error");
            $("#create_invoice").find(':input:disabled').removeAttr('disabled');

            $.ajax({

                url: 'request/invoice.php',
                type: 'POST',
                data: $("#create_invoice").serialize(),
                dataType: 'json',
                success: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $("#create_invoice").remove();
                    $btn.button("reset");
                },
                error: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $btn.button("reset");
                }

            });
        }

    }
	
	//Delete Payments
	
	    $(document).on('click', ".deletePayment", function (e) {
        e.preventDefault();

        var billNo = 'act=deletePayment&date=' + $(this).attr('data-payment-date') + '&type=' + $(this).attr('data-payment-type');
        var invoice = $(this);
        $('#deletePayment').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
            deletePayment(billNo);
            $(invoice).closest('tr').remove();
        });
    });
    function deletePayment(billNo) {

        jQuery.ajax({

            url: 'request/mini.php',
            type: 'POST',
            data: billNo,
            dataType: 'json',
            success: function (data) {
                if (data.status == "Success") {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                } else {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);

                }
                ;
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + JSON.stringify(data) + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").scrollTop($("body").offset().top);

            }
        });

    }

    

    $(document).on('click', ".delete-invoice", function (e) {
        e.preventDefault();

        var billNo = 'act=delete_invoice&delete=' + $(this).attr('data-invoice-id');
        var invoice = $(this);
        $('#delete_invoice').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
            removeInvoice(billNo);
            $(invoice).closest('tr').remove();
        });
    });
    function removeInvoice(billNo) {

        jQuery.ajax({

            url: 'request/cst.php',
            type: 'POST',
            data: billNo,
            dataType: 'json',
            success: function (data) {
                if (data.status == "Success") {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                } else {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);

                }
                ;
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").scrollTop($("body").offset().top);

            }
        });

    }

//send
    $(document).on('click', ".send-invoice", function (e) {
        e.preventDefault();

        var billNo = 'act=send_invoice&send=' + $(this).attr('data-invoice-id') + '&csd=' + $(this).attr('data-csd');

        $('#send_invoice').modal({backdrop: 'static', keyboard: false}).one('click', '#send', function () {
            sendInvoice(billNo);

        });
    });
    function sendInvoice(billNo) {
        var $btn;
        jQuery.ajax({

            url: 'request/email.php',
            type: 'POST',
            data: billNo,
            dataType: 'json',
            success: function (data) {
                if (data.status == "Success") {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                    $btn.button("reset")
                } else {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                    $btn.button("reset");
                }
                ;
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").scrollTop($("body").offset().top);
                $btn.button("reset");
            }
        });

    }
//rem
//send
    $(document).on('click', ".prm-invoice", function (e) {
        e.preventDefault();

        var billNo = 'act=send_invoice&send=' + $(this).attr('data-invoice-id') + '&csd=' + $(this).attr('data-csd') + '&type=' + $(this).attr('data-type');

        $('#prm_invoice').modal({backdrop: 'static', keyboard: false}).one('click', '#rmsend', function () {
            rsendInvoice(billNo);

        });
    });
    function rsendInvoice(billNo) {
        var $btn;
        jQuery.ajax({

            url: 'request/remail.php',
            type: 'POST',
            data: billNo,
            dataType: 'json',
            success: function (data) {
                if (data.status == "Success") {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                    $btn.button("reset")
                } else {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                    $btn.button("reset");
                }
                ;
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").scrollTop($("body").offset().top);
                $btn.button("reset");
            }
        });

    }
    //sms
    $(document).on('click', ".send-sms", function (e) {
        e.preventDefault();

        var billNo = 'act=send_sms&send=' + $(this).attr('data-invoice-id') + '&csd=' + $(this).attr('data-csd');

        $('#send_sms').modal({backdrop: 'static', keyboard: false}).one('click', '#sms', function () {
            sendSMS(billNo);

        });
    });

    
    function sendSMS(billNo) {
        var $btn;
        jQuery.ajax({

            url: 'request/sms.php',
            type: 'POST',
            data: billNo,
            dataType: 'json',
            success: function (data) {
                if (data.status == "Success") {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                    $btn.button("reset")
                } else {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
                    $("html, body").scrollTop($("body").offset().top);
                    $btn.button("reset");
                }
                ;
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").scrollTop($("body").offset().top);
                $btn.button("reset");
            }
        });

    }

	//paid
    $(document).on('click', ".mark-payment", function (e) {
        e.preventDefault();

        var billNo = 'act=mark_payment&send=' + $(this).attr('data-invoice-id') + '&csd=' + $(this).attr('data-csd');

        $('#mark_payment').modal({backdrop: 'static', keyboard: false}).one('click', '#pay', function () {
            markInvoice(billNo);

        });
    });
    function markInvoice(billNo) {
        var $btn;
        jQuery.ajax({

            url: 'request/mini.php',
            type: 'POST',
            data: billNo,
            dataType: 'json',
            success: function (data) {
                if (data.status == "Success") {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
					$("#markp").remove();
					$("#markp2").remove();
                    $("html, body").scrollTop($("body").offset().top);
					setTimeout(function(){
           location.reload();
      }, 1500);

                } else {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
					$("#markp").remove();
                    $("html, body").scrollTop($("body").offset().top);

                }
                ;
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").scrollTop($("body").offset().top);
                $btn.button("reset");
            }
        });

    }


//receipt payment
	$(document).on('click', ".receiptPayment", function (e) {
        e.preventDefault();
		var billNo = 'act=receiptPayment&send=' + $(this).attr('data-csd') + '&part=';

        
        $('#receiptPayment').modal({backdrop: 'static', keyboard: false}).on('hidden.bs.modal', function () { location.reload(); }).one('click', '#partpay', function () {
			var amt=$("input[name=amount]").val();
			var nte=$("input[name=tnote]").val();
			var date=$("input[name=tdate]").val();
            billNo = billNo + amt + '&tnote=' + nte + '&date=' + date;
			partInvoice(billNo);

        });
    });


//custom payment

	$(document).on('click', ".customPayment", function (e) {
        e.preventDefault();
		var billNo = 'act=customPayment&send=' + $(this).attr('data-csd') + '&part=';

        
        $('#customPayment').modal({backdrop: 'static', keyboard: false}).on('hidden.bs.modal', function () { location.reload(); }).one('click', '#partpay', function () {
			var amt=$("input[name=amount]").val();
			var nte=$("input[name=tnote]").val();
			var date=$("input[name=tdate]").val();
            billNo = billNo + amt + '&tnote=' + nte + '&date=' + date;
			partInvoice(billNo);

        });
    });



//custom payment end

//part
	    $(document).on('click', ".part-payment", function (e) {
        e.preventDefault();

        var billNo = 'act=part_payment&send=' + $(this).attr('data-invoice-id') + '&part=';

        $('#part_payment').modal({backdrop: 'static', keyboard: false}).on('hidden.bs.modal', function () { location.reload(); }).one('click', '#partpay', function () {
			var amt=$("input[name=amount]").val();
			var nte=$("input[name=tnote]").val();
			var date=$("input[name=tdate]").val();
            billNo = billNo + amt + '&tnote=' + nte + '&date=' + date;
            partInvoice(billNo);

        });
    });
    function partInvoice(billNo) {
		
            
        var $btn;
		var errorNum = farmCheck();
		
		        if (errorNum > 0) {
            $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
            $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to enter partial amount!");
            $("html, body").animate({scrollTop: $('#notify').offset().bottom}, 1000);
        } else {
        jQuery.ajax({

            url: 'request/mini.php',
            type: 'POST',
            data: billNo,
            dataType: 'json',
            success: function (data) {
                if (data.status == "Success") {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-danger").addClass("alert-success").fadeIn();
					$("html, body").scrollTop($("body").offset().top);
					setTimeout(function(){// wait for 5 secs(2)
           location.reload(); // then reload the page.(3)
      }, 2000);

                } else {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-danger").fadeIn();
					$("html, body").scrollTop($("body").offset().top);

                }

            },
            error: function (data) {
                $("#notify .message").html("<strong>" + JSON.stringify(data) + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").scrollTop($("body").offset().top);
            }
        });}

    }

    $("#action_edit_invoice").click(function (e) {
        e.preventDefault();
        updateInvoice();
    });
    function updateInvoice() {
        var errorNum = farmCheck();
        var $btn;
        if (errorNum > 0) {
            $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
            $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
            $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
        } else {
            var $btn = $("#action_update_invoice").button("loading");
            $("#update_invoice").find(':input:disabled').removeAttr('disabled');

            jQuery.ajax({

                url: 'request/cst.php',
                type: 'POST',
                data: $("#update_invoice").serialize(),
                dataType: 'json',
                success: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $("#update_invoice").remove();
                    $btn.button("reset");
                    $(".saman_qty").attr('disabled', 'disabled');

                },
                error: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $btn.button("reset");
                }
            });

        }
    }



    $(document).on('click', ".delete-customer", function (e) {
        e.preventDefault();

        var userId = 'act=delete_customer&delete=' + $(this).attr('data-grahk-id');
        var user = $(this);

        $('#delete_customer').modal({backdrop: 'static', keyboard: false}).one('click', '#delete', function () {
            deleteGrahk(userId);
            $(user).closest('tr').remove();
        });
    });
    function deleteGrahk(userId) {

        jQuery.ajax({

            url: 'request/cst.php',
            type: 'POST',
            data: userId,
            dataType: 'json',
            success: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                $("html, body").animate({scrollTop: $('body').offset().top}, 1000);
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").animate({scrollTop: $('body').offset().top}, 1000);
            }
        });

    }


    $(document).on('click', "#action_update_customer", function (e) {
        e.preventDefault();
        updateGrahk();
    });
    function updateGrahk() {

        var $btn = $("#action_update_customer").button("loading");

        jQuery.ajax({

            url: 'request/cst.php',
            type: 'POST',
            data: $("#update_customer").serialize(),
            dataType: 'json',
            success: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                $btn.button("reset");
            },
            error: function (data) {
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                $btn.button("reset");
            }
        });

    }

    $("#action_create_customer").click(function (e) {
        e.preventDefault();
        actionCreateCustomer();
    });
    function actionCreateCustomer() {
        var $btn;
        var errorNum = farmCheck();

        if (errorNum > 0) {
            $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
            $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
            $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
        } else {

            var $btn = $("#action_create_customer").button("loading");

            $(".required").parent().removeClass("has-error");

            $.ajax({

                url: 'request/cst.php',
                type: 'POST',
                data: $("#create_customer").serialize(),
                dataType: 'json',
                success: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $("#create_customer").before().html("");
                    $("#create_cuatomer").remove();
                    $btn.button("reset");
                },
                error: function (data) {
                    $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                    $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                    $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                    $btn.button("reset");
                }

            });
        }

    }
});

