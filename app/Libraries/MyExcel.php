<?php
namespace App\Libraries;


// require_once '\App\Libraries\Excel\PHPExcel\IOFactory.php';
require_once APPPATH . 'Libraries\Excel\PHPExcel\IOFactory.php';

use PHPExcel_IOFactory;

class MyExcel extends PHPExcel_IOFactory{

    function __construct()
    {
    }

}