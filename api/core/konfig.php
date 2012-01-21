<?php

/**
 * konfig
 * 
 * @package Wapl
 * @author Jnom23
 * @copyright 2011
 * @version $Id$
 * @access public
 */
class konfig
{
    public $xml = array();

    /**
     * konfig::init()
     * 
     * @return
     */
    public static function init()
    {
        // create the KONFIG object
        konfig::load('sys', PXML . 'konfig.xml');
        // Lets make sure we save serialized data
        register_shutdown_function(array($GLOBALS['KONFIG'], 'xit'));
    }
    /**
     * konfig::load()
     * 
     * @param mixed $section
     * @param mixed $xmlfile
     * @param bool $overwrite
     * @return
     */
    static function load($section, $xmlfile, $overwrite = false)
    {
        // create the KONFIG object
        $GLOBALS['KONFIG'] = kore::getInstance('konfig');
        if (isset($GLOBALS['KONFIG']->xml[$section]) and !$overwrite) {
            //$GLOBALS['KONFIG']->xml[$section] = xmldoc::import($xmlfile);
        } else
            $GLOBALS['KONFIG']->xml[$section] = xmldoc::import($xmlfile);
        //btrack('KFG File loaded: '.$section);
    }


    /**
     * konfig::set()
     * 
     * @param mixed $name
     * @param mixed $val
     * @param string $section
     * @return
     */
    public static function set($name, $val, $section = 'sys')
    {
        $key = $GLOBALS['KONFIG']->xml[$section]->xpath("//key[@name='$name']");
        $key[0][0] = $val;
        btrack('SET changed: ' . $name . '>' . $val);
    }
    /**
     * konfig::get()
     * 
     * @param mixed $name
     * @param string $section
     * @return
     */
    public static function get($name, $section = 'sys')
    {
        $key = @$GLOBALS['KONFIG']->xml[$section]->xpath("//key[@name='$name']");
        if (isset($key[0][0])) {
            return $key[0][0];
        } else {
            if (konfig::isOn('error_konfig'))
                debugger::warn("sorry, Key not found :  #$name!");
            return false;
        }
    }
    /**
     * konfig::isOn()
     * 
     * @param mixed $name
     * @param string $section
     * @return
     */
    public static function isOn($name, $section = 'sys')
    {
        if (konfig::get($name, $section) and (strtoupper(konfig::get($name, $section)) ==
            'ON')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * konfig::AcrSet()
     * 
     * @param mixed $name
     * @param mixed $val
     * @param string $section
     * @return
     */
    public static function AcrSet($name, $val, $section = 'sys')
    {
        $key = $GLOBALS['KONFIG']->xml[$section]->xpath("//acr[@id='$name']");
        $key[0][0] = $val;
        btrack('AcrSET changed: ' . $name . '>' . $val);
    }
    /**
     * konfig::AcrGet()
     * 
     * @param mixed $name
     * @param string $section
     * @return
     */
    public static function AcrGet($name, $section = 'sys')
    {
        $key = @$GLOBALS['KONFIG']->xml[$section]->xpath("//acr[@id='$name']");
        if (isset($key[0][0])) {
            return $key[0][0];
        } else {
            if (konfig::isOn('error_konfig'))
                debugger::warn("sorry, Access Rights Key not found :  #$name!");
            return false;
        }
    }

    /**
     * Show konfig form
     * 
     * @param string $section konfig section
     * @return
     */
    public static function showkonfig($section = 'sys')
    {

		registry::set('cfg_sec',$section);
		form::sendForm(PAPP.'common'.DS.'konfig.php');	

    }
    /**
     * konfig::savecpanel()
     * 
     * @param string $section
     * @return
     */
    public static function savekonfig($section = 'sys')
    {

    }
    /**
     * Save KONFIG object to a File
     *
     * @access private
     */
    public static function xit()
    {
        $GLOBALS['KONFIG']->xml['sys']->asXML(PXML . 'konfig.xml');
    }

    /**
     * konfig::save()
     * 
     * @param mixed $section
     * @param mixed $xmlfile
     * @return
     */
    public static function save($section, $xmlfile)
    {
        $GLOBALS['KONFIG']->xml[$section]->asXML($xmlfile);
    }


}
?>