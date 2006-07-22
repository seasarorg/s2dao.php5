<?php

interface SampleProcedureDao {
    const BEAN = "SampleProcedureBean";

    const getSalesTax_PROCEDURE = "SALES_TAX";
    public function getSalesTax($subtotal);
    
    const getSalesTax2_PROCEDURE = "SALES_TAX2";
    public function getSalesTax2($subtotal);
    
    const getSalesTax3_PROCEDURE = "SALES_TAX3";
    public function getSalesTax3($subtotal);
    
    const getSalesTax4Map_PROCEDURE = "SALES_TAX4";
    public function getSalesTax4Map($sales, &$tax, &$total);
}

?>