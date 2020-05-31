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

function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['G']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter in 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}

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
        $this->SetFont($this->font, 'B', 8);
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
        
        $notey = $this->GetY();
        $this->SetXY($x + 128, $y);
        $col2 = SUB;
        $this->MultiCell(36, 10, $col2, 1, 'L', true);
        $this->SetXY($x + 162, $y);
        $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[0]), 1, 'R', true);
        $this->Ln(0);
        $this->SetXY($x, $y + 20);
        if (!empty($detail[6]) and get_settings('show_payment_mode') ==1) {
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

        //new start
            $y = $this->GetY();
            $this->SetXY($x + 128, $y);
            $col3 = TTL;
            $this->MultiCell(34, 10, $col3, 1, 'L');
            $this->SetXY($x + 162, $y);
            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($detail[4]), 1, 'R');
       
        if ($detail[6] =='partial') {
            //echo $this->paid ;exit;
            $y = $this->GetY();
            $this->SetXY($x + 128, $y);
            $this->SetFont($this->font, '', 11);
             $col3 = ' Partial';
             $rn = $this->paid;
             $rn2 = round($this->due, 0, PHP_ROUND_HALF_UP);
            $rn0 = $rn2 - $rn;
             $this->MultiCell(36, 10, $col3, 1, 'L', true);
             $this->SetXY($x + 162, $y);
   
            $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($rn), 1,      'R', true);

        }
       
        // $y = $this->GetY();
        // $this->SetXY($x + 128, $y);
        // $this->SetFont($this->font, '', 11);
         // $col3 = ' ROUNDOFF';
        //  $rn = $this->due;
         // $rn2 = round($this->due, 0, PHP_ROUND_HALF_UP);
        // $rn0 = $rn2 - $rn;
         // $this->MultiCell(36, 10, $col3, 1, 'L', true);
          //$this->SetXY($x + 162, $y);

         //$this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($rn0), 1,      'R', true);
        
         $ttltxt = "Total Due";
         $ttlamnt = $this->due;

         if ($detail[6] =='due') {
         $ttltxt = "Total Due";
         $ttlamnt = $detail[4]; 
         }

        $y = $this->GetY();
        $this->SetXY($x + 128, $y);
        $this->SetFont($this->font, 'B', 11);
        $col3 = $ttltxt;
        $this->MultiCell(34, 10, $col3, 1, 'L', false);
        $this->SetXY($x + 162, $y);
        $this->MultiCell(28, 10, $this->curr . '' . $this->amountFormat($ttlamnt), 1,  'R', false);
        
        if($this->GetY() < $notey){ $y = $notey ;}else {$y = $this->GetY();}
            $this->SetXY($x, $y);
            $this->Ln();
    }


    function Terms($terms)
    {
        // Billing Terms
        $y = $this->GetY();
        $this->Ln(0);
        
        $this->Line(10, $y, 200, $y);
        $this->SetFont($this->font, '', 9);
        $this->MultiAlignCell(190, 8, iconv('UTF-8', 'windows-1252',$this->WriteHTML($terms)), 0, 0, 'L');

        
        


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

    protected $B;
protected $I;
protected $U;
protected $HREF;
protected $fontList;
protected $issetfont;
protected $issetcolor;

function __construct($orientation='P', $unit='mm', $format='A4')
{
    //Call parent constructor
    parent::__construct($orientation,$unit,$format);

    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';

    $this->tableborder=0;
    $this->tdbegin=false;
    $this->tdwidth=0;
    $this->tdheight=0;
    $this->tdalign="L";
    $this->tdbgcolor=false;

    $this->oldx=0;
    $this->oldy=0;

    $this->fontlist=array("arial","times","courier","helvetica","symbol");
    $this->issetfont=false;
    $this->issetcolor=false;
}

//////////////////////////////////////
//html parser

function WriteHTML($html)
{
    $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><hr><td><tr><table><sup>"); //remove all unsupported tags
    $html=str_replace("\n",'',$html); //replace carriage returns with spaces
    $html=str_replace("\t",'',$html); //replace carriage returns with spaces
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //explode the string
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            elseif($this->tdbegin) {
                if(trim($e)!='' && $e!="&nbsp;") {
                    $this->Cell($this->tdwidth,$this->tdheight,$e,$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
                }
                elseif($e=="&nbsp;") {
                    $this->Cell($this->tdwidth,$this->tdheight,'',$this->tableborder,'',$this->tdalign,$this->tdbgcolor);
                }
            }
            else
                $this->Write(5,stripslashes(txtentities($e)));
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    //Opening tag
    switch($tag){

        case 'SUP':
            if( !empty($attr['SUP']) ) {    
                //Set current font to 6pt     
                $this->SetFont('','',6);
                //Start 125cm plus width of cell to the right of left margin         
                //Superscript "1" 
                $this->Cell(2,2,$attr['SUP'],0,0,'L');
            }
            break;

        case 'TABLE': // TABLE-BEGIN
            if( !empty($attr['BORDER']) ) $this->tableborder=$attr['BORDER'];
            else $this->tableborder=0;
            break;
        case 'TR': //TR-BEGIN
            break;
        case 'TD': // TD-BEGIN
            if( !empty($attr['WIDTH']) ) $this->tdwidth=($attr['WIDTH']/4);
            else $this->tdwidth=40; // Set to your own width if you need bigger fixed cells
            if( !empty($attr['HEIGHT']) ) $this->tdheight=($attr['HEIGHT']/6);
            else $this->tdheight=6; // Set to your own height if you need bigger fixed cells
            if( !empty($attr['ALIGN']) ) {
                $align=$attr['ALIGN'];        
                if($align=='LEFT') $this->tdalign='L';
                if($align=='CENTER') $this->tdalign='C';
                if($align=='RIGHT') $this->tdalign='R';
            }
            else $this->tdalign='L'; // Set to your own
            if( !empty($attr['BGCOLOR']) ) {
                $coul=hex2dec($attr['BGCOLOR']);
                    $this->SetFillColor($coul['R'],$coul['G'],$coul['B']);
                    $this->tdbgcolor=true;
                }
            $this->tdbegin=true;
            break;

        case 'HR':
            if( !empty($attr['WIDTH']) )
                $Width = $attr['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.2);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(1);
            break;
        case 'STRONG':
            $this->SetStyle('B',true);
            break;
        case 'EM':
            $this->SetStyle('I',true);
            break;
        case 'B':
        case 'I':
        case 'U':
            $this->SetStyle($tag,true);
            break;
        case 'A':
            $this->HREF=$attr['HREF'];
            break;
        case 'IMG':
            if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                if(!isset($attr['WIDTH']))
                    $attr['WIDTH'] = 0;
                if(!isset($attr['HEIGHT']))
                    $attr['HEIGHT'] = 0;
                $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
            }
            break;
        case 'BLOCKQUOTE':
        case 'BR':
            $this->Ln(5);
            break;
        case 'P':
            $this->Ln(10);
            break;
        case 'FONT':
            if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                $coul=hex2dec($attr['COLOR']);
                $this->SetTextColor($coul['R'],$coul['G'],$coul['B']);
                $this->issetcolor=true;
            }
            if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                $this->SetFont(strtolower($attr['FACE']));
                $this->issetfont=true;
            }
            if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist) && isset($attr['SIZE']) && $attr['SIZE']!='') {
                $this->SetFont(strtolower($attr['FACE']),'',$attr['SIZE']);
                $this->issetfont=true;
            }
            break;
    }
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='SUP') {
    }

    if($tag=='TD') { // TD-END
        $this->tdbegin=false;
        $this->tdwidth=0;
        $this->tdheight=0;
        $this->tdalign="L";
        $this->tdbgcolor=false;
    }
    if($tag=='TR') { // TR-END
        $this->Ln();
    }
    if($tag=='TABLE') { // TABLE-END
        $this->tableborder=0;
    }

    if($tag=='STRONG')
        $tag='B';
    if($tag=='EM')
        $tag='I';
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
    if($tag=='FONT'){
        if ($this->issetcolor==true) {
            $this->SetTextColor(0);
        }
        if ($this->issetfont) {
            $this->SetFont('arial');
            $this->issetfont=false;
        }
    }
}

function SetStyle($tag, $enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s) {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}
}

