<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author nowel
 */
final class S2DaoVersion {
    
    const Version = '1.2.0';
    const DownwardVersion = '1.2.0';
    const ReleaseVersion = 'beta';
    const FullVersion = 'S2Dao.PHP5-1.2.0-beta1';
    const RequiredPDOVersion = '1.0.3';
    
    public static function getVersion(){
        return self::Version;
    }
    
    public static function getFullVersion(){
        return self::FullVersion;
    }
    
    public static function getRequiredPdoVersion(){
        return self::RequiredPDOVersion;
    }
    
    public static function compatibleTo($s2daoVersion){
        return version_compare(self::DownwardVersion, $s2daoVersion, '>=');
    }
    
    /*
    public static function compatibleS2Container(S2ContainerVersion $container){
    }
    */
    
    public static function compatiblePDO(){
        $ref = new ReflectionExtension('PDO');
        return version_compare(self::RequiredPDOVersion, $ref->getVersion(), '>=');
    }
    
    public static function compatibleWith(S2DaoVersion $version){
        if(self::compatibleTo($version->getVersion())){
            return false;
        }
        if(self::compatiblePDO()){
            return false;
        }
        return true;
    }
    
    public static function requiresExtention(){
        return array('PDO');
    }
    
    public static function requiresOptExtension(){
        return array('json', 'Spyc');
    }
    
}

?>