<?php

$query = "declare @tbl as table (
        field1 varchar(100),  -- This comment will cause unwanted lineshifts in the output
        field2 int,
        field3 datetime, -- This comment will, again, cause unwanted lineshifts in the output
        field4 varchar(120),
        field5 varchar(200)
    )";
        
        
        require_once('resources/php/SqlFormatter.php');

echo SqlFormatter::format($query);
