<?php

/**
 * @author nowel
 */
class S2Dao_MapBasicProcedureHandler extends S2Dao_AbstractBasicProcedureHandler {
//
//	public MapBasicProcedureHandler(DataSource ds, String procedureName) {
//		this(ds, procedureName, BasicStatementFactory.INSTANCE);
//	}
//
//	public MapBasicProcedureHandler(DataSource ds, String procedureName, StatementFactory statementFactory) {
//		setDataSource(ds);
//		setProcedureName(procedureName);
//		setStatementFactory(statementFactory);
//		initTypes();
//	}
//	protected Object execute(Connection connection, Object[] args){
//		CallableStatement cs = null;
//		try {
//			cs = prepareCallableStatement(connection);
//			bindArgs(cs, args);
//			cs.execute();
//			Map result = new HashMap();
//			for (int i = 0; i < columnInOutTypes_.length; i++) {
//				if(isOutputColum(columnInOutTypes_[i].intValue())){
//					result.put(columnNames_[i],cs.getObject(i+1));
//				}
//			}
//			return result;
//		} catch (SQLException e) {
//			throw new SQLRuntimeException(e);
//		} finally {
//			StatementUtil.close(cs);
//		}
//	}
//
}
?>