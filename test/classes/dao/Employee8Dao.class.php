<?php
interface Employee8Dao {

    const BEAN = "Employee8";

    const getEmployeesList_QUERY = "/*BEGIN*/
            /*IF dto.ename != null*/ AND ename = /*dto.ename*/'aaa'/*END*/
            /*IF dto.job != null*/ AND job = /*dto.job*/'bbb'/*END*/
            /*END*/";

    public function getEmployeesList(Employee8 $employee);

}

?>