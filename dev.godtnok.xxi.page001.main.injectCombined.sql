/*
 * Sprado XXI
 * Copyright 2021 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.main.injectCombined.sql
 * Purpose: SQL #SELECT injection to combine items marked for combination
 * Changelog:
 *  2021-09-10: Created
 *  2021-11-22: Added replacement name variable for breakfast guests
 *  2022-04-03: Added [sortorder] from article grouping
 */

SELECT
	subquery = case when combine = 1 then '' else subquery end,
	mpehotel,
	bookref = case when combine = 1 then '' else bookref end,
        statno,
        stattext,
	date1 = case when combine = 1 then '' else date1 end,
	date2 = case when combine = 1 then '' else date2 end,
	time1,
	time2,
	guest1 = case when combine = 1 then '#BKFSTNAME' else guest1 end,
	guest2 = case when combine = 1 then '' else guest2 end,
	location = case when combine = 1 then '' else location end,
	sum(adults) adults,
        sum(children1) children1,
        sum(children2) children2,
        sum(children3) children3,
        sum(children4) children4,
	ptyp = case when combine = 1 then 0 else ptyp end,
	artref,
	artshort,
	arttext,
	data1,
	data2,
	data3,
	data4,
	data5,
	combine,
	skipfirst,
        sortorder
FROM
    @result

GROUP BY
	  case when combine = 1 then '' else subquery end
	, mpehotel
	, case when combine = 1 then '' else bookref end
        , statno
        , stattext
	, case when combine = 1 then '' else date1 end
	, case when combine = 1 then '' else date2 end
	, time1
	, time2
	, case when combine = 1 then '#BKFSTNAME' else guest1 end
	, case when combine = 1 then '' else guest2 end
	, case when combine = 1 then '' else location end
	, case when combine = 1 then 0 else ptyp end
	, artref
	, artshort
	, arttext
	, data1
	, data2
	, data3
	, data4
	, data5
	, combine
	, skipfirst
        , sortorder
--        , dep
--        , artgroup

ORDER BY time1, location, guest1