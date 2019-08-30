<?php

if (stristr(htmlentities($_SERVER['PHP_SELF']), "payments.php")) {
    die("Internal Server Error!");
}
//customer management
if ($user->group_id > 2) {
    die('<div class="panel-heading alert-danger"><h4>You are not authorized!</h4></div>');
}
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if (isset($_GET['id'])) {
    $cid = intval($_GET['id']);
} else {
    $cid = 0;
}

switch ($op) {
    case "v": //Purchase Receipt
        vendorpayment($cid);
        break;
    
    default:
        customerpayment($cid);
        break;
}


function customerpayment($cid)
{

    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#plist').DataTable({
                stateSave: true
            });
        });
    </script>

   
<div id="customPayment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Custom Payment</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4"><input type="text" id="amount" name="amount" class="form-control required" placeholder="Amount"></div>
                    <div class="col-md-4"><input type="text" name="tnote" class="form-control"  placeholder="Payment Note"></div>
                    <div class="col-md-4"> <div class="input-group date mdate" id="tsn_date"> <input type="text" class="form-control required" name="tdate" value="<?php echo date('d-m-Y');  ?>" data-date-format="DD-MM-YYYY"/>
                    	<span class="input-group-addon"> <span class="icon-calendar"></span> </span> </div>
                    </div>
                    
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="partpay">Add</button>

            </div>
        </div>
    </div>
</div>

    
    <div class="row">
        <div class="col-lg-12">
        <div style="padding:10px;"> <a data-csd="<?php echo $cid; ?>" class="btn btn-success customPayment" title="Partial Payment"><span class="icon-drawer"></span>Pay Now </a> </div>
            <div class="panel panel-default">
                <div id="notify" class="alert alert-success" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                
                    <div class="message"></div>
                </div>
                
                <div class="panel-body tbl">
				
				<div class="usertotal" style="padding:20px;">
                <div class="col-md-4">
                <?php 
				global $db, $siteConfig;
				$vsum = $db->sum('invoices', 'total', "csd='$cid' ");
		 		$vpaid = $db->sum('invoices', 'ramm', "csd='$cid' ");
				
				?>
				<p> Total Amount : <strong><?php echo $siteConfig['curr'].$vsum; ?></strong> </p>
				<p> Total Received : <strong> <?php echo $siteConfig['curr'].$vpaid; ?></strong> </p>
				<p> Due : <strong><?php echo  $siteConfig['curr'].($vsum-$vpaid); ?></strong> </p>
				
                </div>
                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <form method="get" action="view/payment-view.php">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required" name="f" value="<?php echo date('d-m-Y',strtotime("-1 month")); ?>" data-date-format="<?php echo $siteConfig['dformat2']; ?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="t" value="<?php echo date('d-m-Y'); ?>" data-date-format="<?php echo $siteConfig['dformat2']; ?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group" >
                                        <select name="d"><option value="0"> View</option> <option value="1"> download</option> </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                            	<input type="hidden" name="id" value="<?php echo $cid; ?>" />
                                <button type="submit" class="btn btn-primary "> Report </button>
                            </div>
                            <div class="clearfix"></div>
                            </form>
                		</div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr>
                
				</div>
				
				<?php

                    global $db;
                    $pquery = "SELECT 1 AS type,  invoices.tid AS id, part_trans.tdate AS date, SUM(part_trans.amount) AS credit, part_trans.note AS note FROM invoices INNER JOIN part_trans ON invoices.tid = part_trans.tid  WHERE invoices.csd = ".$cid." GROUP BY part_trans.tdate ORDER BY part_trans.tdate DESC";
					$iquery = "SELECT 2 AS type, tid AS id, tsn_date AS date, status AS note, total AS debit FROM invoices WHERE csd = ".$cid." ORDER BY tsn_date ASC";

                    $payments = $db->pdoQuery($pquery)->results();
					$invoices = $db->pdoQuery($iquery)->results();
					
					
					//$results = array_merge($payments, $invoices);
					
					function cmp($payments, $invoices){
						$pd = strtotime($payments['date']);
						$id = strtotime($invoices['date']);
						return ($pd-$id);
					}
					$results = array_merge($payments, $invoices);
					usort($results, 'cmp');
					
					
					

                    ?>
                    <div class="table-responsive">
                        <table id="plist" class="table cell-border" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Note</th>
                                <th> Action </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($results as $row) {
                                $date = $row["date"];
								if($row["type"] == 1) $bg =' style="background:#d5f0d4"';else $bg='';
								if(isset($row["debit"])) $debit = amountFormat($row["debit"]); else $debit = '';
								if(isset($row["credit"])) $credit = amountFormat($row["credit"]); else $credit = '';
								
								if($row["type"] == 2) {$btn = '<a href="index.php?rdp=view-invoice&amp;id='.$row["id"].'" class="btn btn-info btn-xs"><span class="icon-file-text2"></span></a>'; }
								else { $btn = '<a data-payment-type="customer" data-payment-date="'.$date.'" class="btn btn-danger btn-xs deletePayment" title="Delete"><span class="icon-bin"></span></a>'; }
								
								$note = $row["note"];
                                echo '<tr><td>'.date('Y-m-d',strtotime($date)).'</td><td>'.$debit.'</td><td>'.$credit.'</td><td>'.$note.'</td><td>'.$btn.'</td>';
                            }

                            ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<?php

}

