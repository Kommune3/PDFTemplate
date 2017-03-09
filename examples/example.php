<?php
require_once '../PDFTemplateAutoload.php';

$pdf = new PDFTemplate();

// Basic settings
$pdf->setFilename('example.pdf');
$pdf->setTemplate('odt/example.odt');

// Set some vars
$pdf->addVar('first_name', 'John');
$pdf->addVar('last_name', 'Doe');

// Add a row
$row = new PDFTemplateRow('demoitems');
$row->addVar('position', '1');
$row->addVar('title', 'This is a title');
$row->addVar('quantity', '5');
$row->addVar('price', '15.99');

$pdf->addRow($row);

// Finally create the PDF
$pdf->createPDF();