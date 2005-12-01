<?php
class S2DaoClassLoader {
    public static $CLASSES = array(
        "S2Dao_ResultSetHandler" =>
            "/org/seasar/extension/db/S2Dao_ResultSetHandler.class.php",
        "S2Dao_RelationRowCache" =>
            "/org/seasar/dao/impl/S2Dao_RelationRowCache.class.php",
        "S2Dao_BasicStatementFactory" =>
            "/org/seasar/extension/db/impl/S2Dao_BasicStatementFactory.class.php",
        "S2Dao_BasicResultSetFactory" =>
            "/org/seasar/extension/db/impl/S2Dao_BasicResultSetFactory.class.php",
        "S2Dao_BasicSelectHandler" =>
            "/org/seasar/extension/db/impl/S2Dao_BasicSelectHandler.class.php",
        "S2Dao_StatementFactory" =>
            "/org/seasar/extension/db/S2Dao_StatementFactory.class.php",
        "S2Dao_ResultSetFactory" =>
            "/org/seasar/extension/db/S2Dao_ResultSetFactory.class.php",
        "S2Dao_SelectHandler" =>
            "/org/seasar/extension/db/S2Dao_SelectHandler.class.php",
        "S2Dao_BeanArrayMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/S2Dao_BeanArrayMetaDataResultSetHandler.class.php",
        "S2Dao_BeanListMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/S2Dao_BeanListMetaDataResultSetHandler.class.php",
        "S2Dao_PrimaryKeyNotFoundRuntimeException" =>
            "/org/seasar/dao/S2Dao_PrimaryKeyNotFoundRuntimeException.class.php",
        "S2Dao_UpdateFailureRuntimeException" =>
            "/org/seasar/dao/S2Dao_UpdateFailureRuntimeException.class.php",
        "S2Dao_NotSingleRowUpdatedRuntimeException" =>
            "/org/seasar/dao/S2Dao_NotSingleRowUpdatedRuntimeException.class.php",
        "S2Dao_BasicHandler" =>
            "/org/seasar/extension/db/impl/S2Dao_BasicHandler.class.php",
        "S2Dao_UpdateHandler" =>
            "/org/seasar/extension/db/S2Dao_UpdateHandler.class.php",
        "S2Dao_AbstractAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_AbstractAutoHandler.class.php",
        "S2Dao_SqlParser" =>
            "/org/seasar/dao/S2Dao_SqlParser.class.php",
        "S2Dao_SqlParserImpl" =>
            "/org/seasar/dao/parser/S2Dao_SqlParserImpl.class.php",
        "S2Dao_SqlTokenizer" =>
            "/org/seasar/dao/S2Dao_SqlTokenizer.class.php",
        "S2Dao_SqlTokenizerImpl" =>
            "/org/seasar/dao/parser/S2Dao_SqlTokenizerImpl.class.php",
        "S2Dao_InsertAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_InsertAutoHandler.class.php",
        "S2Dao_InsertAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_InsertAutoStaticCommand.class.php",
        "S2Dao_InsertBatchAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_InsertBatchAutoHandler.class.php",
        "S2Dao_InsertBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_InsertBatchAutoStaticCommand.class.php",
        "S2Dao_UpdateAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_UpdateAutoHandler.class.php",
        "S2Dao_UpdateBatchAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_UpdateBatchAutoHandler.class.php",
        "S2Dao_UpdateBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_UpdateBatchAutoStaticCommand.class.php",
        "S2Dao_UpdateAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_UpdateAutoStaticCommand.class.php",
        "S2Dao_DeleteAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_DeleteAutoHandler.class.php",
        "S2Dao_DeleteAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_DeleteAutoStaticCommand.class.php",
        "S2Dao_DeleteBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_DeleteBatchAutoStaticCommand.class.php",
        "S2Dao_DeleteBatchAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_DeleteBatchAutoHandler.class.php",
        "S2Dao_SelectDynamicCommand" =>
            "/org/seasar/dao/impl/S2Dao_SelectDynamicCommand.class.php",
        "S2Dao_AbstractDynamicCommand" =>
            "/org/seasar/dao/impl/S2Dao_AbstractDynamicCommand.class.php",
        "S2Dao_AbstractBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_AbstractBatchAutoStaticCommand.class.php",
        "S2Dao_ObjectResultSetHandler" =>
            "/org/seasar/extension/db/impl/S2Dao_ObjectResultSetHandler.class.php",
        "S2Dao_PropertyType" =>
            "/org/seasar/extension/db/S2Dao_PropertyType.class.php",
        "S2Dao_PropertyTypeImpl" =>
            "/org/seasar/extension/db/impl/S2Dao_PropertyTypeImpl.class.php",
        "S2Dao_DatabaseMetaDataUtil" =>
            "/org/seasar/dao/util/S2Dao_DatabaseMetaDataUtil.class.php",
        "S2Dao_DataSourceUtil" =>
            "/org/seasar/dao/util/S2Dao_DataSourceUtil.class.php",
        "S2Dao_AssignedIdentifierGenerator" =>
            "/org/seasar/dao/id/S2Dao_AssignedIdentifierGenerator.class.php",
        "S2Dao_IdentifierGeneratorFactory" =>
            "/org/seasar/dao/id/S2Dao_IdentifierGeneratorFactory.class.php",
        "S2Dao_AbstractIdentifierGenerator" =>
            "/org/seasar/dao/id/S2Dao_AbstractIdentifierGenerator.class.php",
        "S2Dao_AbstractStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_AbstractStaticCommand.class.php",
        "S2Dao_AbstractSqlCommand" =>
            "/org/seasar/dao/impl/S2Dao_AbstractSqlCommand.class.php",
        "S2Dao_AbstractBeanMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/S2Dao_AbstractBeanMetaDataResultSetHandler.class.php",
        "S2Dao_AbstractBatchAutoHandler" =>
            "/org/seasar/dao/impl/S2Dao_AbstractBatchAutoHandler.class.php",
        "S2Dao_AbstractAutoStaticCommand" =>
            "/org/seasar/dao/impl/S2Dao_AbstractAutoStaticCommand.class.php",
        "S2Dao_AbstractDao" =>
            "/org/seasar/dao/impl/S2Dao_AbstractDao.class.php",
        "S2Dao_EntityManagerImpl" =>
            "/org/seasar/dao/impl/S2Dao_EntityManagerImpl.class.php",
        "S2Dao_FieldAnnotationReader" =>
            "/org/seasar/dao/impl/S2Dao_FieldAnnotationReader.class.php",
        "S2Dao_DtoMetaData" =>
            "/org/seasar/dao/S2Dao_DtoMetaData.class.php",
        "S2Dao_DtoMetaDataImpl" =>
            "/org/seasar/dao/impl/S2Dao_DtoMetaDataImpl.class.php",
        "S2Dao_DaoMetaDataFactoryImpl" =>
           "/org/seasar/dao/impl/S2Dao_DaoMetaDataFactoryImpl.class.php",
        "S2Dao_DaoMetaData" =>
            "/org/seasar/dao/S2Dao_DaoMetaData.class.php",
        "S2Dao_DaoMetaDataImpl" =>
            "/org/seasar/dao/impl/S2Dao_DaoMetaDataImpl.class.php",
        "S2Dao_DaoMetaDataFactory" =>
            "/org/seasar/dao/S2Dao_DaoMetaDataFactory.class.php",
        "S2Dao_DaoNotFoundRuntimeException" =>
            "/org/seasar/dao/S2Dao_DaoNotFoundRuntimeException.class.php",
        "S2Dao_DaoAnnotationReader" =>
            "/org/seasar/dao/S2Dao_DaoAnnotationReader.class.php",
        "S2DaoInterceptor" =>
            "/org/seasar/dao/interceptors/S2DaoInterceptor.class.php",
        "S2Dao_SqlCommand" =>
            "/org/seasar/dao/S2Dao_SqlCommand.class.php",
        "S2Dao_SqlParsar" =>
            "/org/seasar/dao/S2Dao_SqlParsar.class.php",
        "S2Dao_CommandContext" =>
            "/org/seasar/dao/S2Dao_CommandContext.class.php",
        "S2Dao_CommandContextImpl" =>
            "/org/seasar/dao/context/S2Dao_CommandContextImpl.class.php",
        "S2Dao_Dbms" =>
            "/org/seasar/dao/S2Dao_Dbms.class.php",
        "S2Dao_DbmsManager" =>
            "/org/seasar/dao/dbms/S2Dao_DbmsManager.class.php",
        "S2Dao_IllegalBoolExpressionRuntimeException" =>
            "/org/seasar/dao/S2Dao_IllegalBoolExpressionRuntimeException.class.php",
        "S2Dao_IllegalSignatureRuntimeException" =>
            "/org/seasar/dao/S2Dao_IllegalSignatureRuntimeException.class.php",
        "S2Dao_IdentifierGenerator" =>
            "/org/seasar/dao/S2Dao_IdentifierGenerator.class.php",
        "S2Dao_Node" =>
            "/org/seasar/dao/S2Dao_Node.class.php",
        "S2Dao_BeanMetaData" =>
            "/org/seasar/dao/S2Dao_BeanMetaData.class.php",
        "S2Dao_BeanMetaDataImpl" =>
            "/org/seasar/dao/impl/S2Dao_BeanMetaDataImpl.class.php",
        "S2Dao_BeanMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/S2Dao_BeanMetaDataResultSetHandler.class.php",
        "S2Dao_EndCommentNotFoundRuntimeException" =>
            "/org/seasar/dao/S2Dao_EndCommentNotFoundRuntimeException.class.php",
        "S2Dao_IfConditionNotFoundRuntimeException" =>
            "/org/seasar/dao/S2Dao_IfConditionNotFoundRuntimeException.class.php",
        "S2Dao_SqlTokenizer" =>
            "/org/seasar/dao/S2Dao_SqlTokenizer.class.php",
        "S2Dao_BeginNode" =>
            "/org/seasar/dao/node/S2Dao_BeginNode.class.php",
        "S2Dao_BindVariableNode" =>
            "/org/seasar/dao/node/S2Dao_BindVariableNode.class.php",
        "S2Dao_AbstractNode" =>
            "/org/seasar/dao/node/S2Dao_AbstractNode.class.php",
        "S2Dao_ContainerNode" =>
            "/org/seasar/dao/node/S2Dao_ContainerNode.class.php",
        "S2Dao_ElseNode" =>
            "/org/seasar/dao/node/S2Dao_ElseNode.class.php",
        "S2Dao_EmbeddedValueNode" =>
            "/org/seasar/dao/node/S2Dao_EmbeddedValueNode.class.php",
        "S2Dao_IfNode" =>
            "/org/seasar/dao/node/S2Dao_IfNode.class.php",
        "S2Dao_ParenBindVariableNode" =>
            "/org/seasar/dao/node/S2Dao_ParenBindVariableNode.class.php",
        "S2Dao_PrefixSqlNode" =>
            "/org/seasar/dao/node/S2Dao_PrefixSqlNode.class.php",
        "S2Dao_SqlNode" =>
            "/org/seasar/dao/node/S2Dao_SqlNode.class.php",
        "S2Dao_EntityManager" =>
            "/org/seasar/dao/S2Dao_EntityManager.class.php",
        "S2Dao_RelationPropertyType" =>
            "/org/seasar/dao/S2Dao_RelationPropertyType.class.php",
        "S2Dao_ArrayList" =>
            "/org/seasar/dao/util/S2Dao_ArrayList.class.php",
        "S2Dao_HashMap" =>
            "/org/seasar/dao/util/S2Dao_HashMap.class.php",
        "S2Dao_MySQL" =>
            "/org/seasar/dao/dbms/S2Dao_MySQL.class.php",
        "S2Dao_pgsql" =>
            "/org/seasar/dao/dbms/S2Dao_pgsql.class.php",
        "S2Dao_Standard" =>
            "/org/seasar/dao/dbms/S2Dao_Standard.class.php",
        "S2Dao_ColumnNotFoundRuntimeException" =>
            "/org/seasar/extension/db/S2Dao_ColumnNotFoundRuntimeException.class.php",
        "S2Dao_PDODataSource" =>
            "/org/seasar/extension/db/pdo/S2Dao_PDODataSource.class.php",
        "S2Dao_PDOSqlHandler" =>
            "/org/seasar/extension/db/pdo/S2Dao_PDOSqlHandler.class.php",
        "S2Dao_PDOTxInterceptor" =>
            "/org/seasar/extension/db/pdo/S2Dao_PDOTxInterceptor.class.php",
    );

    public static function load($className){
        if( isset(self::$CLASSES[$className]) ){
            require_once(S2DAO_PHP5 . self::$CLASSES[$className]);
            return true;
        } else {
            return false;
        }
    }
    
    public static function export(){
        $export = array();
        foreach(self::$CLASSES as $key => $value){
            $export[$key] = S2DAO_PHP5 . $value;
        }
        return $export;
    }
}
?>
