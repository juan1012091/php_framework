<?php
/**
 * User: JuanG
 * Date: 21/03/2015
 * Time: 12:49 AM
 */
date_default_timezone_set('America/Monterrey');
error_reporting(1);//For debug use 1 and 0 on production
class Root {
    private static $ServerName = "localhost";
    public static function ServerUrl()
    {
        $rootDir = basename(__DIR__);
        $URL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $URL .= self::$ServerName.":".$_SERVER["SERVER_PORT"]."/".$rootDir;
        }
        else
        {
            $URL .= self::$ServerName."/".$rootDir;
        }
        return $URL;
    }
    public static function Url($url)
    {
        return dirname(__FILE__).$url;
    }

    public static function GetUrlServer($url)
    {
        return self::ServerUrl().$url;
    }

    public static function UseFile($url)
    {
        return require_once(self::Url($url));
    }
    public static function DataBase()
    {
        return require_once(self::Url("/Config/conexionBD.php"));
    }
    public static function Images($pictureName)
    {
        return self::ServerUrl()."/Resources/Images/" . $pictureName;
    }
    //Added by JGRG 16/10/2016
    public static function Session()
    {
        return require_once(self::Url("/BLL/Session.php"));
    }
    //Utilizar BLL, DAL, Objects especificos
    public static function UseDAL($file)
    {
        $filePath = self::Url("/DAL/".$file.".php");
        if(file_exists($filePath))
        {
            include_once $filePath;
        }
    }

    public static function UseBLL($file)
    {
        $filePath = self::Url("/BLL/".$file.".php");
        if(file_exists($filePath))
        {
            include_once $filePath;
        }
    }

    public static function UseObjects($file)
    {
        $filePath = self::Url("/Objects/".$file.".php");
        if(file_exists($filePath))
        {
            include_once $filePath;
        }
    }
    public static function dateFormat($string){
        if($string != null && $string != ""){
            $date = new DateTime($string);
            return $date->format('Y-m-d');
        }else{
            return null;
        }
    }
    public static function dateTimeFormat($string, $type=4){
        if($string != null && $string != ""){
            $date = new DateTime($string);
            $formated = null;
            switch ($type) {
                case 1://output: 24/03/2012 17:45:12
                    $formated = $date->format('d/m/Y H:i:s');
                    break;
                case 2://#output: 2012-03-24 5:45 PM
                    $formated = $date->format('Y-m-d g:i A');
                    break;
                case 3://output: 2012-03-24 05:45pm
                    $formated = $date->format('Y-m-d G:ia');
                    break;
                default://output: 2012-03-24 17:45:12
                    $formated = $date->format('Y-m-d H:i:s');
                    break;
            }

            return $formated;
        }else{
            return null;
        }
    }
    public static function timeFormat($string){
        if($string != null && $string != ""){
            $date = new DateTime($string);
            return $date->format('H:i:s');
        }else{
            return null;
        }
    }
    public static function getDatetime(){
        return date("Y-m-d H:i:s");
    }
    public static function getGUID(){
        if (function_exists('com_create_guid')){
            return trim(com_create_guid(), '{}');
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);// "}"
            return $uuid;
        }
    }
    public static function formatMoney($value){
        //in Xampp enable the extension extension=intl in the php.ini file
        $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
        $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'MXN');
        $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        return $fmt->formatCurrency($value, 'MXN');
    }
} 