ELECT emp.*, dept.dname dname_0, dept.loc loc_0 FROM EMP as emp, DEPT as dept
WHERE emp.empno = /*empno*/7788 AND emp.deptno = dept.deptno
