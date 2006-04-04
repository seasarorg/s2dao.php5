<?php

/**
 * @author nowel
 */
final class S2Dao {
    
    const HOME = S2DAO_PHP5;
    const USE_COMMENT = S2DAO_PHP5_USE_COMMENT;
    const version = '1.1.0-RC1';

}

/**
 * @author nowel
 */
class S2DaoClassLoader {
    
    const ORG_SEASAR = '/org/seasar';
    
    public static $CLASSES = array(
        'S2ActiveRecord' => '/extension/activerecord/S2ActiveRecord.class.php',
        'S2ActiveRecordHelper' => '/extension/activerecord/S2ActiveRecordHelper.class.php',
        'S2ActiveRecordCollection' => '/extension/activerecord/S2ActiveRecordCollection.class.php',
        'S2Dao_BasicHandler' => '/extension/db/impl/S2Dao_BasicHandler.class.php',
        'S2Dao_BasicStatementFactory' => '/extension/db/impl/S2Dao_BasicStatementFactory.class.php',
        'S2Dao_BasicResultSetFactory' => '/extension/db/impl/S2Dao_BasicResultSetFactory.class.php',
        'S2Dao_BasicSelectHandler' => '/extension/db/impl/S2Dao_BasicSelectHandler.class.php',
        'S2Dao_ResultSetHandler' => '/extension/db/S2Dao_ResultSetHandler.class.php',
        'S2Dao_StatementFactory' => '/extension/db/S2Dao_StatementFactory.class.php',
        'S2Dao_ResultSetFactory' => '/extension/db/S2Dao_ResultSetFactory.class.php',
        'S2Dao_SelectHandler' => '/extension/db/S2Dao_SelectHandler.class.php',
        'S2Dao_UpdateHandler' => '/extension/db/S2Dao_UpdateHandler.class.php',
        'S2Dao_ObjectResultSetHandler' => '/extension/db/impl/S2Dao_ObjectResultSetHandler.class.php',
        'S2Dao_PropertyType' => '/extension/db/S2Dao_PropertyType.class.php',
        'S2Dao_PropertyTypeImpl' => '/extension/db/impl/S2Dao_PropertyTypeImpl.class.php',
        'S2Dao_ValueType' => '/extension/db/S2Dao_ValueType.class.php',
        'S2Dao_PHPType' => '/extension/db/S2Dao_PHPType.class.php',
        'S2Dao_PDOType' => '/extension/db/types/S2Dao_PDOType.class.php',
        'S2Dao_ColumnNotFoundRuntimeException' => '/extension/db/S2Dao_ColumnNotFoundRuntimeException.class.php',
        'S2Dao_AnnotationReaderFactory' => '/dao/S2Dao_AnnotationReaderFactory.class.php',
        'S2Dao_BeanAnnotationReader' => '/dao/S2Dao_BeanAnnotationReader.class.php',
        'S2Dao_BeanMetaData' => '/dao/S2Dao_BeanMetaData.class.php',
        'S2Dao_CommandContext' => '/dao/S2Dao_CommandContext.class.php',
        'S2Dao_DaoMetaData' => '/dao/S2Dao_DaoMetaData.class.php',
        'S2Dao_DaoMetaDataFactory' => '/dao/S2Dao_DaoMetaDataFactory.class.php',
        'S2Dao_DaoAnnotationReader' => '/dao/S2Dao_DaoAnnotationReader.class.php',
        'S2Dao_DtoMetaData' => '/dao/S2Dao_DtoMetaData.class.php',
        'S2Dao_Dbms' => '/dao/S2Dao_Dbms.class.php',
        'S2Dao_Node' => '/dao/S2Dao_Node.class.php',
        'S2Dao_SqlCommand' => '/dao/S2Dao_SqlCommand.class.php',
        'S2Dao_SqlParsar' => '/dao/S2Dao_SqlParsar.class.php',
        'S2Dao_IdentifierGenerator' => '/dao/S2Dao_IdentifierGenerator.class.php',
        'S2Dao_SqlParser' => '/dao/S2Dao_SqlParser.class.php',
        'S2Dao_SqlTokenizer' => '/dao/S2Dao_SqlTokenizer.class.php',
        'S2Dao_RelationPropertyType' => '/dao/S2Dao_RelationPropertyType.class.php',
        'S2Dao_RelationPropertyTypeImpl' => '/dao/impl/S2Dao_RelationPropertyTypeImpl.class.php',
        'S2Dao_RelationKey' => '/dao/impl/S2Dao_RelationKey.class.php',
        'S2Dao_RelationRowCache' => '/dao/impl/S2Dao_RelationRowCache.class.php',
        'S2Dao_CommandContextImpl' => '/dao/context/S2Dao_CommandContextImpl.class.php',
        'S2Dao_AbstractIdentifierGenerator' => '/dao/id/S2Dao_AbstractIdentifierGenerator.class.php',
        'S2Dao_AssignedIdentifierGenerator' => '/dao/id/S2Dao_AssignedIdentifierGenerator.class.php',
        'S2Dao_IdentifierGeneratorFactory' => '/dao/id/S2Dao_IdentifierGeneratorFactory.class.php',
        'S2Dao_IdentityIdentifierGenerator' => '/dao/id/S2Dao_IdentityIdentifierGenerator.class.php',
        'S2Dao_DbmsManager' => '/dao/dbms/S2Dao_DbmsManager.class.php',
        'S2Dao_Standard' => '/dao/dbms/S2Dao_Standard.class.php',
        'S2Dao_DaoNotFoundRuntimeException' => '/dao/exception/S2Dao_DaoNotFoundRuntimeException.class.php',
        'S2Dao_EndCommentNotFoundRuntimeException' => '/dao/exception/S2Dao_EndCommentNotFoundRuntimeException.class.php',
        'S2Dao_IfConditionNotFoundRuntimeException' => '/dao/exception/S2Dao_IfConditionNotFoundRuntimeException.class.php',
        'S2Dao_IllegalBoolExpressionRuntimeException' => '/dao/exception/S2Dao_IllegalBoolExpressionRuntimeException.class.php',
        'S2Dao_IllegalSignatureRuntimeException' => '/dao/exception/S2Dao_IllegalSignatureRuntimeException.class.php',
        'S2Dao_NotSingleRowUpdatedRuntimeException' => '/dao/exception/S2Dao_NotSingleRowUpdatedRuntimeException.class.php',
        'S2Dao_PrimaryKeyNotFoundRuntimeException' => '/dao/exception/S2Dao_PrimaryKeyNotFoundRuntimeException.class.php',
        'S2Dao_SQLRuntimeException' => '/dao/exception/S2Dao_SQLRuntimeException.class.php',
        'S2Dao_UpdateFailureRuntimeException' => '/dao/exception/S2Dao_UpdateFailureRuntimeException.class.php',
        'S2Dao_AbstractAutoHandler' => '/dao/impl/S2Dao_AbstractAutoHandler.class.php',
        'S2Dao_AbstractAutoStaticCommand' => '/dao/impl/S2Dao_AbstractAutoStaticCommand.class.php',
        'S2Dao_AbstractBeanMetaDataResultSetHandler' => '/dao/impl/S2Dao_AbstractBeanMetaDataResultSetHandler.class.php',
        'S2Dao_AbstractBatchAutoHandler' => '/dao/impl/S2Dao_AbstractBatchAutoHandler.class.php',
        'S2Dao_AbstractBatchAutoStaticCommand' => '/dao/impl/S2Dao_AbstractBatchAutoStaticCommand.class.php',
        'S2Dao_AbstractDao' => '/dao/impl/S2Dao_AbstractDao.class.php',
        'S2Dao_AbstractDynamicCommand' => '/dao/impl/S2Dao_AbstractDynamicCommand.class.php',
        'S2Dao_AbstractStaticCommand' => '/dao/impl/S2Dao_AbstractStaticCommand.class.php',
        'S2Dao_AbstractSqlCommand' => '/dao/impl/S2Dao_AbstractSqlCommand.class.php',
        'S2Dao_BeanMetaDataImpl' => '/dao/impl/S2Dao_BeanMetaDataImpl.class.php',
        'S2Dao_BeanMetaDataResultSetHandler' => '/dao/impl/S2Dao_BeanMetaDataResultSetHandler.class.php',
        'S2Dao_BeanArrayMetaDataResultSetHandler' => '/dao/impl/S2Dao_BeanArrayMetaDataResultSetHandler.class.php',
        'S2Dao_BeanListMetaDataResultSetHandler' => '/dao/impl/S2Dao_BeanListMetaDataResultSetHandler.class.php',
        'S2Dao_DtoMetaDataImpl' => '/dao/impl/S2Dao_DtoMetaDataImpl.class.php',
        'S2Dao_DaoMetaDataFactoryImpl' => '/dao/impl/S2Dao_DaoMetaDataFactoryImpl.class.php',
        'S2Dao_DaoMetaDataImpl' => '/dao/impl/S2Dao_DaoMetaDataImpl.class.php',
        'S2Dao_DeleteAutoHandler' => '/dao/impl/S2Dao_DeleteAutoHandler.class.php',
        'S2Dao_DeleteAutoStaticCommand' => '/dao/impl/S2Dao_DeleteAutoStaticCommand.class.php',
        'S2Dao_DeleteBatchAutoStaticCommand' => '/dao/impl/S2Dao_DeleteBatchAutoStaticCommand.class.php',
        'S2Dao_DeleteBatchAutoHandler' => '/dao/impl/S2Dao_DeleteBatchAutoHandler.class.php',
        'S2Dao_DatabaseMetaDataUtil' => '/dao/util/S2Dao_DatabaseMetaDataUtil.class.php',
        'S2Dao_DataSourceUtil' => '/dao/util/S2Dao_DataSourceUtil.class.php',
        'S2Dao_EntityManager' => '/dao/S2Dao_EntityManager.class.php',
        'S2Dao_EntityManagerImpl' => '/dao/impl/S2Dao_EntityManagerImpl.class.php',
        'S2DaoAnnotationReader' => '/dao/annotation/S2DaoAnnotationReader.class.php',
        'S2Dao_AbstractBeanAnnotationReader' => '/dao/annotation/S2Dao_AbstractBeanAnnotationReader.class.php',
        'S2Dao_AbstractDaoAnnotationReader' => '/dao/annotation/S2Dao_AbstractDaoAnnotationReader.class.php',
        'S2Dao_BeanAnnotation' => '/dao/annotation/S2Dao_BeanAnnotation.class.php',
        'S2Dao_BeanCommentAnnotationReader' => '/dao/annotation/S2Dao_BeanCommentAnnotationReader.class.php',
        'S2Dao_BeanConstantAnnotationReader' => '/dao/annotation/S2Dao_BeanConstantAnnotationReader.class.php',
        'S2Dao_DaoAnnotation' => '/dao/annotation/S2Dao_DaoAnnotation.class.php',
        'S2Dao_DaoCommentAnnotationReader' => '/dao/annotation/S2Dao_DaoCommentAnnotationReader.class.php',
        'S2Dao_DaoConstantAnnotationReader' => '/dao/annotation/S2Dao_DaoConstantAnnotationReader.class.php',
        'S2Dao_FieldAnnotationReaderFactory' => '/dao/annotation/S2Dao_FieldAnnotationReaderFactory.class.php',
        'S2Dao_FieldBeanAnnotationReader' => '/dao/annotation/S2Dao_FieldBeanAnnotationReader.class.php',
        'S2Dao_FieldDaoAnnotationReader' => '/dao/annotation/S2Dao_FieldDaoAnnotationReader.class.php',
        'S2Dao_InsertAutoHandler' => '/dao/impl/S2Dao_InsertAutoHandler.class.php',
        'S2Dao_InsertAutoStaticCommand' => '/dao/impl/S2Dao_InsertAutoStaticCommand.class.php',
        'S2Dao_InsertBatchAutoHandler' => '/dao/impl/S2Dao_InsertBatchAutoHandler.class.php',
        'S2Dao_InsertBatchAutoStaticCommand' => '/dao/impl/S2Dao_InsertBatchAutoStaticCommand.class.php',
        'S2Dao_UpdateAutoHandler' => '/dao/impl/S2Dao_UpdateAutoHandler.class.php',
        'S2Dao_UpdateAutoStaticCommand' => '/dao/impl/S2Dao_UpdateAutoStaticCommand.class.php',
        'S2Dao_UpdateBatchAutoHandler' => '/dao/impl/S2Dao_UpdateBatchAutoHandler.class.php',
        'S2Dao_UpdateBatchAutoStaticCommand' => '/dao/impl/S2Dao_UpdateBatchAutoStaticCommand.class.php',
        'S2Dao_UpdateDynamicCommand' => '/dao/impl/S2Dao_UpdateDynamicCommand.class.php',
        'S2Dao_RelationRowCache' => '/dao/impl/S2Dao_RelationRowCache.class.php',
        'S2Dao_SelectDynamicCommand' => '/dao/impl/S2Dao_SelectDynamicCommand.class.php',
        'S2DaoInterceptor' => '/dao/interceptors/S2DaoInterceptor.class.php',
        'S2Dao_AbstractNode' => '/dao/node/S2Dao_AbstractNode.class.php',
        'S2Dao_BeginNode' => '/dao/node/S2Dao_BeginNode.class.php',
        'S2Dao_BindVariableNode' => '/dao/node/S2Dao_BindVariableNode.class.php',
        'S2Dao_ContainerNode' => '/dao/node/S2Dao_ContainerNode.class.php',
        'S2Dao_ElseNode' => '/dao/node/S2Dao_ElseNode.class.php',
        'S2Dao_EmbeddedValueNode' => '/dao/node/S2Dao_EmbeddedValueNode.class.php',
        'S2Dao_IfNode' => '/dao/node/S2Dao_IfNode.class.php',
        'S2Dao_ParenBindVariableNode' => '/dao/node/S2Dao_ParenBindVariableNode.class.php',
        'S2Dao_PrefixSqlNode' => '/dao/node/S2Dao_PrefixSqlNode.class.php',
        'S2Dao_SqlNode' => '/dao/node/S2Dao_SqlNode.class.php',
        'S2Dao_SqlParserImpl' => '/dao/parser/S2Dao_SqlParserImpl.class.php',
        'S2Dao_SqlTokenizerImpl' => '/dao/parser/S2Dao_SqlTokenizerImpl.class.php',
        'S2Dao_ArrayList' => '/dao/util/S2Dao_ArrayList.class.php',
        'S2Dao_ArrayUtil' => '/dao/util/S2Dao_ArrayUtil.class.php',
        'S2Dao_HashMap' => '/dao/util/S2Dao_HashMap.class.php',
    );

    public static function load($className){
        if(isset(self::$CLASSES[$className])){
            require_once S2Dao::HOME . self::ORG_SEASAR . self::$CLASSES[$className];
            return true;
        } else {
            return false;
        }
    }
    
    public static function export(){
        $export = array();
        foreach(self::$CLASSES as $key => $value){
            $export[$key] = S2Dao::HOME . self::ORG_SEASAR . $value;
        }
        return $export;
    }
}
?>
