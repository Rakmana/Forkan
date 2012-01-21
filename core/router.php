<?php
/**
 * WAPL Router Request Greeter & dispatcher Class
 * @package WAPL
 * @author     Khedrane Jnom <Jnom23@gmail.com>
 * @copyright (C) 2003 - 2011 . itkane.com
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 * @version $Id: router.php,v2.0 19:06 12/03/2011 NP++ Exp $
 */


/**
 * ROUTER
 * WAPL Request Routing class 
 * Require Registry.cls + Debugger.cls
 *
 * @package  WAPL
 */


class router
{
    private $path;
    /**
     * Initialize Router Parse request uri & extract all parts. 
     *
     * @access public
     */
    function init()
    {
        router::parse();
        router::getModeName();
        router::getAppName();
        router::getJobName();
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Parse uri string and split theme into $Global[uri] e.g: [md=site] & [ap=fpage] & [jb=index]
     *
     * @return mixed  referance to $Global[uri]  
     */
    function parse()
    {
        $uri = $_SERVER["QUERY_STRING"];
        $parts = explode('&', $uri);
        foreach ($parts as $key => $value) {

            $lmts = explode('=', $value);
            $data = isset($lmts[1]) ? $lmts[1] : $lmts[0];
            $GLOBALS['uri'][$key][$lmts[0]] = $data;
        }
        $ret = &$GLOBALS['uri'];
        //  index.php?[md=site] & [ap=fpage] & [jb=index]
        //             [0][md]    [0][ap]
        //tools::dump($GLOBALS['uri']);

        return $ret;

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Get mode name if isValid else notify & set default to 'Site', then set into registry and defines
     *
     * @access public
     * @return string  Mode Name  
     */
    function getModeName()
    {
        $mode = (!empty($GLOBALS['uri'][0])) ? (!empty($GLOBALS['uri'][0]['md'])) ? $GLOBALS['uri'][0]['md'] :
            'site' : 'site';

        $file = KROT . $mode . '.mode.php';
        if (flib::is_file($file) == false) {
            debugger::alert('Mode not valid:  ' . $mode . ':: !! ');
            //reset 2 index
            $mode = 'site';
        }
        ;
        registry::set('mode', $mode);
        define('MODC', $mode); //current running mode name
        btrack('MOD-selected: ' . $mode);
        return $mode;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Get Application Name if isValid else notify & set default to 'fpage', then set into registry and defines
     *
     * @access public
     * @return string  Application Name  
     */
    function getAppName()
    {
        $app = (!empty($GLOBALS['uri'][1])) ? (!empty($GLOBALS['uri'][1]['ap'])) ? $GLOBALS['uri'][1]['ap'] :
            'forkan' : 'forkan';

        if (isset($_GET['appr']) && router::ismediax())
            //if send it from media file in mediax mode

            $app = $_GET['appr'];

        $file = PAPP . $app . DS . $app . '.php'; // e.g: applications/appname/appname.php

        if (flib::is_file($file) == false) {
            debugger::alert('Application not valid:  ' . MODC . '::' . $app . ' !! ');
            //reset 2 index
            $app = 'forkan';
        }
        ;

        registry::set('app', $app);
        define('PCAP', PAPP . $app . DS); //current running application path
        define('APPC', $app); //current running application name
        btrack('APP-selected: ' . $app);
        return $app;

    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Get Job Name if isValid else notify & set default to 'index', then set into registry and defines
     *
     * @access public
     * @return string  Job Name  
     */
    function getJobName()
    {
        $job = (!empty($GLOBALS['uri'][2])) ? (!empty($GLOBALS['uri'][2]['jb'])) ? $GLOBALS['uri'][2]['jb'] :
            'index' : 'index';

        //TODO: This will be more silent...
        $file = PCAP . APPC . '.php';
        loader::import($file);
        $app = kore::getInstance(APPC);

        if (!method_exists($app, $job)) {
            debugger::warn('Job not valid:  ' . MODC . '::' . APPC . '::' . $job . ' !! ');
            //reset 2 index
            $job = 'index';
        }
        ;

        registry::set('job', $job);
        define('JOBC', $job); //current running job name

        btrack('JOB-selected: ' . $job);
        return $job;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Switch To Current Mode by including its mode file
     *
     * @access public
     * @return string  Mode Name  
     */
    function SwitchToMode()
    {
        $mode = registry::get('mode');
        btrack('MOD-Loading: ' . $mode);
        loader::import(KROT . $mode . '.mode.php');
        //btrack('MODE: '.$mode);

        /**
         * @ToDo: Will add exception if failed to load file return false
         */
        return $mode;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Include App file, create app Instance Initialize it then return a referance to it
     *
     * @access public
     * @return mixed  App Instance  
     */
    function getApp()
    {
        $app = registry::get('app');

        $file = PAPP . $app . DS . $app . '.php';
        loader::import($file);

        btrack('APP-Loading: ' . $app);

        /*$kon_cn = 'kon_'.$app;*/
        $appcls = kore::getInstance($app);

        //load application konfig file
        applications::getAppConfig($app);

        $appcls->init();
        $appcls->initView();


        return $appcls;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Get Job name
     *
     * @access public
     * @return string Job Name
     */
    function getJob()
    {
        $job = registry::get('job');

        btrack('JOB-Loading: ' . $job);
        return $job;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Check if Ajax Mode
     *
     * @access public
     * @return bool 
     */
    function isajx()
    {
        $mode = registry::get('mode');
        $ret = ($mode != 'kjax') ? false : true;
        return $ret;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Check if Mediax Mode
     *
     * @access public
     * @return bool 
     */
    function ismediax()
    {
        $mode = registry::get('mode');
        $ret = ($mode != 'mediax') ? false : true;
        return $ret;
    }
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    /**
     * Check if Site Mode
     *
     * @access public
     * @return bool 
     */
    function issite()
    {
        $mode = registry::get('mode');
        $ret = ($mode != 'site') ? false : true;
        return $ret;
    }
}

?>