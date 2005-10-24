<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.util
 * @author klove
 */
class MessageUtil {

    private static $msgMap_ = null;
    
    private function MessageUtil() {
    }
    
    /**
     * @param string message id code
     * @params array message words
     */
    public static function getMessageWithArgs($code,$args){
        if(MessageUtil::$msgMap_ == null){
            MessageUtil::loadMsgFile();
        }
        if(!is_array($args)){
            return "$args not array.\n";
        }
        if(!is_string($code)){
            return "$code not string.\n";
        }
        
        if(!array_key_exists($code,MessageUtil::$msgMap_)){
            return "$code not found in " . S2CONTAINER_PHP5_MESSAGES_INI . "\n.";
        }
        $msg = MessageUtil::$msgMap_[$code];

        $msg = preg_replace('/{/','{$args[',$msg);
        $msg = preg_replace('/}/',']}',$msg);
        $msg = EvalUtil::getExpression('"'.$msg.'"');

        return eval($msg);
    }

    private static function loadMsgFile(){
        if(is_readable(S2CONTAINER_PHP5_MESSAGES_INI)){
            MessageUtil::$msgMap_ = parse_ini_file(S2CONTAINER_PHP5_MESSAGES_INI);
        }else{
            print "[ERROR] S2CONTAINER_PHP5_MESSAGES_INI file not found.\n";
        }
    }    
}
?>