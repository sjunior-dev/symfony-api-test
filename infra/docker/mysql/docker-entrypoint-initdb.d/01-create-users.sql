CREATE USER 'app_admin'@'%' IDENTIFIED WITH mysql_native_password BY '1cgMx56faAD8v2343Adf433x1ppW';
GRANT ALL ON *.* TO 'app_admin'@'%';
GRANT ALL ON app_core_db.* TO 'root'@'%';
