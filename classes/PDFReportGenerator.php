<?php

require_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'eng.php';
require_once $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR. 'GreenAppleMail'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';

/**
 * Description of PDFReportGenerator
 *
 * @author Rishi
 */
class PDFReportGenerator {
    
    function __construct() {
        
    }
    
    function generatePDF($fileName){
        
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle(PDF_HEADER_TITLE);
        $pdf->SetSubject('Payment Receipt');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->setFooterData($tc=array(0,64,0), $lc=array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        //$pdf->setLanguageArray($l);

        $pdf->setFontSubsetting(true);

        $pdf->SetFont('dejavusans', '', 12, '', true);

        $pdf->AddPage();

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        
        $order = $_SESSION['userOrder'];
        
        $orderid = $order->getOrderNo();
        $clientid = $order->getClientid();
        $projectname = $order->getProjectName();
        $date = date('l F d Y \T\I\M\E: h:i A');
        
        if ($order->getIsCreditAvailableMode()){
            $amountcharged = $order->getAmountcharged();
            $paymentmethod = 'Credit Available';
            $email = $order->getPaymentEmail();
            
            $html = "
                <h1>Transaction Payment Report</h1>
                <i>Congratulations !! Transaction approved! Thank you for your order.</i>
                <h3>Transaction Details: </h3>
                <br/>
                Transaction Date        :   $date <br/><br/>
                Amount Charged          :   $$amountcharged <br/>
                Payment Method          :   $paymentmethod <br/>
                Order ID                :   $orderid <br/>
                Client ID               :   $clientid <br/>
                Project Name            :   $projectname <br/>
                Email                   :   $email <br/><br/><br/> ";
            
            $html = $html . '
				
				<h6>By placing this order you confirm having read, understood and agree with the <a href="www.greenapplemail.com/GreenAppleMail/public_html/terms.php">Green Apple Mail Terms of Service </a> and accept to be legally bound by them.</h6>
                
                ';
        }
        else{
        
            $response = $_SESSION['payflowresponse'];

            $paymentmethod = '';
            if ($response['TENDER'] == 'CC' || $response['TENDER'] == 'C'){
                $paymentmethod = 'Credit Card';
             }
             else if ($response['TENDER'] == 'P'){
                 $paymentmethod = 'PayPal';
             }

             $cardtype = "";         
             if (isset($response['CARDTYPE'])){
                if ($response['CARDTYPE'] == '0'){
                    $cardtype ='Visa';
                }
                else if ($response['CARDTYPE'] == '1'){
                    $cardtype ='MasterCard';
                }
                else if ($response['CARDTYPE'] == '2'){
                    $cardtype ='Discover';
                }
                else if ($response['CARDTYPE'] == '3'){
                    $cardtype ='American Expres';
                }
                else if ($response['CARDTYPE'] == '4'){
                    $cardtype ='Diner’s Club';
                }
                else if ($response['CARDTYPE'] == '5'){
                    $cardtype ='JCB';
                }
             }
            

             $transactionid = $response['PNREF'];
             $transactionmessage = $response['RESPMSG'];
             $amountcharged = $response['AMT'];
             $email = $response['EMAIL'];

            // Set some content to print
            $html = "
                <h1>Transaction Payment Report</h1>
                <i>Congratulations !! Transaction approved! Thank you for your order.</i>
                <h3>Transaction Details: </h3>
                <br/>
                Transaction Date        :   $date <br/><br/>
                Transaction ID          :   $transactionid <br/>
                Transaction Message     :   $transactionmessage <br/>
                Amount Charged          :   $$amountcharged <br/>
                Payment Method          :   $paymentmethod <br/>
                Order ID                :   $orderid <br/>
                Client ID               :   $clientid <br/>
                Project Name            :   $projectname <br/>
                Email                   :   $email <br/><br/><br/> ";

            $html = $html . '
				 <h6>By placing this order you confirm having read, understood and agree with the <a href="www.greenapplemail.com/GreenAppleMail/public_html/terms.php">Green Apple Mail Terms of Service </a> and accept to be legally bound by them.</h6>
                ';

            if (!empty($cardtype)){
                $html = $html . "Card Type                   :   $cardtype <br/>";
            }
        }

        //$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output($fileName, 'F');
    }
        
}

?>
