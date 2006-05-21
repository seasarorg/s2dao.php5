SELECT emp.*, dept.dname as dname_0, dept.loc as loc_0 FROM EMP as emp, DEPT as dept 
WHERE emp.deptno = dept.deptno ORDER BY emp.empno
