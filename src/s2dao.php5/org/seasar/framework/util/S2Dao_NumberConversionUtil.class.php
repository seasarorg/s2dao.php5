<?php

/**
 * @author nowel
 */
final class S2Dao_NumberConversionUtil {

	private function __construct() {
	}

	public static function convertNumber($type, $o) {
		/*
        if ( is_integer($type) ) {
			return IntegerConversionUtil::toInteger($o);
		//} else if (type == BigDecimal.class) {
		//	return BigDecimalConversionUtil.toBigDecimal(o);
		} else if ( is_double($type) ) {
			return DoubleConversionUtil::toDouble($o);
		} else if ( is_long($type) ) {
			return LongConversionUtil::toLong($o);
		} else if ( is_float($type) ) {
			return FloatConversionUtil::toFloat($o);
		//} else if (type == Short.class) {
		//	return ShortConversionUtil.toShort(o);
		//} else if (type == BigInteger.class) {
		//	return BigIntegerConversionUtil.toBigInteger(o);
		}
        */
		return (string)$o;
	}
	
	public static function convertPrimitiveWrapper($type, $o) {
        if( is_integer($type) ){
            return 0;
        } else if( is_real($type) ){
            return 0;
        } else if( is_bool($type) ){
            return $type;
        } else {
            return $o;
        }
        /*
		if (type == int.class) {
			Integer i = IntegerConversionUtil.toInteger(o); 
			if (i != null) {
				return i;
			}
			return new Integer(0);
		} else if (type == double.class) {
			Double d = DoubleConversionUtil.toDouble(o);
			if (d != null) {
				return d;
			}
			return new Double(0);
		} else if (type == long.class) {
			Long l = LongConversionUtil.toLong(o);
			if (l != null) {
				return l;
			}
			return new Long(0);
		} else if (type == float.class) {
			Float f = FloatConversionUtil.toFloat(o);
			if (f != null) {
				return f;
			}
			return new Float(0);
		} else if (type == short.class) {
			Short s = ShortConversionUtil.toShort(o);
			if (s != null) {
				return s;
			}
			return new Short((short) 0);
		} else if (type == boolean.class) {
			Boolean b = BooleanConversionUtil.toBoolean(o);
			if (b != null) {
				return b;
			}
			return Boolean.FALSE;
		}
		return o;
        */
	}
}
?>
