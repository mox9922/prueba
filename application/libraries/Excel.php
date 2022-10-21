<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Excel
{
    public function Phpexcelsystem(){
        require_once('PHPExcel/Classes/PHPExcel/IOFactory.php');
    }

    public function IOFactory(){
        require_once('PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php');
    }
}