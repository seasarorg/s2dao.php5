package org.seasar.dao.dbms;

import org.seasar.dao.BeanMetaData;
import org.seasar.dao.RelationPropertyType;

/**
 * @author higa
 *
 */
public class Oracle extends S2Dao_Standard {

	/**
	 * @see org.seasar.dao.Dbms#getSuffix()
	 */
	public String getSuffix() {
		return "_oracle";
	}
	
	/**
	 * @see org.seasar.dao.dbms.Standard#createAutoSelectFromClause(org.seasar.dao.S2Dao_BeanMetaData)
	 */
	protected String createAutoSelectFromClause(S2Dao_BeanMetaData beanMetaData) {
		StringBuffer buf = new StringBuffer(100);
		buf.append("FROM ");
		String myTableName = beanMetaData.getTableName();
		buf.append(myTableName);
		StringBuffer whereBuf = new StringBuffer(100);
		for (int i = 0; i < beanMetaData.getRelationPropertyTypeSize(); ++i) {
			S2Dao_RelationPropertyType rpt = beanMetaData.getRelationPropertyType(i);
			S2Dao_BeanMetaData bmd = rpt.getBeanMetaData();
			buf.append(", ");
			buf.append(bmd.getTableName());
			buf.append(" ");
			String yourAliasName = rpt.getPropertyName();
			buf.append(yourAliasName);
			for (int j = 0; j < rpt.getKeySize(); ++j) {
				whereBuf.append(myTableName);
				whereBuf.append(".");
				whereBuf.append(rpt.getMyKey(j));
				whereBuf.append(" = ");
				whereBuf.append(yourAliasName);
				whereBuf.append(".");
				whereBuf.append(rpt.getYourKey(j));
				whereBuf.append("(+)");
				whereBuf.append(" AND ");
			}
		}
		if (whereBuf.length() > 0) {
			whereBuf.setLength(whereBuf.length() - 5);
			buf.append(" WHERE ");
			buf.append(whereBuf);
		}
		return buf.toString();
	}
	
	public String getSequenceNextValString(String sequenceName) {
		return "select " + sequenceName + ".nextval from dual";
	}
}