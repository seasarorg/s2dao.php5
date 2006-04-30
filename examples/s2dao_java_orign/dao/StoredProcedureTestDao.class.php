<?php

interface StoredProcedureTestDao {
	const BEAN = "Employee";
	const getSalesTax_PROCEDURE = "SALES_TAX";
    /**
     * @return double
     */
	public function getSalesTax($subtotal);
	
    const getSalesTax2_PROCEDURE = "SALES_TAX2";
    /**
     * @return double
     */
	public function getSalesTax2($subtotal);
    
	const getSalesTax3_PROCEDURE = "SALES_TAX3";
    /**
     * @return double
     */
	public function getSalesTax3($subtotal);
    
	const getSalesTax4_PROCEDURE = "SALES_TAX4";
    /**
     * @return map
     */    
	public function getSalesTax4($subtotal);
}
?>