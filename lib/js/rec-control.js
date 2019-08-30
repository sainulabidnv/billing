$(document).ready(function () {
     $("#action_create_rec").click(function (e) {
            e.preventDefault();
            actionCreateQuote();
        });
        function actionCreateQuote() {

            var errorNum = farmCheck();


            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {

                var $btn = $("#action_create_quote").button("loading");

                $(".required").parent().removeClass("has-error");
                $("#create_quote").find(':input:disabled').removeAttr('disabled');

                $.ajax({

                    url: 'request/recc.php',
                    type: 'POST',
                    data: $("#create_quote").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $("#create_quote").remove();
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

		$("#raction_edit").click(function (e) {
            e.preventDefault();
            rupdateInvoice();
        });
        function rupdateInvoice() {
            var errorNum = farmCheck();

            if (errorNum > 0) {
                $("#notify").removeClass("alert-success").addClass("alert-warning").fadeIn();
                $("#notify .message").html("<strong>Error</strong>: It appears you have forgotten to complete something!");
                $("#notify").animate({scrollTop: $('#notify').offset().top}, 1000);
            } else {
                var $btn = $("#action_edit_quote").button("loading");
                $("#edit_quote").find(':input:disabled').removeAttr('disabled');

                jQuery.ajax({

                    url: 'request/recc.php',
                    type: 'POST',
                    data: $("#edit_quote").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
                        $("#notify").removeClass("alert-warning").addClass("alert-success").fadeIn();
                        $("html, body").animate({scrollTop: $('#notify').offset().top}, 1000);
                        $("#edit_quote").remove();
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
$(document).on('click', ".rdelete-invoice", function (e) {
        e.preventDefault();

        var billNo = 'act=delete_invoice&delete=' + $(this).attr('data-invoice-id');
		var invoice = $(this);

        $('#rdelete_invoice').modal({backdrop: 'static', keyboard: false}).one('click', '#rdelete', function () {
            rremoveInvoice(billNo);
            $(invoice).closest('tr').remove();
        });
    });
    function rremoveInvoice(billNo) {

        jQuery.ajax({

            url: 'request/rcst.php',
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


		    $(document).on('click', ".can-payment", function (e) {
        e.preventDefault();

        var billNo = 'act=can_payment&send=' + $(this).attr('data-invoice-id');

        $('#can_payment').modal({backdrop: 'static', keyboard: false}).one('click', '#rcancel', function () {
            canInvoice(billNo);

        });
    });
    function canInvoice(billNo) {
        var $btn;
        jQuery.ajax({

            url: 'request/recp.php',
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
						}, 2000);

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

//send
    $(document).on('click', ".rsend-invoice", function (e) {
        e.preventDefault();

        var billNo = 'act=rsend_invoice&send=' + $(this).attr('data-invoice-id') + '&csd=' + $(this).attr('data-csd');

        $('#rsend_invoice').modal({backdrop: 'static', keyboard: false}).one('click', '#send', function () {
            sendInvoice(billNo);

        });
    });
    function sendInvoice(billNo) {
        var $btn;
        jQuery.ajax({

            url: 'request/email-r.php',
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
    $(document).on('click', ".rsend-sms", function (e) {
        e.preventDefault();

        var billNo = 'act=rsend_sms&send=' + $(this).attr('data-invoice-id') + '&csd=' + $(this).attr('data-csd');

        $('#rsend_sms').modal({backdrop: 'static', keyboard: false}).one('click', '#sms', function () {
            sendSMS(billNo);

        });
    });

    function sendSMS(billNo) {
        var $btn;
        jQuery.ajax({

            url: 'request/sms-r.php',
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
//part
	    $(document).on('click', ".part-payment", function (e) {
        e.preventDefault();

        var billNo = 'act=part_payment&send=' + $(this).attr('data-invoice-id') + '&part=';

        $('#part_payment').modal({backdrop: 'static', keyboard: false}).one('click', '#partpay', function () {
			var amt=$("input[name=amount]").val();
			var nte=$("input[name=tnote]").val();
            billNo = billNo + amt + '&tnote=' + nte;
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
                $("#notify .message").html("<strong>" + data.status + "</strong>: " + data.message);
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