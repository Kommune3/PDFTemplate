# PDFTemplate - Create PDF's from ODT Files and replace placeholders/variables

This modules offers an easy way to create PDFs from OpenDocument (.odt) templates. The magic: Each template can hold a
variety of placeholders/variables that can easy be replaced.

This module integrates the free www.pdftemplate.eu service. No registration required!

## A simple example

```php
<?php
require 'PDFTemplateAutoload.php';

$pdf = new PDFTemplate();

// Basic settings
$pdf->setFilename('example.pdf');
$pdf->setTemplate('odt/example.odt');       // Path to your local Open Document Template

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
```