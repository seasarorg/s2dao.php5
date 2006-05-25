<?php
  
/**
 * SelectDynamicCommandLimitOffsetWrapper‚ð¶¬‚·‚éƒNƒ‰ƒX
 * @author yonekawa
 */
class S2Dao_SelectDynamicCommandLimitOffsetWrapperFactory
{
    public static function create(S2Dao_SelectDynamicCommand $selectDynamicCommand)
    {
        return 
            new S2Dao_SelectDynamicCommandLimitOffsetWrapper($selectDynamicCommand);
    }
}

?>