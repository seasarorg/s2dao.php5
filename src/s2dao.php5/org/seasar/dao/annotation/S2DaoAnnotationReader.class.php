<?php

/**
 * @author nowel
 */
class S2DaoAnnotationReader implements S2Container_AnnotationReader {
    
    const ARGS_TYPE_ARRAY = S2Container_AnnotationFactory::ARGS_TYPE_ARRAY;
    const ARGS_TYPE_HASH = S2Container_AnnotationFactory::ARGS_TYPE_HASH;

    public function getAnnotations(ReflectionClass $clazz,
                                   $methodName){
                                    
        if(is_string($methodName)){
            $clazz = $clazz->getMethod($methodName);
        }
        
        $comments = preg_split('/\r?\n/',
                               $clazz->getDocComment(), -1, PREG_SPLIT_NO_EMPTY);
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

        if(count($annoLines) != 0){
            $annoObj = $this->getAnnotationObject($annoLines);
            if(is_object($annoObj)){
                $annoObjects[get_class($annoObj)] = $annoObj;
            }
        }

        if(count($annoObjects) > 0){
            return $annoObjects;
        }
        return null;
    }

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
            return S2Container_AnnotationFactory::create($annotationType,
                                                         $args,
                                                         $argType);
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