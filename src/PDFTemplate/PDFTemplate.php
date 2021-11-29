<?php

namespace PDFTemplate;

/**
 * PDFTemplate class
 * PHP Version 5
 * @link http://www.pdftemplate.eu The PDFTemplate Webpage
 * @author Nikolai Fischer <nikolai@kommune3.org>
 * @copyright 2017 Nikolai Fischer
 * @license <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

class PDFTemplate {
  public $settings = NULL;
  public $vars = array();
  public $images = array();
  public $destination = NULL;
  public $filename = NULL;
  public $file = NULL;
  public $template = array();
  public $rows = array();

  /**
   * Set the destination of the Open Document (odt) template file.
   *
   * @param string $template_path
   */
  public function setTemplate($template_path) {
    $file_data = file_get_contents($template_path);
    $this->template = array(
      'extension' => 'odt',
      'base64' => base64_encode($file_data),
    );
  }

  /**
   * Set the filename for the created PDF.
   *
   * @param string $filename
   */
  public function setFilename($filename) {
    $this->filename = $filename;
  }

  /**
   * Set destination for the created PDF.
   *
   * @param string $destination
   */
  public function setDestination($destination) {
    $this->destination = $destination;
  }

  /**
   * Add a variable.
   *
   * @param string $name
   *   The name of the variable to set
   * @param string $value
   *   The value of the variable
   */
  public function addVar($name, $value) {
    $this->vars[$name] = $value;
  }

  /**
   * Add a row.
   *
   * @param PDFTemplateRow $row
   */
  public function addRow(PDFTemplateRow $row) {
    $this->rows[$row->rowName][] = $row->row[$row->rowName];
  }

  /**
   * Add an image.
   *
   * @param string $name
   *   The name of the variable
   * @param string $iamge_url
   *   The url of the image
   */
  public function addImage($name, $image_url) {
    if ($image = file_get_contents($image_url)) {
      $path_info = pathinfo($image_url);

      $extention = $path_info['extension'];
      $extension = substr($extention, 0, strpos($extention, '?'));

      $this->vars[$name] = array(
        'type' => 'image',
        'extension' => $extension,
        'base64' => base64_encode($image)
      );

      $data = base64_decode(base64_encode($image));
    }
  }

  /**
   * Add PDFTemplate var from image string.
   *
   * @param string $name
   * @param string $image
   * @param string $extension
   */
  public function addImageFromString($name, $image, $extension) {

    $this->vars[$name] = array(
      'type' => 'image',
      'extension' => $extension,
      'base64' => base64_encode($image),
    );
  }

  /**
   * Generate the PDF.
   *
   * @return file
   */
  public function createPDF() {
    $this->settings['return'] = 'base64';

    $data = $this->settings;
    $data['vars'] = array_merge($this->vars, $this->rows);
    $data['template'] = $this->template;

    foreach ($this->images as $key => $image) {
      $data[$key] = array(
        'type' => 'image',
        'extension' => $image['extension'],
        'base64' => $image['base64'],
      );
    }

    $result = $this->sendRequest('job', $data);

    if ($result->status == 200) {
      $pdf_decoded = base64_decode($result->base64);
      $destination = $this->destination . $this->filename;
      $file = fopen($destination, "wb");
      fwrite($file, $pdf_decoded);
      fclose($file);
      return $file;
    }
  }

  private function sendRequest($method, $data) {
    $data_string = json_encode($data);

    $ch = curl_init('http://api.pdftemplate.eu/v1/pdf/create');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $ch,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
      )
    );

    $result = curl_exec($ch);
    $result = json_decode($result);
    return $result;
  }
}
