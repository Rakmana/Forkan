<?php
/**
 * xml- PHP xml parser
 *
 * @package kool
 * @author Khedrane Atallah
 * @copyright 2003 - 2009 MY2CHA@30.DZ
 */
class xmldoc
{
    var $description = 'XML SKY parser';

    public static function init()
    {
    }
    /**
     * load: load xml resource file or string
     *
     * @access public
     * @param file $xml
     * @return string $xml
     */
    public static function load($xml)
    {


        if (is_file($xml)) {
            if (flib::exists($xml)) {
                @$ret = &simplexml_load_file($xml);
                //btrack('XML: ' . basename($xml));
            } else {
                $ret = false;
                //btrack('XML: File NOT Loaded: ' . basename($xml));
            }
            return $ret;
        } elseif (is_string($xml)) {
            //btrack('XML: String Loaded ');
            @$ret = &simplexml_load_string($xml);
            return $ret;
        } else {
            //btrack('XML: Resounce NOT Valid ');
            return false;
        }
    }
    /**
     * load xml string then convert it to an object.
     *
     * @access public
     * @param string $xml	
     * @return object $this->cd
     */
    public static function import($fileName)
    {
        $xml = xmldoc::load($fileName);
        return $xml;
    }
    public static function export($xmlObject, $file)
    {
        $xmlObject->asXML($file);
    }
    public static function get($xml, $xpath)
    {
        $element = &$xml->xpath($xpath);
        return $element;
    }

}

?>