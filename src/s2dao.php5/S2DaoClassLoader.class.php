<?php
class S2DaoClassLoader {
    public static $CLASSES = array(
        "ConnectionUtil" =>
            "/org/seasar/framework/util/ConnectionUtil.class.php",
        "RelationRowCache" =>
            "/org/seasar/dao/impl/RelationRowCache.class.php",
        "BasicResultSetFactory" =>
            "/org/seasar/extension/db/impl/BasicResultSetFactory.class.php",
        "BasicSelectHandler" =>
            "/org/seasar/extension/db/impl/BasicSelectHandler.class.php",
        "SelectHandler" =>
            "/org/seasar/extension/db/SelectHandler.class.php",
        "BasicStatementFactory" =>
            "/org/seasar/extension/db/impl/BasicStatementFactory.class.php",
        "BeanArrayMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/BeanArrayMetaDataResultSetHandler.class.php",
        "BeanListMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/BeanListMetaDataResultSetHandler.class.php",
        "PrimaryKeyNotFoundRuntimeException" =>
            "/org/seasar/dao/PrimaryKeyNotFoundRuntimeException.class.php",
        "UpdateFailureRuntimeException" =>
            "/org/seasar/dao/UpdateFailureRuntimeException.class.php",
        "NotSingleRowUpdatedRuntimeException" =>
            "/org/seasar/dao/NotSingleRowUpdatedRuntimeException.class.php",
        "BasicHandler" =>
            "/org/seasar/extension/db/impl/BasicHandler.class.php",
        "BasicBatchHandler" =>
            "/org/seasar/extension/db/impl/BasicBatchHandler.class.php",
        "UpdateHandler" =>
            "/org/seasar/extension/db/UpdateHandler.class.php",
        "AbstractAutoHandler" =>
            "/org/seasar/dao/impl/AbstractAutoHandler.class.php",
        "SqlParser" =>
            "/org/seasar/dao/SqlParser.class.php",
        "SqlParserImpl" =>
            "/org/seasar/dao/parser/SqlParserImpl.class.php",
        "SqlTokenizer" =>
            "/org/seasar/dao/SqlTokenizer.class.php",
        "SqlTokenizerImpl" =>
            "/org/seasar/dao/parser/SqlTokenizerImpl.class.php",
        "InsertAutoHandler" =>
            "/org/seasar/dao/impl/InsertAutoHandler.class.php",
        "InsertAutoStaticCommand" =>
            "/org/seasar/dao/impl/InsertAutoStaticCommand.class.php",
        "InsertBatchAutoHandler" =>
            "/org/seasar/dao/impl/InsertBatchAutoHandler.class.php",
        "InsertBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/InsertBatchAutoStaticCommand.class.php",
        "UpdateAutoHandler" =>
            "/org/seasar/dao/impl/UpdateAutoHandler.class.php",
        "UpdateBatchAutoHandler" =>
            "/org/seasar/dao/impl/UpdateBatchAutoHandler.class.php",
        "UpdateBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/UpdateBatchAutoStaticCommand.class.php",
        "UpdateAutoStaticCommand" =>
            "/org/seasar/dao/impl/UpdateAutoStaticCommand.class.php",
        "DeleteAutoHandler",
            "/org/seasar/dao/impl/DeleteAutoHandler.class.php",
        "DeleteAutoStaticCommand" =>
            "/org/seasar/dao/impl/DeleteAutoStaticCommand.class.php",
        "DeleteBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/DeleteBatchAutoStaticCommand.class.php",
        "DeleteBatchAutoHandler" =>
            "/org/seasar/dao/impl/DeleteBatchAutoHandler.class.php",
        "SelectDynamicCommand" =>
            "/org/seasar/dao/impl/SelectDynamicCommand.class.php",
        "AbstractDynamicCommand" =>
            "/org/seasar/dao/impl/AbstractDynamicCommand.class.php",
        "AbstractBatchAutoStaticCommand" =>
            "/org/seasar/dao/impl/AbstractBatchAutoStaticCommand.class.php",
        "ObjectResultSetHandler" =>
            "/org/seasar/extension/db/impl/ObjectResultSetHandler.class.php",
        "PropertyType" =>
            "/org/seasar/extension/db/PropertyType.class.php",
        "PropertyTypeImpl" =>
            "/org/seasar/extension/db/impl/PropertyTypeImpl.class.php",
        "DatabaseMetaDataUtil" =>
            "/org/seasar/framework/util/DatabaseMetaDataUtil.class.php",
        "DataSourceUtil" =>
            "/org/seasar/framework/util/DataSourceUtil.class.php",
        "AssignedIdentifierGenerator" =>
            "/org/seasar/dao/id/AssignedIdentifierGenerator.class.php",
        "IdentifierGeneratorFactory" =>
            "/org/seasar/dao/id/IdentifierGeneratorFactory.class.php",
        "AbstractIdentifierGenerator" =>
            "/org/seasar/dao/id/AbstractIdentifierGenerator.class.php",
        "AbstractStaticCommand" =>
            "/org/seasar/dao/impl/AbstractStaticCommand.class.php",
        "AbstractSqlCommand" =>
            "/org/seasar/dao/impl/AbstractSqlCommand.class.php",
        "AbstractBeanMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/AbstractBeanMetaDataResultSetHandler.class.php",
        "AbstractBatchAutoHandler" =>
            "/org/seasar/dao/impl/AbstractBatchAutoHandler.class.php",
        "AbstractAutoStaticCommand" =>
            "/org/seasar/dao/impl/AbstractAutoStaticCommand.class.php",
        "FieldAnnotationReader" =>
            "/org/seasar/dao/impl/FieldAnnotationReader.class.php",
        "DtoMetaData" =>
            "/org/seasar/dao/DtoMetaData.class.php",
        "DtoMetaDataImpl" =>
            "/org/seasar/dao/impl/DtoMetaDataImpl.class.php",
        "DaoMetaDataFactoryImpl" =>
           "/org/seasar/dao/impl/DaoMetaDataFactoryImpl.class.php",
    	"DaoMetaData" =>
            "/org/seasar/dao/DaoMetaData.class.php",
        "DaoMetaDataImpl" =>
            "/org/seasar/dao/impl/DaoMetaDataImpl.class.php",
    	"DaoMetaDataFactory" =>
            "/org/seasar/dao/DaoMetaDataFactory.class.php",
    	"DaoNotFoundRuntimeException" =>
            "/org/seasar/dao/DaoNotFoundRuntimeException.class.php",
        "DaoAnnotationReader" =>
            "/org/seasar/dao/DaoAnnotationReader.class.php",
        "S2DaoInterceptor" =>
            "/org/seasar/dao/interceptors/S2DaoInterceptor.class.php",
        "SqlCommand" =>
            "/org/seasar/dao/SqlCommand.class.php",
        "SqlParsar" =>
            "/org/seasar/dao/SqlParsar.class.php",
        "CommandContext" =>
            "/org/seasar/dao/CommandContext.class.php",
        "CommandContextImpl" =>
            "/org/seasar/dao/context/CommandContextImpl.class.php",
        "Dbms" =>
            "/org/seasar/dao/Dbms.class.php",
        "DbmsManager" =>
            "/org/seasar/dao/dbms/DbmsManager.class.php",
        "IllegalBoolExpressionRuntimeException" =>
            "/org/seasar/dao/IllegalBoolExpressionRuntimeException.class.php",
        "IllegalSignatureRuntimeException" =>
            "/org/seasar/dao/IllegalSignatureRuntimeException.class.php",
        "IdentifierGenerator" =>
            "/org/seasar/dao/IdentifierGenerator.class.php",
        "Node" =>
            "/org/seasar/dao/Node.class.php",
        "BeanMetaData" =>
            "/org/seasar/dao/BeanMetaData.class.php",
        "BeanMetaDataImpl" =>
            "/org/seasar/dao/impl/BeanMetaDataImpl.class.php",
        "BeanMetaDataResultSetHandler" =>
            "/org/seasar/dao/impl/BeanMetaDataResultSetHandler.class.php",
        "EndCommentNotFoundRuntimeException" =>
            "/org/seasar/dao/EndCommentNotFoundRuntimeException.class.php",
        "IfConditionNotFoundRuntimeException" =>
            "/org/seasar/dao/IfConditionNotFoundRuntimeException.class.php",
        "SqlTokenizer" =>
            "/org/seasar/dao/SqlTokenizer.class.php",
        "BeginNode" =>
            "/org/seasar/dao/node/BeginNode.class.php",
        "BindVariableNode" =>
            "/org/seasar/dao/node/BindVariableNode.class.php",
        "AbstractNode" =>
            "/org/seasar/dao/node/AbstractNode.class.php",
        "ContainerNode" =>
            "/org/seasar/dao/node/ContainerNode.class.php",
        "ElseNode" =>
            "/org/seasar/dao/node/ElseNode.class.php",
        "EmbeddedValueNode" =>
            "/org/seasar/dao/node/EmbeddedValueNode.class.php",
        "IfNode" =>
            "/org/seasar/dao/node/IfNode.class.php",
        "ParenBindVariableNode" =>
            "/org/seasar/dao/node/ParenBindVariableNode.class.php",
        "PrefixSqlNode" =>
            "/org/seasar/dao/node/PrefixSqlNode.class.php",
        "SqlNode" =>
            "/org/seasar/dao/node/SqlNode.class.php",
        "EntityManager" =>
            "/org/seasar/dao/EntityManager.class.php",
        "RelationPropertyType" =>
            "/org/seasar/dao/RelationPropertyType.class.php",
        "StatementFactory" =>
            "/org/seasar/extension/db/StatementFactory.class.php",
        "ResultSetFactory" =>
            "/org/seasar/extension/db/ResultSetFactory.class.php",
        "FieldUtil" =>
            "/org/seasar/framework/util/FieldUtil.class.php",
        "ArrayList" =>
            "/org/seasar/dao/util/ArrayList.class.php",
        "HashMap" =>
            "/org/seasar/dao/util/HashMap.class.php",
        "MySQL" =>
            "/org/seasar/dao/dbms/MySQL.class.php",
        "Standard" =>
            "/org/seasar/dao/dbms/Standard.class.php",
        "ColumnNotFoundRuntimeException" =>
            "/org/seasar/extension/db/ColumnNotFoundRuntimeException.class.php",
    );

    public static function load($className){
        if( isset($className, self::$CLASSES) ){
            require_once(S2DAO_PHP5 . self::$CLASSES[$className]);
            return true;
        } else {
            return false;
       }
    }
}
?>
