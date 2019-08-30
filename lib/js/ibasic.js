//reset customer
$(document).ready(function () {
    
	//$('.taxgen').on('click', function (e) {
	$(document).on('click', ".taxgen", function (e) {
		e.preventDefault();
		art = $(this).parents('article');
		intax = parseFloat(art.find('[name="bill_saman_tax[]"]').val())+ parseFloat(art.find('[name="bill_saman_tax2[]"]').val());
		
		bprice = parseFloat(art.find('[name="billsaman_price[]"]').val() || 0);
		qty = parseFloat(art.find('[name="saman_qty[]"]').val() || 0);
		bprice2 = parseFloat(bprice/qty)/((intax/100)+1);
		art.find('.billsaman_price').val((bprice2*qty).toFixed(2))
		art.find('.saman_qty').val('')
		art.find('[name="bill_saman_sub[]"]').val('')
		
		antimYog()
		/*sainul end*/ 
		
		
		})
	$('#clear-form').on('click', function () {
        $('#myc').find('input:text,input:hidden').val('');
        $('#myc').find('input:hidden').val('0');
        $('#myc').find('input:text').prop("disabled", false);
    });

var curr = $('#hdata').attr('data-curr');
var vrate = $('#hdata').attr('data-vat');
var vrate2 = $('#hdata').attr('data-vat2');

var cloned = $('<article class="mr6 margin-b"><div class="col-xs-12 col-md-5 col-lg-4 mol-4"><div class="input-group"><span class="input-group-addon"><a href="#" class="btn btn-success btn-xs product-select"><span class="icon-database" title="Select Product From List"></span>List</a></span><input type="text" class="form-control item-input bill_saman hbh required" id="bill_saman[]" name="bill_saman[]" placeholder="Enter item title and / or description"><input type="hidden" class="bill_pid" id="bill_pid[]" name="bill_pid[]" value="0"><span class="input-group-addon"><a href="#" class="btn btn-danger btn-xs delete-row" title="Delete Product row"><span class="icon-cancel-circle"></span></a></span></div></div><div class="col-xs-12 col-md-6 col-lg-1 mol-1"><input type="number" class="form-control saman_qty jod required" id="saman_qty[]" name="saman_qty[]" value=""><input type="hidden" class="psaman_qty" name="psaman_qty[]" value="0"></div><div class="col-xs-12 col-md-6 col-lg-2 mol-2"><div class="input-group"><span class="input-group-addon currenty">'+curr+'</span><input type="number" class="form-control jod billsaman_price required" name="billsaman_price[]" placeholder="0.00"></div></div><div class="col-xs-12 col-md-3 col-lg-1 mol-1"><input type="number" class="form-control pdtax jod" name="bill_saman_tax[]" placeholder="TAX" value="'+vrate+'"><input type="hidden" class="form-control jod" name="saman-tax[]" placeholder="TAX" value=""></div> <div class="col-xs-12 col-md-3 col-lg-1 mol-1"><input type="number" class="form-control pdtax2 jod" name="bill_saman_tax2[]" placeholder="TAX" value="'+vrate2+'"><input type="hidden" class="form-control jod" name="saman-tax2[]" placeholder="CGST" value=""></div><div class="col-xs-12 col-md-6 col-lg-2 mol-2"><div class="input-group"><span class="input-group-addon">'+curr+'</span><input type="number" class="form-control jod-sub" name="bill_saman_sub[]" id="bill_saman_sub[]" value="0.00" aria-describedby="sizing-addon1" disabled><input type="hidden" class="ttInput" name="total[]" id="total-0" value="0"></div>  <div class="clear"></div></div> <div class="col-xs-12 col-md-3 col-lg-1 mol-1"> <button name="gen" class="taxgen"> Tax Gen</button></div><br>&nbsp;</article>');
    $(".add-row").click(function (e) {

        e.preventDefault();
        $(cloned).clone().appendTo('#saman_list');
        antimYog();
    });

//select product dropdown
    $(document).on('click', ".product-select", function (e) {

        e.preventDefault;

        var product = $(this);

        $('#insert').modal({backdrop: 'static', keyboard: false}).one('click', '#selected', function (e) {

            var itemText = $('#insert').find("option:selected").text();
            var itemValue = $('#insert').find("option:selected").val();
			//var itemPid = $('#insert').find("option:selected").attr('data-pid');
            var res = itemText.split("|");
            $(product).closest('article').find('.bill_saman').val(res[0]);
			$(product).closest('article').find('.bill_pid').val(itemValue);
            $(product).closest('article').find('.billsaman_price').val(res[1]);
			$(product).closest('article').find('.pdtax').val(res[2]);
			$(product).closest('article').find('.pdtax2').val(res[3]);
            $(product).closest('article').find('.jod-sub').val(res[1]);

			$(product).closest('article').find('.psaman_qty').val(0);
            antimYog();

        });

        return false;

    });

//select customer dropdown
    $(document).on('click', ".grahk-select", function (e) {

        e.preventDefault;

        var customer = $(this);

        $('#insert_grahk').modal({backdrop: 'static', keyboard: false});

        return false;

    });

    $(document).on('click', ".grahk-chayn", function (e) {

        var grahak_id = $(this).attr('data-grahk-id');
        var grahak_name = $(this).attr('data-grahk-name');
        var grahak_adrs1 = $(this).attr('data-grahk-address-1');
        var grahak_adrs2 = $(this).attr('data-grahkaddrs2');
        var grahak_phone = $(this).attr('data-grahk-phone');
        var grahak_email = $(this).attr('data-grahk-email');
		var grahak_tax = $(this).attr('data-grahk-tax');


        $('#grahak_id').val(grahak_id);
        $('#grahak_name').val(grahak_name).attr('disabled', 'disabled');
        $('#grahak_adrs1').val(grahak_adrs1).attr('disabled', 'disabled');
        $('#grahak_adrs2').val(grahak_adrs2).attr('disabled', 'disabled');
        $('#grahak_phone').val(grahak_phone).attr('disabled', 'disabled');
        $('#grahak_email').val(grahak_email).attr('disabled', 'disabled');
		$('#grahak_tax').val(grahak_tax).attr('disabled', 'disabled');
        $('.crepeat').remove();


        $('#insert_grahk').modal('hide');

    });







    $('#saman_list').on('input', '.jod', function () {
        antimYogupdate(this);
        antimYog();
    });

	 $('.remove_tax').on('change', function() {

		$('#saman_list article').each(function () {
			$('[name="bill_saman_tax[]"]', this).val(0);antimYogupdate(this);});

        antimYog();
    });

    $('#bill_totl').on('input', '.jod', function () {
        antimYog();
    });

    $('#bill_saman').on('input', '.jod', function () {
        antimYog();
    });


});


