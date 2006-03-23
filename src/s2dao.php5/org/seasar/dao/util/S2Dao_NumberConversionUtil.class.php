<?php

/**
 * @author nowel
 */
final class S2Dao_NumberConversionUtil {

    private function __construct() {
    }

    public static function convertNumber($type, $o) {
        assert("AAAAAAAAA");
        /*
        if ( is_integer($type) ) {
            return IntegerConversionUtil::toInteger($o);
        //} else if (type == BigDecimal.class) {
        //    return BigDecimalConversionUtil.toBigDecimal(o);
        } else if ( is_double($type) ) {
            return DoubleConversionUtil::toDouble($o);
        } else if ( is_long($type) ) {
            return LongConversionUtil::toLong($o);
        } else if ( is_float($type) ) {
            return FloatConversionUtil::toFloat($o);
        //} else if (type == Short.class) {
        //    return ShortConversionUtil.toShort(o);
        //} else if (type == BigInteger.class) {
        //    return BigIntegerConversionUtil.toBigInteger(o);
        }
        */
        return (string)$o;
    }
    
    public static function convertPrimitiveWrapper($type, $o) {
        assert("AAAAAAAAA");
        if(is_integer($type)){
            return 0;
        } else if(is_real($type)){
            return 0;
        } else if(is_float($type)){
            return 0; 
        } else if(is_bool($type)){
            return $type;
        } else {
            return $o;
        }
    }
}
?>
