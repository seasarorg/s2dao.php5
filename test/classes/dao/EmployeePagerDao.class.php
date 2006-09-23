<?php

interface EmployeePagerDao
{
    const BEAN = "Employee2";
    
    public function getCount();
    public function getAllEmployeesList();
    public function getAllEmployeesArray();

    public function getAllByPagerConditionList(S2Dao_PagerCondition $dto);
    public function getAllByPagerConditionArray(S2Dao_PagerCondition $dto);
    public function getAllByPagerConditionJson(S2Dao_PagerCondition $dto);
    public function getAllByPagerConditionYaml(S2Dao_PagerCondition $dto);
    
    /**
     * @return list
     */
    public function getAllByPagerConditionListComment(S2Dao_PagerCondition $dto);
    
    /**
     * @return array
     */
    public function getAllByPagerConditionArrayComment(S2Dao_PagerCondition $dto);

    /**
     * @return json
     */
    public function getAllByPagerConditionJsonComment(S2Dao_PagerCondition $dto);

    /**
     * @return yaml
     */
    public function getAllByPagerConditionYamlComment(S2Dao_PagerCondition $dto);
}

?>