package org.seasar.dao.dbms;

/**
 * @author makotan
 *
 */
public class MaxDB extends S2Dao_Standard {
	
	public String getSuffix() {
		return "_maxdb";
	}

	public String getSequenceNextValString(String sequenceName) {
		return "select " + sequenceName + ".nextval from dual";
	}
}
