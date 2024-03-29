<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.s2dao.annotation
 */
class S2DaoAnnotationReader implements S2Container_AnnotationReader {
    
    const ARGS_TYPE_ARRAY = S2Container_AnnotationFactory::ARGS_TYPE_ARRAY;
    const ARGS_TYPE_HASH = S2Container_AnnotationFactory::ARGS_TYPE_HASH;

    private function isReflectorCall(array $reflectors, $callName){
        foreach($reflectors as $reflector){
            if($reflector->getName() == $callName){
                return true;
            }
        }
        return false;
    }
    
    public function getAnnotations(ReflectionClass $clazz, $callName){
        if($this->isReflectorCall($clazz->getMethods(), $callName)){
            $clazz = $clazz->getMethod($callName);
        } else if($this->isReflectorCall($clazz->getProperties(), $callName)){
            $clazz = $clazz->getProperty($callName);
        }
        
        $comments = preg_split('/\r?\n/', $clazz->getDocComment(), -1, PREG_SPLIT_NO_EMPTY);
        $inAnno = false;
        $annoLines = array();
        $annoObjects = array();
        foreach($comments as $line){
            $line = $this->removeCommentSlashAster($line);
            if(preg_match('/^@\w+$/', $line) || preg_match('/^@\w+\s*\(/',$line)){
                $inAnno = true;
                if(count($annoLines) != 0){
                    $annoObj = $this->getAnnotationObject($annoLines);
                    if(is_object($annoObj)){
                        $annoObjects[get_class($annoObj)] = $annoObj;
                    }
                    $annoLines = array();
                }
            }
            if($inAnno){
                $annoLines[] = $line;
            }
        }

        if(0 != count($annoLines)){
            $annoObj = $this->getAnnotationObject($annoLines);
            if(is_object($annoObj)){
                $annoObjects[get_class($annoObj)] = $annoObj;
            }
        }

        if(0 < count($annoObjects)){
            return $annoObjects;
        }
        return null;
    }

    /**
     * @throws S2Container_AnnotationRuntimeException
     */
    private function getAnnotationObject(array $annoLines){
        if(preg_match('/^@(\w+)$/', $annoLines[0], $matches)){
            return S2Container_AnnotationFactory::create($matches[1]);
        }
        
        if(!preg_match('/^@\w+\s*\(/', $annoLines[0])){
            return null;
        }
        
        $line = implode(' ', $annoLines);
        if(preg_match('/^@(\w+)\s*\((.*)\)/', trim($line), $matches)){
            if(empty($matches[2])){
                return S2Container_AnnotationFactory::create($matches[1]);
            }
            
            $annotationType = $matches[1];
            $argType = null;
            $args = array();
            
            //FIXME
            if(preg_match('/^\"(.*)\"(.+)?/s', $matches[2], $match)){
                $args['value'] = trim($match[1]);
                if(isset($match[2])){
                    if(preg_match("/,(.+?)=(.+)/s", $match[2], $m)){
                        $key = $this->removeQuote($m[1]);
                        $value = $this->removeQuote($m[2]);
                        $args[$key] = $value;
                    }
                }
                $argType = self::ARGS_TYPE_HASH;
            } else {
                $items = S2Dao_ArrayUtil::spacetrim(explode(',', $matches[2]));
                foreach ($items as $item) {
                    if (preg_match('/^(.+?)=(.+)/s', $item, $matches)) {
                        if ($argType == self::ARGS_TYPE_ARRAY) {
                            throw new S2Container_AnnotationRuntimeException('ERR003',
                                                        array($line, $item));
                        }
                        $key = $this->removeQuote($matches[1]);
                        $val = $this->removeQuote($matches[2]);
                    
                        if (empty($key)) {
                            throw new S2Container_AnnotationRuntimeException('ERR004',
                                                               array($line,$item));
                        }
                        $args[$key] = $val;
                        $argType = self::ARGS_TYPE_HASH;
                    } else {
                        if ($argType == self::ARGS_TYPE_HASH) {
                            throw new S2Container_AnnotationRuntimeException('ERR003',
                                                        array($line, $item));
                        }
                        $item = $this->removeQuote($item);
                        $args[] = $item;
                        $argType = self::ARGS_TYPE_ARRAY;
                    }
                }
            }
            return S2Container_AnnotationFactory::create($annotationType, $args, $argType);
        }
    }

    private function removeQuote($str) {
        $str = trim($str);
        $str = preg_replace('/^[\"\']/', '', $str);
        $str = preg_replace('/[\"\']$/', '', $str);
        return trim($str);
    }

    private function removeCommentSlashAster($line){
        $line = trim($line);
        $line = preg_replace('/^\/\*\*/', '', $line);
        $line = preg_replace('/\*\/$/', '', $line);
        $line = preg_replace('/^\*/', '', $line);
        return trim($line);
    }
}

?>