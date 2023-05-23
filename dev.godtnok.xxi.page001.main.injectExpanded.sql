/*
 * Sprado XXI
 * Copyright 2021 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.main.injectExpanded.sql
 * Purpose: SQL #SELECT injection to return every individual record
 * Changelog:
 *  2021-09-10: Created
 *  2022-04-03: Added prioritization of [sortorder] from article grouping
 */

SELECT
    * FROM @result
ORDER BY time1, location, guest1