<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author nowel
 */
class S2DaoClassLoader {
    
    const ORG_SEASAR = '/org/seasar';
    
    public static $CLASSES = array(
        'S2ActiveRecord' => '/extension/activerecord/S2ActiveRecord.class.php',
        'S2ActiveRecordHelper' => '/extension/activerecord/S2ActiveRecordHelper.class.php',
        'S2ActiveRecordCollection' => '/extension/activerecord/S2ActiveRecordCollection.class.php',
        'S2Dao_DataSetImpl' => '/extension/dataset/impl/S2Dao_DataSetImpl.class.php',
        'S2Dao_ColumnType' => '/extenstion/dataset/S2Dao_ColumnType.class.php',
        'S2Dao_DataColumn' => '/extension/dataset/S2Dao_DataColumn.class.php',
        'S2Dao_DataReader' => '/extension/dataset/S2Dao_DataReader.class.php',
        'S2Dao_DataRow' => '/extesnion/dataset/S2Dao_DataRow.class.php',
        'S2Dao_DataSet' => '/extension/dataset/S2Dao_DataSet.class.php',
        'S2Dao_DataTable' => '/extension/dataset/S2Dao_DataTable.class.php',
        'S2Dao_RowStates' => '/extension/dataset/S2Dao_RowStates.class.php',
        'S2Dao_TableNotFoundRuntimeException' => '/extension/dataset/S2Dao_TableNotFoundRuntimeException.class.php',
        'S2Dao_BasicHandler' => '/extension/db/impl/S2Dao_BasicHandler.class.php',
        'S2Dao_BasicResultSetFactory' => '/extension/db/impl/S2Dao_BasicResultSetFactory.class.php',
        'S2Dao_BasicStatementFactory' => '/extension/db/impl/S2Dao_BasicStatementFactory.class.php',
        'S2Dao_BasicSelectHandler' => '/extension/db/impl/S2Dao_BasicSelectHandler.class.php',
        'S2Dao_BasicUpdateHandler' => '/extension/db/impl/S2Dao_BasicUpdateHandler.class.php',
        'S2Dao_ObjectResultSetHandler' => '/extension/db/impl/S2Dao_ObjectResultSetHandler.class.php',
        'S2Dao_PropertyTypeImpl' => '/extension/db/impl/S2Dao_PropertyTypeImpl.class.php',
        'S2Dao_ResultSetHandler' => '/extension/db/S2Dao_ResultSetHandler.class.php',
        'S2Dao_ResultSetFactory' => '/extension/db/S2Dao_ResultSetFactory.class.php',
        'S2Dao_SelectHandler' => '/extension/db/S2Dao_SelectHandler.class.php',
        'S2Dao_StatementFactory' => '/extension/db/S2Dao_StatementFactory.class.php',
        'S2Dao_UpdateHandler' => '/extension/db/S2Dao_UpdateHandler.class.php',
        'S2Dao_PropertyType' => '/extension/db/S2Dao_PropertyType.class.php',
        'S2Dao_ValueType' => '/extension/db/S2Dao_ValueType.class.php',
        'S2Dao_PHPType' => '/extension/db/S2Dao_PHPType.class.php',
        'S2Dao_PDOType' => '/extension/db/types/S2Dao_PDOType.class.php',
        'S2Dao_AbstractTxInterceptor' => '/extension/tx/S2Dao_AbstractTxInterceptor.class.php',
        'S2Dao_MandatoryInterceptor' => '/extension/tx/S2Dao_MandatoryInterceptor.class.php',
        'S2Dao_NotSupportedInterceptor' => '/extension/tx/S2Dao_NotSupportedInterceptor.class.php',
        'S2Dao_NeverInterceptor' => '/extension/tx/S2Dao_NeverInterceptor.class.php',
        'S2Dao_RequiredInterceptor' => '/extension/tx/S2Dao_RequiredInterceptor.class.php',
        'S2Dao_RequiresNewInterceptor' => '/extension/tx/S2Dao_RequiresNewInterceptor.class.php',
        'S2Dao_TxRule' => '/extension/tx/S2Dao_TxRule.class.php',
        'S2Dao_AnnotationReaderFactory' => '/dao/S2Dao_AnnotationReaderFactory.class.php',
        'S2Dao_BeanAnnotationReader' => '/dao/S2Dao_BeanAnnotationReader.class.php',
        'S2Dao_BeanMetaData' => '/dao/S2Dao_BeanMetaData.class.php',
        'S2Dao_CommandContext' => '/dao/S2Dao_CommandContext.class.php',
        'S2Dao_DaoMetaData' => '/dao/S2Dao_DaoMetaData.class.php',
        'S2Dao_DaoMetaDataFactory' => '/dao/S2Dao_DaoMetaDataFactory.class.php',
        'S2Dao_DaoAnnotationReader' => '/dao/S2Dao_DaoAnnotationReader.class.php',
        'S2Dao_DtoMetaData' => '/dao/S2Dao_DtoMetaData.class.php',
        'S2Dao_Dbms' => '/dao/S2Dao_Dbms.class.php',
        'S2Dao_EntityManager' => '/dao/S2Dao_EntityManager.class.php',
        'S2Dao_Node' => '/dao/S2Dao_Node.class.php',
        'S2Dao_SqlCommand' => '/dao/S2Dao_SqlCommand.class.php',
        'S2Dao_SqlParsar' => '/dao/S2Dao_SqlParsar.class.php',
        'S2Dao_IdentifierGenerator' => '/dao/S2Dao_IdentifierGenerator.class.php',
        'S2Dao_SqlParser' => '/dao/S2Dao_SqlParser.class.php',
        'S2Dao_SqlTokenizer' => '/dao/S2Dao_SqlTokenizer.class.php',
        'S2Dao_ProcedureMetaData' => '/dao/S2Dao_ProcedureMetaData.class.php',
        'S2Dao_RelationPropertyType' => '/dao/S2Dao_RelationPropertyType.class.php',
        'S2DaoAnnotationReader' => '/dao/annotation/S2DaoAnnotationReader.class.php',
        'S2Dao_AbstractAnnotationReader' => '/dao/annotation/S2Dao_AbstractAnnotationReader.class.php',
        'S2Dao_BeanCommentAnnotationReader' => '/dao/annotation/S2Dao_BeanCommentAnnotationReader.class.php',
        'S2Dao_BeanConstantAnnotationReader' => '/dao/annotation/S2Dao_BeanConstantAnnotationReader.class.php',
        'S2Dao_DaoCommentAnnotationReader' => '/dao/annotation/S2Dao_DaoCommentAnnotationReader.class.php',
        'S2Dao_DaoConstantAnnotationReader' => '/dao/annotation/S2Dao_DaoConstantAnnotationReader.class.php',
        'S2Dao_FieldAnnotationReaderFactory' => '/dao/annotation/S2Dao_FieldAnnotationReaderFactory.class.php',
        'S2Dao_FieldBeanAnnotationReader' => '/dao/annotation/S2Dao_FieldBeanAnnotationReader.class.php',
        'S2Dao_FieldDaoAnnotationReader' => '/dao/annotation/S2Dao_FieldDaoAnnotationReader.class.php',
        'Arguments' => '/dao/annotation/type/Arguments.class.php',
        'Bean' => '/dao/annotation/type/Bean.class.php',
        'Column' => '/dao/annotation/type/Column.class.php',
        'Dao' => '/dao/annotation/type/Dao.class.php',
        'Id' => '/dao/annotation/type/Id.class.php',
        'NoPersistentProperty' => '/dao/annotation/type/NoPersistentProperty.class.php',
        'PersistentProperty' => '/dao/annotation/type/PersistentProperty.class.php',
        'Procedure' => '/dao/annotation/type/Procedure.class.php',
        'Query' => '/dao/annotation/type/Query.class.php',
        'Relation' => '/dao/annotation/type/Relation.class.php',
        'Sql' => '/dao/annotation/type/Sql.class.php',
        'S2Dao_CommandContextImpl' => '/dao/context/S2Dao_CommandContextImpl.class.php',
        'S2Dao_DbmsManager' => '/dao/dbms/S2Dao_DbmsManager.class.php',
        'S2Dao_DB2' => '/dao/dbms/S2Dao_DB2.class.php',
        'S2Dao_Firebird' => '/dao/dbms/S2Dao_Firebird.class.php',
        'S2Dao_MSSQLServer' => '/dao/dbms/S2Dao_MSSQLServer.class.php',
        'S2Dao_MySQL' => '/dao/dbms/S2Dao_MySQL.class.php',
        'S2Dao_Oracle' => '/dao/dbms/S2Dao_Oracle.class.php',
        'S2Dao_PostgreSQL' => '/dao/dbms/S2Dao_PostgreSQL.class.php',
        'S2Dao_SQLite' => '/dao/dbms/S2Dao_SQLite.class.php',
        'S2Dao_Standard' => '/dao/dbms/S2Dao_Standard.class.php',
        'S2Dao_ColumnNotFoundRuntimeException' => '/dao/exception/S2Dao_ColumnNotFoundRuntimeException.class.php',
        'S2Dao_DaoNotFoundRuntimeException' => '/dao/exception/S2Dao_DaoNotFoundRuntimeException.class.php',
        'S2Dao_EndCommentNotFoundRuntimeException' => '/dao/exception/S2Dao_EndCommentNotFoundRuntimeException.class.php',
        'S2Dao_IfConditionNotFoundRuntimeException' => '/dao/exception/S2Dao_IfConditionNotFoundRuntimeException.class.php',
        'S2Dao_IllegalBoolExpressionRuntimeException' => '/dao/exception/S2Dao_IllegalBoolExpressionRuntimeException.class.php',
        'S2Dao_IllegalSignatureRuntimeException' => '/dao/exception/S2Dao_IllegalSignatureRuntimeException.class.php',
        'S2Dao_NotExactlyOneRowUpdatedRuntimeException' => '/dao/exception/S2Dao_NotExactlyOneRowUpdatedRuntimeException.class.php',
        'S2Dao_NoRowsUpdatedRuntimeException' => '/dao/exception/S2Dao_NoRowsUpdatedRuntimeException.class.php',
        'S2Dao_NotSingleRowUpdatedRuntimeException' => '/dao/exception/S2Dao_NotSingleRowUpdatedRuntimeException.class.php',
        'S2Dao_PrimaryKeyNotFoundRuntimeException' => '/dao/exception/S2Dao_PrimaryKeyNotFoundRuntimeException.class.php',
        'S2Dao_SIllegalStateException' => '/dao/exception/S2Dao_SIllegalStateException.class.php',
        'S2Dao_SQLRuntimeException' => '/dao/exception/S2Dao_SQLRuntimeException.class.php',
        'S2Dao_UpdateFailureRuntimeException' => '/dao/exception/S2Dao_UpdateFailureRuntimeException.class.php',
        'S2Dao_AbstractBasicProcedureHandler' => '/dao/handler/S2Dao_AbstractBasicProcedureHandler.class.php',
        'S2Dao_MapBasicProcedureHandler' => '/dao/handler/S2Dao_MapBasicProcedureHandler.class.php',
        'S2Dao_ObjectBasicProcedureHandler' => '/dao/handler/S2Dao_ObjectBasicProcedureHandler.class.php',
        'S2Dao_ProcedureHandler' => '/dao/handler/S2Dao_ProcedureHandler.class.php',
        'S2Dao_AbstractIdentifierGenerator' => '/dao/id/S2Dao_AbstractIdentifierGenerator.class.php',
        'S2Dao_AssignedIdentifierGenerator' => '/dao/id/S2Dao_AssignedIdentifierGenerator.class.php',
        'S2Dao_IdentifierGeneratorFactory' => '/dao/id/S2Dao_IdentifierGeneratorFactory.class.php',
        'S2Dao_IdentityIdentifierGenerator' => '/dao/id/S2Dao_IdentityIdentifierGenerator.class.php',
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
        'S2Dao_EntityManagerImpl' => '/dao/impl/S2Dao_EntityManagerImpl.class.php',
        'S2Dao_DtoMetaDataImpl' => '/dao/impl/S2Dao_DtoMetaDataImpl.class.php',
        'S2Dao_DaoMetaDataFactoryImpl' => '/dao/impl/S2Dao_DaoMetaDataFactoryImpl.class.php',
        'S2Dao_DaoMetaDataImpl' => '/dao/impl/S2Dao_DaoMetaDataImpl.class.php',
        'S2Dao_DeleteAutoHandler' => '/dao/impl/S2Dao_DeleteAutoHandler.class.php',
        'S2Dao_DeleteAutoStaticCommand' => '/dao/impl/S2Dao_DeleteAutoStaticCommand.class.php',
        'S2Dao_DeleteBatchAutoStaticCommand' => '/dao/impl/S2Dao_DeleteBatchAutoStaticCommand.class.php',
        'S2Dao_DeleteBatchAutoHandler' => '/dao/impl/S2Dao_DeleteBatchAutoHandler.class.php',
        'S2Dao_InsertAutoHandler' => '/dao/impl/S2Dao_InsertAutoHandler.class.php',
        'S2Dao_InsertAutoDynamicCommand' => '/dao/impl/S2Dao_InsertAutoDynamicCommand.class.php',
        'S2Dao_InsertAutoStaticCommand' => '/dao/impl/S2Dao_InsertAutoStaticCommand.class.php',
        'S2Dao_InsertBatchAutoHandler' => '/dao/impl/S2Dao_InsertBatchAutoHandler.class.php',
        'S2Dao_InsertBatchAutoStaticCommand' => '/dao/impl/S2Dao_InsertBatchAutoStaticCommand.class.php',
        'S2Dao_RelationRowCache' => '/dao/impl/S2Dao_RelationRowCache.class.php',
        'S2Dao_RelationKey' => '/dao/impl/S2Dao_RelationKey.class.php',
        'S2Dao_RelationPropertyTypeImpl' => '/dao/impl/S2Dao_RelationPropertyTypeImpl.class.php',
        'S2Dao_SelectDynamicCommand' => '/dao/impl/S2Dao_SelectDynamicCommand.class.php',
        'S2Dao_StaticStoredProcedureCommand' => '/dao/impl/S2Dao_StaticStoredProcedureCommand.class.php',
        'S2Dao_UpdateAutoHandler' => '/dao/impl/S2Dao_UpdateAutoHandler.class.php',
        'S2Dao_UpdateAutoStaticCommand' => '/dao/impl/S2Dao_UpdateAutoStaticCommand.class.php',
        'S2Dao_UpdateBatchAutoHandler' => '/dao/impl/S2Dao_UpdateBatchAutoHandler.class.php',
        'S2Dao_UpdateBatchAutoStaticCommand' => '/dao/impl/S2Dao_UpdateBatchAutoStaticCommand.class.php',
        'S2Dao_UpdateDynamicCommand' => '/dao/impl/S2Dao_UpdateDynamicCommand.class.php',
        'S2DaoAssertAtLeastOneRowInterceptor' => '/dao/interceptors/S2DaoAssertAtLeastOneRowInterceptor.class.php',
        'S2DaoAssertExactlyOneRowInterceptor' => '/dao/interceptors/S2DaoAssertExactlyOneRowInterceptor.class.php',
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
        'S2Dao_PagerS2DaoInterceptorWrapper' => '/dao/pager/S2Dao_PagerS2DaoInterceptorWrapper.class.php',
        'S2Dao_PagerResultSetWrapper' => '/dao/pager/S2Dao_PagerResultSetWrapper.class.php',
        'S2Dao_PagerSupport' => '/dao/pager/S2Dao_PagerSupport.class.php',
        'S2Dao_PagerViewHelper' => '/dao/pager/S2Dao_PagerViewHelper.class.php',
        'S2Dao_PagerCondition' => '/dao/pager/S2Dao_PagerCondition.class.php',
        'S2Dao_DefaultPagerCondition' => '/dao/pager/S2Dao_DefaultPagerCondition.class.php',
        'S2DaoBeanListReader' => '/dao/unit/S2DaoBeanListReader.class.php',
        'S2DaoBeanReader' => '/dao/unit/S2DaoBeanReader.class.php',
        'S2DaoTestCase' => '/dao/unit/S2DaoTestCase.class.php',
        'S2Dao_ArrayList' => '/dao/util/S2Dao_ArrayList.class.php',
        'S2Dao_ArrayUtil' => '/dao/util/S2Dao_ArrayUtil.class.php',
        'S2Dao_DatabaseMetaDataUtil' => '/dao/util/S2Dao_DatabaseMetaDataUtil.class.php',
        'S2Dao_DataSourceUtil' => '/dao/util/S2Dao_DataSourceUtil.class.php',
        'S2Dao_HashMap' => '/dao/util/S2Dao_HashMap.class.php',
        'S2Dao_MySQLProcedureMetaDataImpl' => '/dao/util/procedure/S2Dao_MySQLProcedureMetaDataImpl.class.php',
        'S2Dao_OracleProcedureMetaDataImpl' => '/dao/util/procedure/S2Dao_OracleProcedureMetaDataImpl.class.php',
        'S2Dao_PostgreSQLProcedureMetaDataImpl' => '/dao/util/procedure/S2Dao_PostgreSQLProcedureMetaDataImpl.class.php',
        'S2Dao_SQLiteProcedureMetaDataImpl' => '/dao/util/procedure/S2Dao_SQLiteProcedureMetaDataImpl.class.php',
        'S2Dao_ProcedureMetaDataFactory' => '/dao/util/procedure/S2Dao_ProcedureMetaDataFactory.class.php',
        'S2Dao_ProcedureInfo' => '/dao/util/procedure/S2Dao_ProcedureInfo.class.php',
        'S2Dao_ProcedureType' => '/dao/util/procedure/S2Dao_ProcedureType.class.php',
    );

    public static function load($className){
        if(isset(self::$CLASSES[$className])){
            require_once S2DAO_PHP5 . self::ORG_SEASAR . self::$CLASSES[$className];
            return true;
        }
        return false;
    }
    
    public static function export(){
        $export = array();
        foreach(self::$CLASSES as $key => $value){
            $export[$key] = S2DAO_PHP5 . self::ORG_SEASAR . $value;
        }
        return $export;
    }
}
?>
