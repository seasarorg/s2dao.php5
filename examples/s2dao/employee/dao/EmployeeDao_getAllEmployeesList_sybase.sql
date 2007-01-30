SELECT emp.*, dept.DNAME as dname_0, dept.LOC as loc_0
FROM EMP as emp, DEPT as dept 
WHERE emp.DEPTNO = dept.DEPTNO ORDER BY emp.EMPNO
