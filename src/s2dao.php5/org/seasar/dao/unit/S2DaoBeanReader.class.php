<?php

/**
 * @author nowel
 */
class S2DaoBeanReader implements DataReader {

	private $dataSet_ = new DataSetImpl();
	private $table_ = dataSet_.addTable("S2DaoBean");

	protected S2DaoBeanReader() {
	}

	public S2DaoBeanReader(Object bean, DatabaseMetaData dbMetaData) {
		Dbms dbms = DbmsManager.getDbms(dbMetaData);
		BeanMetaData beanMetaData = new BeanMetaDataImpl(bean.getClass(),
				dbMetaData, dbms);
		setupColumns(beanMetaData);
		setupRow(beanMetaData, bean);
	}

	protected void setupColumns(BeanMetaData beanMetaData) {
		for (int i = 0; i < beanMetaData.getPropertyTypeSize(); ++i) {
			PropertyType pt = beanMetaData.getPropertyType(i);
			Class propertyType = pt.getPropertyDesc().getPropertyType();
			table_.addColumn(pt.getColumnName(), ColumnTypes
					.getColumnType(propertyType));
		}
		for (int i = 0; i < beanMetaData.getRelationPropertyTypeSize(); ++i) {
			RelationPropertyType rpt = beanMetaData.getRelationPropertyType(i);
			for (int j = 0; j < rpt.getBeanMetaData().getPropertyTypeSize(); j++) {
				PropertyType pt = rpt.getBeanMetaData().getPropertyType(j);
				String columnName = pt.getColumnName() + "_"
						+ rpt.getRelationNo();
				Class propertyType = pt.getPropertyDesc().getPropertyType();
				table_.addColumn(columnName, ColumnTypes
						.getColumnType(propertyType));
			}
		}
	}

	protected void setupRow(BeanMetaData beanMetaData, Object bean) {
		DataRow row = table_.addRow();
		for (int i = 0; i < beanMetaData.getPropertyTypeSize(); ++i) {
			PropertyType pt = beanMetaData.getPropertyType(i);
			PropertyDesc pd = pt.getPropertyDesc();
			Object value = pd.getValue(bean);
			ColumnType ct = ColumnTypes.getColumnType(pd.getPropertyType());
			row.setValue(pt.getColumnName(), ct.convert(value, null));
		}
		for (int i = 0; i < beanMetaData.getRelationPropertyTypeSize(); ++i) {
			RelationPropertyType rpt = beanMetaData.getRelationPropertyType(i);
			Object relationBean = rpt.getPropertyDesc().getValue(bean);
			if (relationBean == null) {
				continue;
			}
			for (int j = 0; j < rpt.getBeanMetaData().getPropertyTypeSize(); j++) {
				PropertyType pt = rpt.getBeanMetaData().getPropertyType(j);
				String columnName = pt.getColumnName() + "_"
						+ rpt.getRelationNo();
				PropertyDesc pd = pt.getPropertyDesc();
				Object value = pd.getValue(relationBean);
				ColumnType ct = ColumnTypes.getColumnType(pd.getPropertyType());
				row.setValue(columnName, ct.convert(value, null));
			}
		}
		row.setState(RowStates.UNCHANGED);
	}

	public DataSet read() {
		return dataSet_;
	}

}
?>
