<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  E-mail: support@skyresoft.com
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
//bill to pdf class for printing
require('../lib/fpdf/fpdf.php');
require('../lang/lang.php');

class ExperssInvoice extends FPDF
{
    var $col = 0; // Current column
    var $font = 'Helvetica';
    var $date;
    var $paid;
    var $due;

    function billTitle($title, $totitle)
    {
        $this->btitle = $title;
        $this->tbtitle = $totitle;
    }

    
    
    

    function Header()
    {
        
        //BILL TYPE
        $this->SetTextColor(60);
        $this->SetFont($this->font, 'B', 14);
        $this->Cell(0, 5, $this->btitle, 0, 1, 'C');
        $this->SetFont($this->font, '', 9);
        
		if(isset($_GET['f']) and isset($_GET['t']) and date('Y-m-d',strtotime($_GET['f'])) < date('Y-m-d',strtotime($_GET['t'])) ) {
			$this->Ln(1);
			$this->SetTextColor(60);
        	$this->SetFont($this->font, '', 9);
        	$this->Cell(0, 5, '('.$_GET['f'].' to '.$_GET['t'].')', 0, 1, 'C');
        	$this->SetFont($this->font, '', 9);
        	
		}
		$this->Ln(3);
		$this->SetTextColor(60);
        $this->SetFont($this->font, 'B', 9);
        $this->Cell(0, 5, $this->tbtitle, 0, 1, 'C');
        $this->SetFont($this->font, '', 9);
        $this->Ln(5);


      }


     
    function BillBody($pdata)
    {
        //product tables
        $header = array(
            'Date',
			'Note',
            'Debit',
            'Credit');

        // Table Colors, line width and bold font
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(184);
        $this->SetLineWidth(.1);
        $this->SetFont($this->font, 'B');
        // Header
        $w = array(
            25,
			105,
            30,
            30);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(240);
        $this->SetTextColor(60);
        $this->SetFont($this->font);
        // Data
		$debittotal = 0;
		$credittotal = 0;
        $fill = false;
        foreach ($pdata as $key => $value) {
            
			
			$date = $value['date'];
			if(isset($value["debit"])) $debit = $value["debit"]; else $debit = '';
			if(isset($value["credit"])) $credit = $value["credit"]; else $credit = '';
            
			$debittotal = $debittotal + $debit; 
			$credittotal = $credittotal + $credit;

            if (strlen($date) > 60) {
                $date = substr($date, 0, 60) . "...";
            }
            $this->Cell($w[0], 7, iconv('UTF-8', 'windows-1252', date('d-m-Y',strtotime($date))), 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 7, $value['note'], 'LR', 0, 'L', $fill);
			$this->Cell($w[2], 7, $debit, 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 7, $credit, 'LR', 0, 'R', $fill);
            
			 $this->Ln();
            $fill = !$fill;
        }
		$balancetotal = $debittotal - $credittotal;
		
		$this->Ln(2);
		$this->SetFont($this->font);
		$this->Cell(25, 7, '', '', 0, 'R');
		$this->Cell(105, 7, 'Balance', '', 0, 'R');
		$this->Cell(30, 7, '', '0', 0, 'R');
		$this->Cell(30, 7, amountFormat($balancetotal), '0', 0, 'R');
		
		$this->Ln(5);
		$this->SetFont($this->font, 'B');
		$this->Cell(25, 7, '', '', 0, 'R');
		$this->Cell(105, 7, 'Total', '', 0, 'R');
		$this->Cell(30, 7, amountFormat($debittotal), '0', 0, 'R');
		$this->Cell(30, 7, amountFormat($credittotal), '0', 0, 'R');
        
		
		
		
    }

  
    //end methods
}