//billing subtotal calculations (Grand)
    function antimYog() {
		var kull = 0, mafi = 0, mafi2 = 0, temptax=0, temptax2=0, deliv = parseInt($('.jod.deliv').val()) || 0;
        $('#saman_list article').each(function () { 
             
			
			
			var jodsub = $('.jod-sub', this).val(),
                iqty = $('[name="saman_qty[]"]', this).val(),
                price = $('[name="billsaman_price[]"]', this).val() || 0,
				sgst = $('[name="bill_saman_tax[]"]', this).val(),
				cgst = $('[name="bill_saman_tax2[]"]', this).val(),
				percent = parseFloat(sgst)+parseFloat(cgst),
				upyog = parseFloat(iqty) * parseFloat(price);
				upyog2 = parseFloat(iqty) * parseFloat(price);
			   
			   
		
			   
			   if (percent && $.isNumeric(percent) && percent !== 0) {

		   $('#bill_ptax').val(mafi.toFixed(2));
		   
		   
		   if($('input.remove_tax').is(':checked')==false) {
		        temptax=((parseFloat(sgst) / 100) * upyog);
                mafi += temptax;
				temptax2 =((parseFloat(cgst) / 100) * upyog);
                mafi2 += temptax2;
				}
				

        }
		

               upyog +=temptax;
			   upyog2 +=temptax2;
            kull += parseFloat(jodsub);
			
           // mafi += parseFloat(jodsub)-upyog;
		   
        });
        var subT = parseFloat(kull),
            lastJod = parseFloat(kull),
            dis = parseFloat($('.jod.vvt').val()) || parseFloat($('.djod.vvt').val()),
			distype = $('.jod.vvtype').val();
			if( distype == '0') { cvat = (dis / 100) * lastJod; }
			else cvat = dis;
			
			
        $('.bill-ptax').text(mafi.toFixed(2));
        $('#bill_ptax').val(mafi.toFixed(2));
		$('.bill-ptax2').text(mafi2.toFixed(2));
        $('#bill_ptax2').val(mafi2.toFixed(2));
		
		
		subT=subT-(mafi+mafi2);
		
		
		
		//console.log(mafi2);




                $('.bill-sub-yog').text(subT.toFixed(2));
                $('#bill_upyog').val(subT.toFixed(2));
				if( distype == '0') {
                $('.bill-fdisc').text(((dis / 100) * subT).toFixed(2));
                $('#bill_fdisc').val(((dis / 100) * subT).toFixed(2));
                $('.bill-yog').text((lastJod - ((dis / 100) * lastJod) + deliv).toFixed(2));
                $('#bill_yog').val((lastJod - ((dis / 100) * lastJod) + deliv).toFixed(2));
				} else {
					
					$('.bill-fdisc').text(dis .toFixed(2));
                	$('#bill_fdisc').val(dis.toFixed(2));
                	$('.bill-yog').text((lastJod - dis + deliv).toFixed(2));
                	$('#bill_yog').val((lastJod - dis + deliv).toFixed(2));
					
					}



    }

//billing total calculations (each)
    function antimYogupdate(elem) {

        var tr = $(elem).closest('article'),
            iqty = $('[name="saman_qty[]"]', tr).val(),
            price = $('[name="billsaman_price[]"]', tr).val(),
            isPercent = $('[name="bill_saman_tax[]"]', tr).val().indexOf('%') > -1,
            sgst = $.trim($('[name="bill_saman_tax[]"]', tr).val().replace('%', '')),
			cgst = $.trim($('[name="bill_saman_tax2[]"]', tr).val().replace('%', '')),
			percent = parseFloat(sgst)+parseFloat(cgst),
            upyog = parseFloat(iqty) * parseFloat(price);


        if (($('.bill-ptax').attr('data-enable-tax') === '1') && ($('input.remove_tax').is(':checked')==false)) {

			 var ptt=((parseFloat(percent) / 100) * upyog);

			if ($('.bill-ptax').attr('data-tax-method') === '0') {

                upyog = upyog + ptt;
			}
				 $('[name="saman-tax[]"]', tr).val(sgst);
				 $('[name="saman-tax2[]"]', tr).val(cgst);

        } else {
            $('[name="bill_saman_tax[]"]', tr).val(0);
			$('[name="bill_saman_tax2[]"]', tr).val(0);
        }

        $('.jod-sub', tr).val(upyog.toFixed(2));
    }