function vendorpayment($cid){
	

    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#vlist').DataTable({
                stateSave: true
            });
        });
    </script>


    
    <div id="receiptPayment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Custom Payment</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4"><input type="text" id="amount" name="amount" class="form-control required"
                                                 placeholder="Amount"></div>
                    <div class="col-md-4"><input type="text" name="tnote" class="form-control"
                                                 placeholder="Payment Note"></div>
					<div class="col-md-4"> <div class="input-group date mdate" id="tsn_date"> <input type="text" class="form-control required" name="tdate" value="<?php echo date('d-m-Y');?>" data-date-format="DD-MM-YYYY"/>
                    	<span class="input-group-addon"> <span class="icon-calendar"></span> </span> </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="partpay">Add</button>

            </div>
        </div>
    </div>
</div>
    
    <div class="row">
        <div class="col-lg-12">
        <div style="padding:10px;"> <a data-csd="<?php echo $cid; ?>" class="btn btn-success receiptPayment" title="Partial Payment"><span class="icon-drawer"></span>Pay Now </a> </div>
            <div class="panel panel-default">
            	<div class="panel-heading">
                        <h4>New Vendor/Supplier Payment Details</h4>
                        <div class="clear"></div>
                    </div>
                <div id="notify" class="alert alert-success" style="display:none;">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                
                    <div class="message"></div>
                </div>
                <br><br>
                
                

                <div class="panel-body tbl">
				<div class="vendortotal">
                <div class="col-md-4">
				<?php 
				global $db, $siteConfig;
				$vsum = $db->sum('receipts', 'total', "csd='$cid' ");
		 		$vpaid = $db->sum('receipts', 'ramm', "csd='$cid' ");
				
				?>
				<p> Total Amount : <strong><?php echo $siteConfig['curr'].$vsum; ?></strong> </p>
				<p> Total Paid : <strong> <?php echo $siteConfig['curr'].$vpaid; ?></strong> </p>
				<p> Due : <strong><?php echo  $siteConfig['curr'].($vsum-$vpaid); ?></strong> </p>
				</div>
                
                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <form method="get" action="view/payment-view.php">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <div class="input-group date" id="tsn_due">
                                        <input type="text" class="form-control required" name="f" value="<?php echo date('d-m-Y',strtotime("-1 month")); ?>" data-date-format="<?php echo $siteConfig['dformat2']; ?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group date" id="tsn_date">
                                        <input type="text" class="form-control required" name="t" value="<?php echo date('d-m-Y'); ?>" data-date-format="<?php echo $siteConfig['dformat2']; ?>"/> <span class="input-group-addon"> <span class="icon-calendar"></span> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group" >
                                        <select name="d"><option value="0"> View</option> <option value="1"> download</option> </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                            	<input type="hidden" name="vid" value="<?php echo $cid; ?>" />
                                <button type="submit" class="btn btn-primary "> Report </button>
                            </div>
                            <div class="clearfix"></div>
                            </form>
                		</div>
                    </div>
                </div>
                <div class="clearfix"></div>
                
                <hr>
			</div>
            
            
				<?php

                    
					
					
					global $db;
                    $rpquery = "SELECT 1 AS type, receipts.tid AS id, receipt_trans.tdate AS date, SUM(receipt_trans.amount) AS debit, receipt_trans.note AS note FROM receipts INNER JOIN receipt_trans ON receipts.tid = receipt_trans.tid  WHERE receipts.csd = ".$cid." GROUP BY receipt_trans.tdate  ORDER BY tdate DESC";
                    $rquery = "SELECT 2 AS type, tid AS id, tsn_date AS date, status AS note, total AS credit FROM receipts WHERE csd = ".$cid." ORDER BY tsn_date ASC";


                    $rpayments = $db->pdoQuery($rpquery)->results();
					$receipts = $db->pdoQuery($rquery)->results();
					
					
					//$results = array_merge($payments, $invoices);
					
					function cmp($rpayments, $receipts){
						$pd = strtotime($rpayments['date']);
						$id = strtotime($receipts['date']);
						return ($pd-$id);
					}
					$results2 = array_merge($rpayments, $receipts);
					usort($results2, 'cmp');
					
					
                    ?>
                    
                    <div class="table-responsive">
                        <table id="vlist" class="table cell-border" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Note</th>
                                <th> Action </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($results2 as $row) {
                                $date = $row["date"];
								if($row["type"] == 1) $bg =' style="background:#d5f0d4"';else $bg='';
								if(isset($row["debit"])) $debit = amountFormat($row["debit"]); else $debit = '';
								if(isset($row["credit"])) $credit = amountFormat($row["credit"]); else $credit = '';
								
								
								if($row["type"] == 2) {$btn = '<a href="index.php?rdp=receipt&amp;op=edit&amp;id='.$row["id"].'" class="btn btn-info btn-xs"><span class="icon-file-text2"></span></a>'; }
								else { $btn = '<a data-payment-type="vendor" data-payment-date="'.$date.'" class="btn btn-danger btn-xs deletePayment" title="Delete"><span class="icon-bin"></span></a>'; }
								
								$note = $row["note"];
                                echo '<tr><td>'.date('Y-m-d',strtotime($date)).'</td><td>'.$debit.'</td><td>'.$credit.'</td><td>'.$note.'</td><td>'.$btn.'</td>';
                            }

                            ?>
                            </tbody>

                        </table>
                    </div>
                    
                    
                    
                    <!--<div class="table-responsive">
                        
                        <table id="mlist" class="table cell-border" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $gtotal = 0;
							foreach ($result as $row) {
                                $date = $row["tdate"];
                                $amount = amountFormat($row["amount"]);
								$gtotal = $gtotal + $row["amount"];
                                $note = $row["note"];
                                echo '<tr><td>'.date('d-m-Y',strtotime($row["tdate"])).'</td><td>'.$amount.'</td><td>'.$note.'</td> <td><a data-payment-type="vendor" data-payment-date="'.$date.'" class="btn btn-danger btn-xs deletePayment" title="Delete"><span class="icon-bin"></span></a></td>';
                            }

                            ?>
                            </tbody>

                        </table>
                        
                       
                    </div>-->
                </div>
            </div>
        </div>
    </div><?php


	}

?>

<div id="deletePayment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this?</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
        </div>
    </div>
</div>