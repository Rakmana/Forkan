<?php
/**
 * WAPL API interface
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . itkane.com
 * @link    http://www.itkane.com
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: api.php,v2.0 19:06 12/03/2011 NP++ Exp $
 */


/**
 * API
 * WAPL API interface
 * Require *
 *
 * @package Wapl
 * @author Jnom23
 * @version $Id$
 * @access public
 */
class WPI
{
    /**
     * Add point to profiler track
     *
     * @param string $msg
     * @param bool $show
     * @return
     */
    public static function track($msg = "TRACKER", $show = false)
    {
        return btrack($msg, $show);
    }
    /*------------------------------------------[   Registry    ]----------------------------------------------*/
    /**
     * Get key value from registry
     *
     * @param mixed $key
     * @param bool $ref Get key referance
     * @return mixed Value or null
     */
    public static function getVar($key, $ref = false)
    {
        return registry::get($key, $ref);
    }
    /**
     * Set key value to registry
     *
     * @param mixed $key
     * @param mixed $var
     * @return
     */
    public static function setVar($key, $var)
    {
        return registry::set($key, $var);
    }
    /**
     * Add key with to registry
     *
     * @param mixed $key
     * @param mixed $var
     * @return
     */
    public static function addVar($key, $var)
    {
        return registry::add($key, $var);
    }

    /*------------------------------------------[   Router    ]----------------------------------------------*/
    /**
     * Switch to Request Mode
     *
     * @return string Mode name
     */
    public static function SwitchToMode()
    {
        return router::SwitchToMode();
    }
    /**
     * Check if Mode is Ajax
     *
     * @return bool
     */
    public static function ModeIsAjx()
    {
        return router::isajx();
    }
    /**
     * Check if Mode is Mediax
     *
     * @return bool
     */
    public static function ModeIsMediax()
    {
        return router::ismediax();
    }
    /**
     * Check if Mode is Site
     *
     * @return bool
     */
    public static function ModeIsSite()
    {
        return router::issite();
    }

    /*------------------------------------------[   Langue    ]----------------------------------------------*/
    /**
     * Get Translated string from language Engine
     *
     * @param string $str
     * @return string
     */
    public static function txt($str,$id='wpl')
    {
        return tongue::txt($str,$id);
    }

    /*------------------------------------------[   Mediax    ]----------------------------------------------*/
    /**
     * Print All needed js code into template html
     *
     * @return mixed
     */
    public static function BootJS()
    {
        echo mediax::BootJS();
    }
    public static function addToJS($js){
         return registry::add('js',$js);
    }
    public static function addToJSH($jsh){
         return registry::add('jsh',$jsh);
    }
    public static function addToALJS($aljs){
         return registry::add('aljs',$aljs);
    }
    public static function getLoadingDiv(){
        return form::getLoadingDiv();
    }
    
    /*------------------------------------------[   user    ]----------------------------------------------*/
    public static function isloggedin(){
        return user::jlogged();
    }
    
}
?>