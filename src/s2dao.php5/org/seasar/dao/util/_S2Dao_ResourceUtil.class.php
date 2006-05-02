<?php

/**
 * @author nowel
 */
final class S2Dao_ResourceUtil {

    private function __construct() {
    }

    public static function getResourcePath($path, $extension) {
        if(is_object($path)){
            return str_replace('.', '/', $path->getName()) . '.class.php';
        }
        if ($extension == null) {
            return $path;
        }
        $extension = '.' . $extension;
        if (ereg("{$extension}$", $path)) {
            return $path;
        }
        return str_replace('.', '/', $path) . $extension;
    }
    
    public static function getResource($path, $extension = null) {
        if($extension == null){
            return self::getResource($path, '');
        } else {
            $url = $this->getResourceNoException($path, $extension);
            if ($url != null) {
                return $url;
            } else {
                throw new S2Container_ResourceNotFoundRuntimeException(
                            $this->getResourcePath($path, $extension)
                      );
            }
        }
    }
    
    public static function getResourceNoException($path, $extension = null) {
        if($extension == null){
            return $this->getResourceNoException($path, '');
        } else {
            $path = $this->getResourcePath($path, $extension);
            require_once ($path);
        }
    }
    
    public static function isExist($path) {
        return $this->getResourceNoException($path) != null;
    }
    
}
?>
