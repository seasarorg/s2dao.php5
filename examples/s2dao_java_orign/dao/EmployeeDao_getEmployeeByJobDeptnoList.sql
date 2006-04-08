SELECT * FROM EMP
/*BEGIN*/WHERE
  /*IF job !== null*/EMP.JOB = /*job*/'CLERK'/*END*/
  /*IF deptno !== null*/AND EMP.DEPTNO = /*deptno*/20/*END*/
/*END*/
