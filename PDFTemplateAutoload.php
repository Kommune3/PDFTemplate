<?php
/**
 * PDFTemplate SPL autoloader.
 * PHP Version 5
 * @package PDFTemplate
 * @link https://github.com/Kommune3/PDFTemplate The PHPMailer GitHub project
 * @author Nikolai Fischer <nikolai@kommune3.org>
 * @copyright 2017 Nikolai Fischer
 * @license <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * PDFTemplate SPL autoloader.
 * @param string $classname The name of the class to load
 */
function PDFTemplateAutoload($classname) {
  // Can't use __DIR__ as it's only in PHP 5.3+
  $filename = dirname(__FILE__).DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$classname.'.class';
  if(is_readable($filename)) {
    require $filename;
  }
}

if(version_compare(PHP_VERSION, '5.1.2', '>=')) {
  // SPL autoloading was introduced in PHP 5.1.2
  if(version_compare(PHP_VERSION, '5.3.0', '>=')) {
    spl_autoload_register('PDFTemplateAutoload', true, true);
  } else {
    spl_autoload_register('PDFTemplateAutoload');
  }
} else {
  /**
   * Fall back to traditional autoload for old PHP versions
   * @param string $classname The name of the class to load
   */
  function __autoload($classname) {
    PDFTemplateAutoload($classname);
  }
}