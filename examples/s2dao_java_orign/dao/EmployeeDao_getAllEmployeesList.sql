SELECT emp.*, dept.dname dname_0, dept.loc loc_0 FROM EMP as emp, DEPT as dept 
WHERE emp.deptno = dept.deptno ORDER BY emp.empno
