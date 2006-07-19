<?php

interface EmployeePagerDao
{
    const BEAN = "Employee2";
    
    public function getCount();
    public function getAllEmployeesList();
    public function getAllEmployeesArray();

    public function getAllByPagerConditionList(S2Dao_PagerCondition $dto);
    public function getAllByPagerConditionArray(S2Dao_PagerCondition $dto);
    
    /**
     * @return json
     */
    public function getAllByPagerConditionJson(S2Dao_PagerCondition $dto);
    /**
     * @return yaml
     */
    public function getAllByPagerConditionYaml(S2Dao_PagerCondition $dto);
}

?>