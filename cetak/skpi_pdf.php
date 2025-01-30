<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Methods:*');
require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Slamet Siswanto');
$pdf->SetTitle('Administrasi PPL');
$pdf->SetSubject('PPL');
$pdf->SetKeywords('PPL, PDF, Slamet, Siswanto');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// $fontname = $pdf->addTTFfont('arial.ttf', '', '', 32);
// set margins
// $pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
$pdf->SetMargins(20, 10, 15, 20); // put space of 10 on top 
// set auto page breaks
$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
  require_once(dirname(__FILE__).'/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');

$html="";
$pdf->AddPage('P', 'A4');

$pdf->writeHTML(str_replace("\'", "`", $html), true, false, true, false, '');
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$title="SKPI_";
$pdf->SetTitle($title);
ob_end_clean();
$pdf->Output($title.'.pdf', 'I');
?>
