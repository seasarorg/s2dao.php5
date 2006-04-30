delimiter /
CREATE PROCEDURE SALES_TAX(IN sales INTEGER, OUT tax INTEGER)
begin
	set tax = sales * 0.2;
end;
/

CREATE FUNCTION SALES_TAX2(sales INTEGER) RETURNS INTEGER
return sales * 0.2;
/

CREATE PROCEDURE SALES_TAX3(IN sales INTEGER)
begin
	set sales = sales * 0.2;
end;
/

CREATE PROCEDURE SALES_TAX4(IN sales INTEGER, OUT tax INTEGER, OUT total INTEGER)
begin
	set tax = sales * 0.2;
	set total = sales * 1.2;
end;
/
