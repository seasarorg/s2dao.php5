<?php

interface StoredProcedureTestDao {
	const BEAN = "Employee";

	//const getSalesTax_PROCEDURE = "SALES_TAX";
	//public function getSalesTax($subtotal);
    
    const getSalesTax2_PROCEDURE = "SALES_TAX2";
	public function getSalesTax2($subtotal);
    
	//const getSalesTax3_PROCEDURE = "SALES_TAX3";
	//public function getSalesTax3($subtotal);
    
	//const getSalesTax4Map_PROCEDURE = "SALES_TAX4";
	//public function getSalesTax4Map($subtotal);
}

// SQLite Stored function:PHP Function
function SALES_TAX2($sales){
    return $sales * 0.2;
}
// SQLite Stored function

?>