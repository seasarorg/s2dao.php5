package org.seasar.dao.dbms;


/**
 * @author higa
 *
 */
public class MSSQLServer extends S2Dao_Standard {

	/**
	 * @see org.seasar.dao.Dbms#getSuffix()
	 */
	public String getSuffix() {
		return "_mssql";
	}
	
	public String getIdentitySelectString() {
		return "select @@identity";
	}
}
