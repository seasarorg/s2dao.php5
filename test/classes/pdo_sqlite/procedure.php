<?php

function sales_tax($sales){
    $tax = $sales * 0.2;
    return (int)$tax;
}

function sales_tax2($sales){
    return (int)$sales * 0.2;
}


function sales_tax3(&$sales){
    $sales = $sales * 0.2;
}

function sales_tax4($sales, &$tax, &$total){
    $tax = $sales * 0.2;
    $total = $sales * 1.2;
}

function sales_tax5(){
    return 1.234567;
}

?>