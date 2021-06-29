/*
Change:
$DATABASE1 -> origin
$DATABASE2 -> destiny
*/
SELECT CONCAT('RENAME TABLE $DATABASE1.`', TABLE_NAME, '` TO $DATABASE2.`', TABLE_NAME,  '`; ')
FROM information_schema.TABLES 
WHERE table_schema='$DATABASE1';


-- using group_concat
SELECT GROUP_CONCAT('RENAME TABLE $DATABASE1.`', TABLE_NAME, '` TO $DATABASE2.`', TABLE_NAME SEPARATOR  '`; ')
FROM information_schema.TABLES 
WHERE table_schema='$DATABASE1';

-- to use group_concat, may need to change max len
SET SESSION group_concat_max_len = 100000000; 