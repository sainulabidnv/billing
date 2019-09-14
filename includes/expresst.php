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

    function billingDate($date)
    {
        $this->bdate = $date;
    }

    function currF($curr)
    {
        $this->curr = $curr;
    }

    function dueDate($ddate)
    {
        $this->dbdate = $ddate;
    }

    function billNo($bno)
    {
        $this->billnumber = $bno;
    }

    function biller($name, $add1, $add2, $phone, $email, $tin)
    {
        $this->billername = $name;
        $this->add1 = $add1;
        $this->add2 = $add2;
        $this->phone = $phone;
        $this->email = $email;
        $this->tin = $tin;
    }

    function payee($name, $add1, $add2, $phone, $email, $taxid = "")
    {
        $this->pname = $name;
        $this->padd1 = $add1;
        $this->padd2 = $add2;
        $this->pphone = $phone;
        $this->pemail = $email;
        if ($taxid != "") {
            $this->ptax = TAX . ' ' . $taxid;
        } else {
            $this->ptax = '';
        }

    }

    function Header()
    {
        $this->Ln(3);
        $this->Image('../images/logo.jpg', 10, 10, 30);
        //BILL TYPE
        $this->SetTextColor(60);
        $this->SetFont($this->font, 'B', 18);
        $this->Cell(0, 5, $this->btitle, 0, 1, 'R');
        $this->SetFont($this->font, '', 9);
        $this->Ln(5);

        $lineheight = 5;
        $this->SetFont($this->font, 'B', 12);
        $positionX = 140;

        //Bill Number
        $this->Cell($positionX, $lineheight);
        $this->SetFont($this->font, 'B', 9);

        $this->Cell(20, $lineheight, $this->btitle . ' NO', 0, 0, 'L');

        $this->SetFont($this->font, '', 11);
        $this->Cell(0, $lineheight, $this->billnumber, 0, 1, 'R');

        //Billing Date
        $this->Cell($positionX, $lineheight);
        $this->SetFont($this->font, 'B', 9);

        $this->Cell(32, $lineheight, BDATE, 0, 0, 'L');

        $this->SetFont($this->font, '', 10);
        $this->Cell(0, $lineheight, $this->bdate, 0, 1, 'R');

        //Due date

        $this->Cell($positionX, $lineheight);
        $this->SetFont($this->font, 'B', 9);

        $this->Cell(32, $lineheight, DDATE, 0, 0, 'L');
        $this->SetFont($this->font, '', 10);

        $this->Cell(0, $lineheight, $this->dbdate, 0, 1, 'R');
        $this->Ln(12);
    }

    function currencyFormat($set)
    {
        $this->setc = $set;
    }

    function amountFormat($number)
    {
        //Format money as per country
        if ($this->setc == 1) {
            return number_format($number, 2, ',', '.');
        } else {
            return number_format($number, 2, '.', ',');
        }
    }

    private function MultiAlignCell($w, $h, $text, $border = 0, $ln = 0, $align =
    'L', $fill = false)
    {
        // Store reset values for (x,y) positions
        $x = $this->GetX() + $w;
        $y = $this->GetY();

        // Make a call to FPDF's MultiCell
        $this->MultiCell($w, $h, $text, $border, $align, $fill);

        // Reset the line position to the right, like in Cell
        if ($ln == 0) {
            $this->SetXY($x, $y);
        }
    }

    function Party()
    {
        // customer and company info
        $this->SetDrawColor(222);
        $this->SetFillColor(90);
        $this->Line(10, 40, 200, 40);
        $this->Rect(10, $this->GetY(), 189.5, 8, 'DF');
        $this->Rect(10, $this->GetY(), 189.5, 53, 'D');
        $this->SetFillColor(222);
        $this->Rect(105, $this->GetY(), 0.25, 52.9, 'F');

        $this->SetTextColor(255);
        $this->SetFont($this->font, '', 12);
        $this->SetFillColor(90);
        $this->SetDrawColor(90);
        $this->Cell(3);
        $this->Cell(90, 8, iconv('UTF-8', 'windows-1252', $this->tbtitle), 0, 0, 'L', false);
        $this->Cell(5);
        $this->Cell(90, 8, INFO, 0, 1, 'L', false);

        $this->Ln(4);
        $this->SetTextColor(60);
        $this->SetFont($this->font, 'B', 11);

        $this->Cell(3);
        $this->Cell(90, 4, iconv('UTF-8', 'windows-1252', $this->pname), 0, 0, 'L');
        $this->Cell(5);
        $this->Cell(90, 4, iconv('UTF-8', 'windows-1252', $this->billername), 0, 1, 'L');
        $this->Ln(3);
        $this->SetFont($this->font, '', 10);
        $x = $this->x;
        $y = $this->y;
        $push_right = 0;
        $w = 95;
        $this->Cell(3);
        $this->MultiCell(80, 6, iconv('UTF-8', 'windows-1252', $this->padd1) . "\r\n" .
            iconv('UTF-8', 'windows-1252', $this->padd2) . "\r\n" . $this->pphone . "\r\n" .
            $this->pemail . "\r\n" . $this->ptax, 0, 'L', 0);
        //Subtotal columns
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->Cell(3);
        $this->MultiCell($w, 6, iconv('UTF-8', 'windows-1252', $this->add1) . "\r\n" .
            iconv('UTF-8', 'windows-1252', $this->add2) . "\r\n" . $this->phone . "\r\n" . $this->
            email . "\r\n" . $this->tin, 0, 'L', 0);
        $this->Ln(4);
        $this->Ln(6);
    }

    function BillBody($pdata)
    {
		//$pdata[0]['trate'] = 0;
		if($pdata[0]['trate']>0) { $header = array( PRODUCT, QTY, RATE, TAXV . '(%)', TAXV2 . '(%)', SUBT); }
		else { $header = array( PRODUCT, QTY, RATE, SUBT); }
        
		 // Table Colors, line width and bold font
        $this->SetFillColor(90);
        $this->SetTextColor(255);
        $this->SetDrawColor(184);
        $this->SetLineWidth(.3);
        $this->SetFont($this->font, 'B');
        // Header
        if($pdata[0]['trate']>0) { $w = array( 70, 20,  20, 25, 25, 30); } 
		else { $w = array( 120, 20,  20, 30); }
		
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(240);
        $this->SetTextColor(60);
        $this->SetFont($this->font);
        // Data
        $fill = false;
        foreach ($pdata as $key => $value) {
            $item_product = $value['product'];
            if ($value['discount'] > 0.00) {
                $tx = $value['qty']*$value['price']*$value['trate']/100 . ' (' . $value['trate'] . ')';
				$tx2 = $value['qty']*$value['price']*$value['trate2']/100 . ' (' . $value['trate2'] . ')';
            } else {
                $tx = '';
				$tx2 = '';
            }

            if (strlen($item_product) > 60) {
                $item_product = substr($item_product, 0, 60) . "...";
            }
            $this->Cell($w[0], 7, iconv('UTF-8', 'windows-1252', $item_product), 'LR', 0,
                'L', $fill);
            $this->Cell($w[1], 7, $value['qty'], 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 7, $this->amountFormat($value['price']), 'LR', 0, 'C', $fill);
            if($pdata[0]['trate']>0) {
			$this->Cell($w[3], 7, $tx, 'LR', 0, 'C', $fill);
			$this->Cell($w[3], 7, $tx2, 'LR', 0, 'C', $fill);
			
            $this->Cell($w[4], 7, $this->amountFormat($value['subtotal']), 'LR', 0, 'C', $fill);
            }
			else { $this->Cell($w[3], 7, $this->amountFormat($value['subtotal']), 'LR', 0, 'C', $fill); }
			$this->Ln();
            $fill = !$fill;
        }

        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln(10);
    }

    function PTotal($paid = '', $due = '')
    {
        $this->paid = $paid;
        $this->due = $due;
    }

    function BillTotal(array $detail)
    {
        //bill summary
        $page_height = 297;
        $space_left = $page_height - ($this->GetY() + 20);
        if (40 > $space_left) {
            $this->AddPage(); // page break
        }

        $this->Ln(0);
        $x = $this->GetX();
        $y = $this->GetY();
        if (!empty($detail[5])) {
            $this->SetFont($this->font, '', 9);
            $col1 = CNOTE . ' : ' . $detail[5];
            $this->MultiCell(120, 8, $col1, 0, 1);
            $this->SetFont($this->font, '', 10);
        }

        $notey = $this->GetY();
        $this->SetXY($x + 128, $y);
        $col2 = SUB;
        $this->MultiCell(36, 10, $col2, 1, 'L', true);
        $this->SetXY($x + 162, $y);
        $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[0]), 1,
            'R', true);
        $this->Ln(0);
        $this->SetXY($x, $y + 20);
        if (!empty($detail[6])) {
            $this->SetDrawColor(60);
            $this->SetTextColor(60);
            $this->SetLineWidth(1);
            $this->SetFont($this->font, 'B', 11);
            $this->MultiCell(30, 10, strtoupper($detail[6]), 1, 'C');
        } else {
            $this->MultiCell(30, 10, '', 0, 'C');
        }
        $this->SetDrawColor(184);
        $this->SetLineWidth(.3);
        $this->SetFont($this->font, '', 10);
        $y = $this->GetY();
        $this->SetXY($x + 128, $y - 20);
        if ($detail[2] > 0) {
            $col3 = TAXV;
            $this->MultiCell(34, 10, $col3, 1, 'L');
            $this->SetXY($x + 162, $y - 20);
            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[2]), 1,
                'R');
        }
		
		$y = $this->GetY();
        $this->SetXY($x + 128, $y);
        if ($detail[7] > 0) {
            $col3 = TAXV2;
            $this->MultiCell(34, 10, $col3, 1, 'L');
            $this->SetXY($x + 162, $y);
            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[7]), 1,
                'R');
        }
		
        $y = $this->GetY();
        $this->SetXY($x + 128, $y);
        if ($detail[1] > 0) {
            $col3 = DIS;
            $this->MultiCell(34, 10, $col3, 1, 'L');
            $this->SetXY($x + 162, $y);
            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[1]), 1,
                'R');
        }
        $y = $this->GetY();
        $this->SetXY($x + 128, $y);
        $col3 = SHIP;
        if ($detail[3] > 0) {
            $this->MultiCell(34, 10, $col3, 1, 'L');
            $this->SetXY($x + 162, $y);

            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[3]), 1,
                'R');
        }
        if ($this->paid = '') {
            $y = $this->GetY();
            $this->SetXY($x + 128, $y);
            $col3 = TTL;
            $this->MultiCell(34, 10, $col3, 1, 'L');
            $this->SetXY($x + 162, $y);
            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[4]), 1,
                'R');
            $y = $this->GetY();
            $this->SetXY($x + 128, $y);
            $col3 = PPAID;
            $this->MultiCell(34, 10, $col3, 1, 'L');
            $this->SetXY($x + 162, $y);
            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($this->paid), 1,
                'R');
        } else {
            $this->due = $detail[4];
        }
        // $y = $this->GetY();
        // $this->SetXY($x + 128, $y);
        // $this->SetFont($this->font, '', 11);
        //  $col3 = ' ROUNDOFF';
        //  $rn = $this->due;
        //  $rn2 = round($this->due, 0, PHP_ROUND_HALF_UP);
        //  $rn0 = $rn2 - $rn;
        //  $this->MultiCell(36, 10, $col3, 1, 'L', true);
        //  $this->SetXY($x + 162, $y);

        //  $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($rn0), 1,
        //      'R', true);
        $y = $this->GetY();
        $this->SetXY($x + 128, $y);
        $this->SetFont($this->font, 'B', 11);
        $col3 = DUE;
        $this->MultiCell(34, 10, $col3, 1, 'L', false);
        $this->SetXY($x + 162, $y);

        $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($this->due), 1,
            'R', false);
        
        if($this->GetY() < $notey){ $y = $notey ;}else {$y = $this->GetY();}
            $this->SetXY($x, $y);
            $this->Ln();
    }


    function Terms($terms)
    {
        // Billing Terms
        $y = $this->GetY();
        $this->Ln(6);
        $this->SetFont($this->font, 'B', 12);
        $this->Cell(0, 6, NOTE, 0, 1, 'L');
        $this->Line(10, $y, 200, $y);
        $this->SetFont($this->font, '', 9);
        $this->MultiAlignCell(190, 8, iconv('UTF-8', 'windows-1252', $terms), 0, 0, 'L');
        


    }

    function FooterNote($footer)
    {
        $this->footern = $footer;
    }

    function Footer()
    {
        // Page footer and note
        $this->GetY();
        $this->SetY(-15);
        $this->SetFont($this->font, 'I', 8);
        $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', $this->footern), 0, 0, 'L');
        $this->SetTextColor(128);
        $this->Cell(0, 10, PAGE . $this->PageNo(), 0, 0, 'R');
    }
    //end methods
}

