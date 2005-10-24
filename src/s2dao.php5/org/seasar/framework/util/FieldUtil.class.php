<?php

/**
 * @author Yusuke Hata
 */
 final class FieldUtil {

    private function FieldUtil() {
    }

    public static function get($field, $target){
        try {
            var_dump($field);
            //return new ReflectionClass($field);
            //return $field->get($target);
        } catch (IllegalAccessException $ex) {
            throw new IllegalAccessRuntimeException(
                $field->getDeclaringClass(),
                $ex);
        }
    }

    /*
    public static int getInt(Field field, Object target)
        throws IllegalAccessRuntimeException {

        try {
            return field.getInt(target);
        } catch (IllegalAccessException ex) {
            throw new IllegalAccessRuntimeException(
                field.getDeclaringClass(),
                ex);
        }

    }
    */

    public static function set($field, $target, $value){
        try {
            $field->set($target, $value);
        } catch (IllegalAccessException $ex) {
            throw new IllegalAccessRuntimeException(
                $field->getDeclaringClass(),
                $ex);
        }
    }
}
?>
