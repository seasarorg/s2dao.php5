<?php
interface Employee8Dao {

    const BEAN = "Employee2";

    const getEmployeesList_QUERY = "/*BEGIN*/ WHERE 
            /*IF dto.ename != null*/ ename = /*dto.ename*/'aaa'/*END*/
            /*IF dto.job != null*/ AND job = /*dto.job*/'bbb'/*END*/
            /*END*/";

    public function getEmployeesList(Employee2 $employee);

}

?>