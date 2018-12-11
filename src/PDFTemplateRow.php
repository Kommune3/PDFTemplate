<?php

namespace PDFTemplate;

class PDFTemplateRow {
  public $rowName = FALSE;
  public $row = array();

  function __construct($rowName) {
    $this->rowName = $rowName;
  }

  public function addVar($name, $value) {
    $this->row[$this->rowName][$name] = $value;
  }
}
