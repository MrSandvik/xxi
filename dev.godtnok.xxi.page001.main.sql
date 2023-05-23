/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.main.sql
 * Purpose: HTML overview query
 * Changelog:
 *  2021-05-12: Created
 *  2021-09-10: Added injection token ¤RESULT for alternate result outputs
 *  2022-01-20: @buchnr - Changed room number handling from int to varchar '8000'
 *  2022-01-25: bq01 - Added match '%:%' for time start/end variables
 *  2022-03-31: Correctly counting breakfasts
 *  2022-04-03: Prioritizing customized article texts from postings (bktleist)
 *              Better parsing of time values
 *              Handling //HH:MM// in fixed charges
 *              Prioritizing combined text value from UDF
 *  2022-04-05: fl01:
 *               - select distinct (to avoid reading all assigned departments in @art_sprado)
 *               - Do not count departure date unless 'Date of departure' specified
 *               - If original article name is different from text in fixleist; show original text in data1
 *  2022-04-12: fl01: Added multiplication of [anzahl]
 *  2022-04-24: Changed datatype from varchar(10) to date on date1 and date2 in @result definition
 *  2022-05-23: Added signature (checksum) and timestamp to @result
 *  2022-06-09: Added statno and stattext to @result
 *              Added temp table @resstat
 *  2022-06-13: Added conditional execution of bu01 and bu02 vs bu03 and bu04 queries
 *              01/02 if resrates ckit exists; else 03/04
 *  2022-08-02: Replaced all para3 = 5401 to para3 like '5401'
 *              Commented out references to ckit_dailyrates_resrates in bu03 and bu04 
 *  2022-09-16: bq01; Changed bktbuzu join to get the persistent GM/AC ID from the main gesnr in Buch
 *              bq01; Merged in manually added packaged items from packref instead of eventnr
 *  2022-10-23: Replaced #DATABASE.#SCHEMA. with #PREFIX
 *  2023-01-16: Increased arttext definition in @result declaration from 100 to 500
 *              Fixed article text retrieval in @ptyp_art from isnull(md.data, ar.text) to iif(len(isnull(md.data, '')) < 1, ar.text, md.data)
 */


-- Variables
declare @dt as date
    set @dt = '#DATE';

declare @mpehotel as int
    set @mpehotel = #MPEHOTEL;

-- Defining output table
declare @result as table(
    subquery varchar(4),
    mpehotel int,
    bookref int,
    statno int,
    stattext varchar(50),
    date1 date,
    date2 date,
    time1 varchar(8),
    time2 varchar(8),
    guest1 varchar(100),
    guest2 varchar(100),
    location varchar(100),
    adults int,			
    children1 int,
    children2 int,
    children3 int,
    children4 int,
    ptyp int,
    artref int,
    artshort varchar(60),
    arttext varchar(500),
    data1 varchar(3000),
    data2 varchar(3000),
    data3 varchar(3000),
    data4 varchar(3000),
    data5 varchar(3000),
    combine int,
    skipfirst int,
    sortorder varchar(5),
    signature int,
    timestamp datetime
    );

-- Temp table holding articles to be counted; subset combined and/or skip first day
declare @art_sprado table (
    ref int,
    mpehotel int,
    dep int,
    placeselect int,
    groupselect int,
    maxnosold varchar(max),
    addtext varchar(max),
    combined int,
    combinedtext varchar(max),
    date varchar(max),
    time varchar(max),
    endtime varchar(max),
    fixtime varchar(max),
    fixdate int,
    fixaddtext int,
    fixroom int,
    countnoadults int,
    countkid1 int,
    countkid2 int,
    countkid3 int,
    countkid4 int,
    superiorfilter varchar(max),
    articlesort varchar(max),
    skipfirst int,
    short varchar(60),
    text varchar(100)
    );

print 'Populating @art_sprado...'
insert into @art_sprado

select distinct 
    md.ref,
    md.mpehotel,
    isnull(mx.data, '') dep,
    isnull((select top 1 data from #PREFIXmetadata m2 where m2.ref = md.ref and m2.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_PlaceSelect' and para3 like '5401') = 0, 0, 99999))) and m2.type = md.type and m2.xkey like 'Sprado_PlaceSelect'), '') PlaceSelect,
    isnull((select top 1 cd.para from #PREFIXmetacodes cd where cd.ref = (select top 1 data from #PREFIXmetadata m4 where m4.ref = md.ref and m4.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_GroupSelect' and para3 like '5401') = 0, 0, 99999))) and m4.type = md.type and m4.xkey like 'Sprado_GroupSelect')), '') GroupSelect,
    isnull((select data from #PREFIXmetadata m5 where m5.ref = md.ref and m5.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_MaxNoSold' and para3 like '5401') = 0, 0, 99999))) and m5.type = md.type and m5.xkey like 'Sprado_MaxNoSold'), '') MaxNoSold,
    isnull((select data from #PREFIXmetadata m6 where m6.ref = md.ref and m6.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_AddText' and para3 like '5401') = 0, 0, 99999))) and m6.type = md.type and m6.xkey like 'Sprado_AddText'), '') AddText,
    isnull((select 1 from #PREFIXmetadata m7 where m7.ref = md.ref and m7.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_Combined' and para3 like '5401') = 0, 0, 99999))) and m7.type = md.type and m7.xkey like 'Sprado_Combined'), 0) Combined,
    isnull((select data from #PREFIXmetadata m8 where m8.ref = md.ref and m8.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_CombinedText' and para3 like '5401') = 0, 0, 99999))) and m8.type = md.type and m8.xkey like 'Sprado_CombinedText'), '') CombinedText,
    isnull((select data from #PREFIXmetadata m9 where m9.ref = md.ref and m9.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_Date' and para3 like '5401') = 0, 0, 99999))) and m9.type = md.type and m9.xkey like 'Sprado_Date'), '') Date,
    isnull((select data from #PREFIXmetadata m10 where m10.ref = md.ref and m10.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_Time' and para3 like '5401') = 0, 0, 99999))) and m10.type = md.type and m10.xkey like 'Sprado_Time'), '') Time,
    isnull((select data from #PREFIXmetadata m11 where m11.ref = md.ref and m11.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_EndTime' and para3 like '5401') = 0, 0, 99999))) and m11.type = md.type and m11.xkey like 'Sprado_EndTime'), '') EndTime,
    isnull((select data from #PREFIXmetadata m12 where m12.ref = md.ref and m12.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_FixTime' and para3 like '5401') = 0, 0, 99999))) and m12.type = md.type and m12.xkey like 'Sprado_FixTime'), '') FixTime,
    isnull((select data from #PREFIXmetadata m13 where m13.ref = md.ref and m13.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_FixDate' and para3 like '5401') = 0, 0, 99999))) and m13.type = md.type and m13.xkey like 'Sprado_FixDate'), '') FixDate,
    isnull((select data from #PREFIXmetadata m14 where m14.ref = md.ref and m14.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_FixAddText' and para3 like '5401') = 0, 0, 99999))) and m14.type = md.type and m14.xkey like 'Sprado_FixAddText'), '') FixAddText,
    isnull((select data from #PREFIXmetadata m15 where m15.ref = md.ref and m15.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_FixRoom' and para3 like '5401') = 0, 0, 99999))) and m15.type = md.type and m15.xkey like 'Sprado_FixRoom'), '') FixRoom,
    isnull((select data from #PREFIXmetadata m16 where m16.ref = md.ref and m16.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_CountNoAdults' and para3 like '5401') = 0, 0, 99999))) and m16.type = md.type and m16.xkey like 'Sprado_CountNoAdults'), '') CountNoAdults,
    isnull((select data from #PREFIXmetadata m17 where m17.ref = md.ref and m17.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_CountKid1' and para3 like '5401') = 0, 0, 99999))) and m17.type = md.type and m17.xkey like 'Sprado_CountKid1'), '') CountKid1,
    isnull((select data from #PREFIXmetadata m18 where m18.ref = md.ref and m18.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_CountKid2' and para3 like '5401') = 0, 0, 99999))) and m18.type = md.type and m18.xkey like 'Sprado_CountKid2'), '') CountKid2,
    isnull((select data from #PREFIXmetadata m19 where m19.ref = md.ref and m19.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_CountKid3' and para3 like '5401') = 0, 0, 99999))) and m19.type = md.type and m19.xkey like 'Sprado_CountKid3'), '') CountKid3,
    isnull((select data from #PREFIXmetadata m20 where m20.ref = md.ref and m20.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_CountKid4' and para3 like '5401') = 0, 0, 99999))) and m20.type = md.type and m20.xkey like 'Sprado_CountKid4'), '') CountKid4,
    isnull((select top 1 data from #PREFIXmetadata m21 where m21.ref = md.ref and m21.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_SuperiorFilter' and para3 like '5401') = 0, 0, 99999))) and m21.type = md.type and m21.xkey like 'Sprado_SuperiorFilter'), '') SuperiorFilter,
    isnull((select data from #PREFIXmetadata m22 where m22.ref = md.ref and m22.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_ArticleSort' and para3 like '5401') = 0, 0, 99999))) and m22.type = md.type and m22.xkey like 'Sprado_ArticleSort'), '') ArticleSort,
    isnull((select 1 from #PREFIXmetadata m23 where m23.ref = md.ref and m23.mpehotel in(md.mpehotel, (iif((select para6 from #PREFIXmetaudf where short like 'Sprado_SkipFirst' and para3 like '5401') = 0, 0, 99999))) and m23.type = md.type and m23.xkey like 'Sprado_SkipFirst'), 0) SkipFirst,
    ar.short,
    ar.text
from #PREFIXmetadata md
left join #PREFIXmetadata mx on md.ref = mx.ref and md.mpehotel = mx.mpehotel and mx.xkey like 'Sprado_DepSelect'
left join #PREFIXarticles ar on md.ref = ar.ref
where md.type like '5401' 
  and md.xkey like 'sprado_countselect' 
  and md.ref in(select ref from #PREFIXarticles);


-- Temp table holding ptyps and their direct relation to splittabs
declare @ptyp_st table (ptypnr int, stabnr int, ptyp varchar(60), mpehotel int)
print 'Populating @ptyp_st...'
insert into @ptyp_st

select ptypnr, stab1, ptyp, mpehotel
from #PREFIXptyp 
where stab1 > 0
        union all
select ptypnr, stab2, ptyp, mpehotel
from #PREFIXptyp 
where stab2 > 0
        union all
select ptypnr, stab3, ptyp, mpehotel
from #PREFIXptyp 
where stab3 > 0
        union all
select ptypnr, stab4, ptyp, mpehotel
from #PREFIXptyp 
where stab4 > 0
        union all
select ptypnr, stab5, ptyp, mpehotel
from #PREFIXptyp 
where stab5 > 0
        union all
select ptypnr, stab6, ptyp, mpehotel
from #PREFIXptyp 
where stab6 > 0
        union all
select ptypnr, stab7, ptyp, mpehotel
from #PREFIXptyp 
where stab7 > 0
        union all
select ptypnr, stab8, ptyp, mpehotel
from #PREFIXptyp 
where stab8 > 0
        union all
select ptypnr, stab9, ptyp, mpehotel
from #PREFIXptyp 
where stab9 > 0
        union all
select ptypnr, stab10, ptyp, mpehotel
from #PREFIXptyp 
where stab10 > 0;


-- Temp table for the above in distinction
declare @ptyp_stab table (ptypnr int, stabnr int, ptyp varchar(60), mpehotel int)
        print 'Populating @ptyp_stab...'
	insert into @ptyp_stab
	
	select DISTINCT ptypnr, stabnr, ptyp, mpehotel
	from @ptyp_st;


-- Temp table for active booking numbers for controlling date
declare @buchnr table (buchnr int)
        print 'Populating @buchnr...'
	insert into @buchnr

	select buchnr
	from #PREFIXbuch bu
	left join #PREFIXzimmer zi on bu.zimmernr = zi.zinr
	where bu.datumvon <= @dt
	  and bu.datumbis >= @dt
	  and bu.mpehotel = @mpehotel
	  and bu.anzerw > 0
	  and zi.ziname < '8000';


-- Temp table for reservation status codes
declare @resstat table (subquery varchar(2), statno int, stattext varchar(50))

insert into @resstat
select 
    'bu' subquery,
    rst.resnr statno,
    rst.resbez stattext
from #PREFIXresstat rst 

union all

select 
    'bq' subquery,
    bst.internnr statno,
    bst.text stattext
from #PREFIXbktstat bst 

order by subquery, statno;


-- Temp table for ckit: reservation rates if the feature exists; extension to @ptyp_stab
IF (EXISTS (SELECT * 
    FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_SCHEMA = '[#SCHEMA]' 
    AND  TABLE_NAME = 'ckit_dailyrates_resrates'))
BEGIN

declare @ptyp_rr table (ptypnr int, stabnr int, ptyp varchar(60), mpehotel int)
print 'Populating @ptyp_rr...'
insert into @ptyp_rr
	
select ptypnr, stab1, ptyp, mpehotel
from #PREFIXptyp 
where stab1 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab2, ptyp, mpehotel
from #PREFIXptyp 
where stab2 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab3, ptyp, mpehotel
from #PREFIXptyp 
where stab3 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab4, ptyp, mpehotel
from #PREFIXptyp 
where stab4 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab5, ptyp, mpehotel
from #PREFIXptyp 
where stab5 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab6, ptyp, mpehotel
from #PREFIXptyp 
where stab6 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab7, ptyp, mpehotel
from #PREFIXptyp 
where stab7 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab8, ptyp, mpehotel
from #PREFIXptyp 
where stab8 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab9, ptyp, mpehotel
from #PREFIXptyp 
where stab9 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr))

union all

select ptypnr, stab10, ptyp, mpehotel
from #PREFIXptyp 
where stab10 > 0
  and ptypnr in(select ptypdetail from #PREFIXckit_dailyrates_resrates where leistacc in(select buchnr from @buchnr));


-- Temp table for the above in distinction
declare @ptyp_resrates table (ptypnr int, stabnr int, ptyp varchar(60), mpehotel int)
print 'Populating @ptyp_resrates...'
insert into @ptyp_resrates
select DISTINCT ptypnr, stabnr, ptyp, mpehotel
from @ptyp_rr;


-- Adding collected ptyp-stab references to @ptyp_stab
print 'Adding values from @ptyp_resrates to @ptyp_stab...'
insert into @ptyp_stab
select * from @ptyp_resrates

-- resrates processing END
END;


declare @ptyp_art table (stabnr int
    , bez varchar(60)
    , ref int
    , short varchar(60)
    , text varchar(100)
    , ptypnr int
    , ptyp varchar(60)
    , countnoadults int
    , countkid1 int
    , countkid2 int
    , countkid3 int
    , countkid4 int
    , combine int
    , skipfirst int
    , mpehotel int
    , groupselect varchar(5));

print 'Populating @ptyp_art...'
insert into @ptyp_art

select distinct
    st.stabnr,
    st.bez,
    ar.ref,
    ar.short,
    iif(len(isnull(md.data, '')) < 1, ar.text, md.data),
    ps.ptypnr,
    ps.ptyp,
    sc.countnoadults,
    sc.countkid1,
    sc.countkid2,
    sc.countkid3,
    sc.countkid4,
    sc.combined,
    sc.skipfirst,
    sc.mpehotel,
    sc.groupselect
from #PREFIXsplittab st 
left join #PREFIXarticles ar on st.article = ar.ref
left join @ptyp_stab ps on st.stabnr = ps.stabnr
inner join @art_sprado sc on ar.ref = sc.ref 
left join #PREFIXmetadata md on ar.ref = md.ref and sc.mpehotel = md.mpehotel and md.xkey like 'sprado_combinedtext'
where st.stabnr in(select stabnr from @ptyp_stab)
  and st.article > 0;

-- Process buch records if resrates ckit module exists/enabled
IF (EXISTS (SELECT * 
    FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_SCHEMA = '[#SCHEMA]' 
    AND TABLE_NAME = 'ckit_dailyrates_resrates'))
BEGIN
  
print 'Processing bu01...'
insert into @result
select 
    'bu01',
    bu.mpehotel,
    bu.buchnr,
    rs.statno,
    rs.stattext,
    convert(varchar, bu.datumvon, 23),
    convert(varchar, bu.datumbis, 23),
    isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
    isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
    ku.name1,
    ku.vorname,
    iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
    iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
    iif(pa.countkid1 > 0, bu.anzkin1, 0),
    iif(pa.countkid2 > 0, bu.anzkin2, 0),
    iif(pa.countkid3 > 0, bu.anzkin3, 0),
    iif(pa.countkid4 > 0, bu.anzkin4, 0),
    rr.ptypdetail,
    pa.ref,
    pa.short,
    pa.text,
    '',
    '',
    '',
    iif(bu.grpmaster > 0,
	'[' + gz.ziname + '] ' + gk.name1,
    ''),
    '',
    pa.combine,
    pa.skipfirst,
    pa.groupselect,
    checksum(
        'bu01',
        bu.mpehotel,
        bu.buchnr,
        rs.statno,
        rs.stattext,
        convert(varchar, bu.datumvon, 23),
        convert(varchar, bu.datumbis, 23),
        isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
        isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
        ku.name1,
        ku.vorname,
        iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
        iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
        iif(pa.countkid1 > 0, bu.anzkin1, 0),
        iif(pa.countkid2 > 0, bu.anzkin2, 0),
        iif(pa.countkid3 > 0, bu.anzkin3, 0),
        iif(pa.countkid4 > 0, bu.anzkin4, 0),
        rr.ptypdetail,
        pa.ref,
        pa.short,
        pa.text,
        '',
        '',
        '',
        iif(bu.grpmaster > 0,
            '[' + gz.ziname + '] ' + gk.name1,
        ''),
        '',
        pa.combine,
        pa.skipfirst,
        pa.groupselect
    ),
    getdate()

from #PREFIXckit_dailyrates_resrates rr
inner join @ptyp_art pa on rr.ptypdetail = pa.ptypnr and rr.mpehotel = pa.mpehotel
left join #PREFIXbuch bu on rr.leistacc = bu.buchnr
left join @resstat rs on bu.resstatus = rs.statno and rs.subquery like 'bu'
left join #PREFIXzimmer zi on bu.zimmernr = zi.zinr
left join #PREFIXkunden ku on bu.kundennr = ku.kdnr
left join #PREFIXbuch gm on bu.grpmaster = gm.buchnr
left join #PREFIXkunden gk on gm.kundennr = gk.kdnr
left join #PREFIXzimmer gz on gm.zimmernr = gz.zinr

  where rr.datum = @dt

    and (zi.kat not in(#ZIHIDEKAT) or bu.zimmernr = '-10')
    and bu.resstatus not in (#BUHIDESTATUS)
    and rr.mpehotel = @mpehotel
    and bu.buchnr in (select rr.leistacc from #PREFIXckit_dailyrates_resrates rr where datum = @dt)
    and pa.skipfirst = 0;


print 'Processing bu02...'
insert into @result
select 
    'bu02',
    bu.mpehotel,
    bu.buchnr,
    rs.statno,
    rs.stattext,
    convert(varchar, bu.datumvon, 23),
    convert(varchar, bu.datumbis, 23),
    isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
    isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
    ku.name1,
    ku.vorname,
    iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
    iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
    iif(pa.countkid1 > 0, bu.anzkin1, 0),
    iif(pa.countkid2 > 0, bu.anzkin2, 0),
    iif(pa.countkid3 > 0, bu.anzkin3, 0),
    iif(pa.countkid4 > 0, bu.anzkin4, 0),
    rr.ptypdetail,
    pa.ref,
    pa.short,
    pa.text,
    '',
    '',
    '',
    iif(bu.grpmaster > 0,
	'[' + gz.ziname + '] ' + gk.name1,
    ''),
    '',
    pa.combine,
    pa.skipfirst,
    pa.groupselect,
    checksum(
        'bu02',
        bu.mpehotel,
        bu.buchnr,
        rs.statno,
        rs.stattext,
        convert(varchar, bu.datumvon, 23),
        convert(varchar, bu.datumbis, 23),
        isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
        isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
        ku.name1,
        ku.vorname,
        iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
        iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
        iif(pa.countkid1 > 0, bu.anzkin1, 0),
        iif(pa.countkid2 > 0, bu.anzkin2, 0),
        iif(pa.countkid3 > 0, bu.anzkin3, 0),
        iif(pa.countkid4 > 0, bu.anzkin4, 0),
        rr.ptypdetail,
        pa.ref,
        pa.short,
        pa.text,
        '',
        '',
        '',
        iif(bu.grpmaster > 0,
            '[' + gz.ziname + '] ' + gk.name1,
        ''),
        '',
        pa.combine,
        pa.skipfirst,
        pa.groupselect
    ),
    getdate()

from #PREFIXckit_dailyrates_resrates rr
inner join @ptyp_art pa on rr.ptypdetail = pa.ptypnr and rr.mpehotel = pa.mpehotel
left join #PREFIXbuch bu on rr.leistacc = bu.buchnr
left join @resstat rs on bu.resstatus = rs.statno and rs.subquery like 'bu'
left join #PREFIXzimmer zi on bu.zimmernr = zi.zinr
left join #PREFIXkunden ku on bu.kundennr = ku.kdnr
left join #PREFIXbuch gm on bu.grpmaster = gm.buchnr
left join #PREFIXkunden gk on gm.kundennr = gk.kdnr
left join #PREFIXzimmer gz on gm.zimmernr = gz.zinr

where rr.datum = dateadd(DAY, -1, @dt)
  and (zi.kat not in(#ZIHIDEKAT) or bu.zimmernr = '-10')
  and bu.resstatus not in (#BUHIDESTATUS)
  and rr.mpehotel = @mpehotel
  and bu.buchnr in (select rr.leistacc from #PREFIXckit_dailyrates_resrates rr where datum = dateadd(DAY, -1, @dt))
  and pa.skipfirst = 1;

END

-- Process buch records if resrates ckit module exists/enabled
IF (NOT EXISTS (SELECT * 
    FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_SCHEMA = '[#SCHEMA]' 
    AND  TABLE_NAME = 'ckit_dailyrates_resrates'))
BEGIN

print 'Processing bu03...'
insert into @result
select 
    'bu03',
    bu.mpehotel,
    bu.buchnr,
    rs.statno,
    rs.stattext,
    convert(varchar, bu.datumvon, 23),
    convert(varchar, bu.datumbis, 23),
    isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
    isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
    ku.name1,
    ku.vorname,
    iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
    iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
    iif(pa.countkid1 > 0, bu.anzkin1, 0),
    iif(pa.countkid2 > 0, bu.anzkin2, 0),
    iif(pa.countkid3 > 0, bu.anzkin3, 0),
    iif(pa.countkid4 > 0, bu.anzkin4, 0),
    bu.preistyp,
    pa.ref,
    pa.short,
    pa.text,
    '',
    '',
    '',
    iif(bu.grpmaster > 0,
	'[' + gz.ziname + '] ' + gk.name1,
    ''),
    '',
    pa.combine,
    pa.skipfirst,
    pa.groupselect,
    checksum(
        'bu03',
        bu.mpehotel,
        bu.buchnr,
        rs.statno,
        rs.stattext,
        convert(varchar, bu.datumvon, 23),
        convert(varchar, bu.datumbis, 23),
        isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
        isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
        ku.name1,
        ku.vorname,
        iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
        iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
        iif(pa.countkid1 > 0, bu.anzkin1, 0),
        iif(pa.countkid2 > 0, bu.anzkin2, 0),
        iif(pa.countkid3 > 0, bu.anzkin3, 0),
        iif(pa.countkid4 > 0, bu.anzkin4, 0),
        bu.preistyp,
        pa.ref,
        pa.short,
        pa.text,
        '',
        '',
        '',
        iif(bu.grpmaster > 0,
            '[' + gz.ziname + '] ' + gk.name1,
        ''),
        '',
        pa.combine,
        pa.skipfirst,
        pa.groupselect
    ),
    getdate()

from #PREFIXbuch bu
left join @resstat rs on bu.resstatus = rs.statno and rs.subquery like 'bu'
inner join @ptyp_art pa on bu.preistyp = pa.ptypnr and bu.mpehotel = pa.mpehotel
left join #PREFIXzimmer zi on bu.zimmernr = zi.zinr
left join #PREFIXkunden ku on bu.kundennr = ku.kdnr
left join #PREFIXbuch gm on bu.grpmaster = gm.buchnr
left join #PREFIXkunden gk on gm.kundennr = gk.kdnr
left join #PREFIXzimmer gz on gm.zimmernr = gz.zinr

where bu.datumvon <= @dt
  and bu.datumbis > @dt
  and (zi.kat not in(#ZIHIDEKAT) or bu.zimmernr = '-10')
  and bu.resstatus not in (#BUHIDESTATUS)
  and bu.mpehotel = @mpehotel
  --and bu.buchnr not in (select rr.leistacc from #PREFIXckit_dailyrates_resrates rr where datum = @dt)
  and pa.skipfirst = 0;


print 'Processing bu04...'
insert into @result
select 
    'bu04',
    bu.mpehotel,
    bu.buchnr,
    rs.statno,
    rs.stattext,
    convert(varchar, bu.datumvon, 23),
    convert(varchar, bu.datumbis, 23),
    isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
    isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
    ku.name1,
    ku.vorname,
    iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
    iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
    iif(pa.countkid1 > 0, bu.anzkin1, 0),
    iif(pa.countkid2 > 0, bu.anzkin2, 0),
    iif(pa.countkid3 > 0, bu.anzkin3, 0),
    iif(pa.countkid4 > 0, bu.anzkin4, 0),
    bu.preistyp,
    pa.ref,
    pa.short,
    pa.text,
    '',
    '',
    '',
    iif(bu.grpmaster > 0,
	'[' + gz.ziname + '] ' + gk.name1,
    ''),
    '',
    pa.combine,
    pa.skipfirst,
    pa.groupselect,
    checksum(
        'bu04',
        bu.mpehotel,
        bu.buchnr,
        rs.statno,
        rs.stattext,
        convert(varchar, bu.datumvon, 23),
        convert(varchar, bu.datumbis, 23),
        isnull((select top 1 time from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (time like '[01][0-9]:[0-5][0-9]' or time like '2[0-3]:[0-5][0-9]')), '') ,
        isnull((select top 1 endtime from @art_sprado sc where sc.ref = pa.ref and sc.mpehotel = bu.mpehotel and (endtime like '[01][0-9]:[0-5][0-9]' or endtime like '2[0-3]:[0-5][0-9]')), '') ,
        ku.name1,
        ku.vorname,
        iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']',''),
        iif(pa.CountNoAdults = 0, bu.anzerw * bu.anzahl, 0),
        iif(pa.countkid1 > 0, bu.anzkin1, 0),
        iif(pa.countkid2 > 0, bu.anzkin2, 0),
        iif(pa.countkid3 > 0, bu.anzkin3, 0),
        iif(pa.countkid4 > 0, bu.anzkin4, 0),
        bu.preistyp,
        pa.ref,
        pa.short,
        pa.text,
        '',
        '',
        '',
        iif(bu.grpmaster > 0,
            '[' + gz.ziname + '] ' + gk.name1,
        ''),
        '',
        pa.combine,
        pa.skipfirst,
        pa.groupselect
    ),
    getdate()

from #PREFIXbuch bu
left join @resstat rs on bu.resstatus = rs.statno and rs.subquery like 'bu'
inner join @ptyp_art pa on bu.preistyp = pa.ptypnr and bu.mpehotel = pa.mpehotel
left join #PREFIXzimmer zi on bu.zimmernr = zi.zinr
left join #PREFIXkunden ku on bu.kundennr = ku.kdnr
left join #PREFIXbuch gm on bu.grpmaster = gm.buchnr
left join #PREFIXkunden gk on gm.kundennr = gk.kdnr
left join #PREFIXzimmer gz on gm.zimmernr = gz.zinr

where bu.datumvon <= dateadd(DAY, -1, @dt)
  and bu.datumbis >= dateadd(DAY, -0, @dt)
  and (zi.kat not in(#ZIHIDEKAT) or bu.zimmernr = '-10')
  and bu.resstatus not in (#BUHIDESTATUS)
  and bu.mpehotel = @mpehotel
  --and bu.buchnr not in (select rr.leistacc from #PREFIXckit_dailyrates_resrates rr where datum = @dt)
  and pa.skipfirst = 1;

END

print 'Processing bq01...'
insert into @result
select distinct
    'bq01',
    rm.mpehotel,
    bb.nr,
    rs.statno,
    rs.stattext,
    iif(bl.[date] = '1900-01-01', bb.datum, bl.[date]),
    iif(bl.[date] = '1900-01-01', bb.datum, bl.[date]),
    iif(bl.anzeit like '[01][0-9]:[0-5][0-9]' or bl.anzeit like '2[0-3]:[0-5][0-9]', bl.anzeit, 
		isnull((select top 1 [data] 
                        from #PREFIXmetadata 
                        where type like '5401' 
                          and xkey like 'sprado_time' 
                          and ref = bl.article 
                          and mpehotel = rm.mpehotel
			  and (data like '[01][0-9]:[0-5][0-9]' or data like '2[0-3]:[0-5][0-9]')),
                        bb.anzeit)),
    iif(bl.abzeit like '[01][0-9]:[0-5][0-9]' or bl.abzeit like '2[0-3]:[0-5][0-9]', bl.abzeit, 
		isnull((select top 1 [data] 
                        from #PREFIXmetadata 
                        where type like '5401' 
                          and xkey like 'sprado_time' 
                          and ref = bl.article 
                          and mpehotel = rm.mpehotel
			  and (data like '[01][0-9]:[0-5][0-9]' or data like '2[0-3]:[0-5][0-9]')),
                        bb.abzeit)),
    ku.name1,
    ku.vorname,
    rm.zimmernr + iif(bz.grpmaster > 0, ' [' + gz.ziname + ']',''),
    iif(bl.packref = -1, bl.anzahl, (select anzahl from #PREFIXbktleist where refnr = bl.packref)),
    0,
    0,
    0,
    0,
    0,
    sc.ref,
    sc.short,
    isnull(bl.bez, sc.text),
    '',
    isnull(iif(bl.packref > -1,(select top 1 ztext from #PREFIXbktleist where packref = -1 and refnr = bl.packref), bl.ztext), ''),
    iif(len(bb.glvaname) < 1, iif(len(bb.glvaname1) < 1, '', 'VA: ' + bb.glvaname1), iif(len(bb.glvaname1) < 1, 'EV: ' + bb.glvaname, 'EV: ' + bb.glvaname + '<br>VA: ' + bb.glvaname1)),
    '',
    isnull(iif(bl.packref > -1,(select top 1 '<li>' + replace(xtext, char(13), '</li><li>') + '</li>' from #PREFIXbktleist where packref = -1 and refnr = bl.packref and len(xtext) > 1 ), bl.xtext), ''),
    sc.combined,
    sc.skipfirst,
    sc.groupselect,
    checksum(
        'bq01',
        rm.mpehotel,
        bb.nr,
        rs.statno,
        rs.stattext,
        iif(bl.[date] = '1900-01-01', bb.datum, bl.[date]),
        iif(bl.[date] = '1900-01-01', bb.datum, bl.[date]),
        iif(bl.anzeit like '[01][0-9]:[0-5][0-9]' or bl.anzeit like '2[0-3]:[0-5][0-9]', bl.anzeit, 
                    isnull((select top 1 [data] 
                            from #PREFIXmetadata 
                            where type like '5401' 
                              and xkey like 'sprado_time' 
                              and ref = bl.article 
                              and mpehotel = rm.mpehotel
                              and (data like '[01][0-9]:[0-5][0-9]' or data like '2[0-3]:[0-5][0-9]')),
                            bb.anzeit)),
        iif(bl.abzeit like '[01][0-9]:[0-5][0-9]' or bl.abzeit like '2[0-3]:[0-5][0-9]', bl.abzeit, 
                    isnull((select top 1 [data] 
                            from #PREFIXmetadata 
                            where type like '5401' 
                              and xkey like 'sprado_time' 
                              and ref = bl.article 
                              and mpehotel = rm.mpehotel
                              and (data like '[01][0-9]:[0-5][0-9]' or data like '2[0-3]:[0-5][0-9]')),
                            bb.abzeit)),
        ku.name1,
        ku.vorname,
        rm.zimmernr + iif(bz.grpmaster > 0, ' [' + gz.ziname + ']',''),
        iif(bl.packref = -1, bl.anzahl, (select anzahl from #PREFIXbktleist where refnr = bl.packref)),
        0,
        0,
        0,
        0,
        0,
        sc.ref,
        sc.short,
        isnull(bl.bez, sc.text),
        '',
        isnull(iif(bl.packref > -1,(select top 1 ztext from #PREFIXbktleist where packref = -1 and refnr = bl.packref), bl.ztext), ''),
        iif(len(bb.glvaname) < 1, iif(len(bb.glvaname1) < 1, '', 'VA: ' + bb.glvaname1), iif(len(bb.glvaname1) < 1, 'EV: ' + bb.glvaname, 'EV: ' + bb.glvaname + '<br>VA: ' + bb.glvaname1)),
        '',
        isnull(iif(bl.packref > -1,(select top 1 '<li>' + replace(xtext, char(13), '</li><li>') + '</li>' from #PREFIXbktleist where packref = -1 and refnr = bl.packref and len(xtext) > 1 ), bl.xtext), ''),
        sc.combined,
        sc.skipfirst,
        sc.groupselect
    ),
    getdate()

from #PREFIXbktleist bl
left join @art_sprado sc on bl.article = sc.ref
left join #PREFIXbktbuch bb on (iif(bl.eventnr = -1, (select top 1 bx.eventnr from #PREFIXbktleist bx where bx.packref = bl.packref and bx.eventnr > -1), bl.eventnr) = bb.nr or 
					       iif(bl.eventnr = -1, (select top 1 bx.eventnr from #PREFIXbktleist bx where bx.packref = bl.packref and bx.eventnr > -1), bl.eventnr) = bb.gesnr) and 
					      bb.datum = bl.date
-- 20220916 -- left join #PREFIXbktbuch bb on (bl.eventnr = bb.nr or bl.eventnr = bb.gesnr) and bb.datum = bl.date
left join @resstat rs on bb.status = rs.statno and rs.subquery like 'bq'
left join #PREFIXkunden ku on bb.kundennr = ku.kdnr
inner join #PREFIXroom rm on bb.zimmernr = rm.refnr
left join #PREFIXbktbuzu bz on (select top 1 bx.nr from #PREFIXbktbuch bx where bx.gesnr = bl.nrorgnr and bx.mainroom = 1) = bz.nr and bz.modus = 5
-- 20220916 -- left join #PREFIXbktbuzu bz on bl.eventnr = bz.nr and bz.modus = 5
left join #PREFIXbuch gm on bz.grpmaster = gm.buchnr
left join #PREFIXkunden gk on gm.kundennr = gk.kdnr
left join #PREFIXzimmer gz on gm.zimmernr = gz.zinr

where bb.datum = @dt
  and bl.article in(select ref from @art_sprado)
  and iif(bl.eventnr = -1, (select top 1 bx.eventnr from #PREFIXbktleist bx where bx.packref = bl.packref and bx.eventnr > -1), bl.eventnr) > 0
-- 20220916 -- and bl.eventnr > 0  
  and rm.mpehotel = @mpehotel
  and bb.status not in(#BQHIDESTATUS);


print 'Processing fl01...'
insert into @result
select 
    *,
    checksum(*),
    getdate()
from (
    select distinct
        'fl01' subquery,
        bu.mpehotel,
        fl.buchnr bookref,
        rs.statno,
        rs.stattext,
        fl.abdatum date1,
        bu.datumbis date2,
        iif(substring(fl.text, 1, 2) = '//' 
            and substring(fl.text, 8, 2) = '//' 
            and substring(fl.text, 3, 5) like  '[01][0-9]:[0-5][0-9]' or substring(fl.text, 3, 5) like '2[0-3]:[0-5][0-9]',
            substring(fl.text, 3, 5), iif(sc.time COLLATE Latin1_General_CI_AS like '[01][0-9]:[0-5][0-9]' or substring(fl.text, 3, 5) like '2[0-3]:[0-5][0-9]',
            sc.time COLLATE Latin1_General_CI_AS, fl.uhrzeit)) time1,
        '' time2,
        ku.name1 guest1,
        ku.vorname guest2,
        iif(zi.kat = 1, 'Account ', 'Room ') + isnull(zi.ziname, 'not assigned') + iif(bu.grpmaster > 0, ' [' + gz.ziname + ']','') location,
        case fl.rhythmus
            when 0 then fl.anzahl --Daily
            when 1 then iif(@dt = bu.datumvon, fl.anzahl, NULL) --Date of arrival
            when 2 then iif(@dt = bu.datumbis, fl.anzahl, NULL) --Date of departure
            when 3 then iif(datepart(dw, @dt) = 2,fl.anzahl, NULL) --Mondays
            when 4 then iif(datepart(dw, @dt) = 3,fl.anzahl, NULL) --Tuesdays
            when 5 then iif(datepart(dw, @dt) = 4,fl.anzahl, NULL) --Wednesdays
            when 6 then iif(datepart(dw, @dt) = 5,fl.anzahl, NULL) --Thursdays
            when 7 then iif(datepart(dw, @dt) = 6,fl.anzahl, NULL) --Fridays
            when 8 then iif(datepart(dw, @dt) = 7,fl.anzahl, NULL) --Saturdays
            when 9 then iif(datepart(dw, @dt) = 1,fl.anzahl, NULL) --Sundays
            when 10 then iif(@dt = eomonth(@dt), fl.anzahl, NULL) --Last day of month
            when 11 then iif(@dt = dateadd(month, datediff(month, 0, @dt), 0), fl.anzahl, NULL) --First day of month
            when 12 then iif(@dt = dateadd(month, datediff(month, 0, @dt), 14), fl.anzahl, NULL) --Day 15
            when 13 then iif(datepart(dw, @dt) in(1, 7),fl.anzahl, NULL) --Sat/Sun
            when 14 then iif(datepart(dw, @dt) in(1, 6, 7),fl.anzahl, NULL) --Fri/Sat/Sun
            when 15 then iif(datepart(dw, @dt) in(2, 3, 4, 5),fl.anzahl, NULL) --Mon/Tue/Wed/Thu
            when 16 then iif(datepart(dw, @dt) between 2 and 6,fl.anzahl, NULL) --Mon/Tue/Wed/Thu/Fri
            when 17 then iif(datepart(dw, @dt) between 2 and 7,fl.anzahl, NULL) --Mon/Tue/Wed/Thu/Fri/Sat
            when 18 then iif(@dt = fl.abdatum, fl.anzahl, NULL) --Once (on specified date)
            when 19 then iif(datepart(dw, @dt) in(6, 7),fl.anzahl, NULL) --Fri/Sat
            when 20 then iif(dateadd(DAY, 1, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 2
            when 21 then iif(dateadd(DAY, 2, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 3
            when 22 then iif(dateadd(DAY, 3, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 4
            when 23 then iif(dateadd(DAY, 4, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 5
            when 24 then iif(dateadd(DAY, 5, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 6
            when 25 then iif(dateadd(DAY, 6, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 7
            when 26 then iif(dateadd(DAY, 7, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 8
            when 27 then iif(dateadd(DAY, 8, bu.datumvon) = @dt, fl.anzahl, NULL) --Day 9
            when 28 then iif(dateadd(DAY, 9, bu.datumvon) = @dt, fl.anzahl, NULL) --Day10
            when 29 then iif(dateadd(DAY, 10, bu.datumvon) = @dt, fl.anzahl, NULL) --Day11
            when 30 then iif(dateadd(DAY, 11, bu.datumvon) = @dt, fl.anzahl, NULL) --Day12
            when 31 then iif(dateadd(DAY, 12, bu.datumvon) = @dt, fl.anzahl, NULL) --Day13
            when 32 then iif(dateadd(DAY, 13, bu.datumvon) = @dt, fl.anzahl, NULL) --Day14
            when 33 then iif(dateadd(DAY, 14, bu.datumvon) = @dt, fl.anzahl, NULL) --Day15
            when 34 then iif(dateadd(DAY, 15, bu.datumvon) = @dt, fl.anzahl, NULL) --Day16
            when 35 then iif(dateadd(DAY, 16, bu.datumvon) = @dt, fl.anzahl, NULL) --Day17
            when 36 then iif(dateadd(DAY, 17, bu.datumvon) = @dt, fl.anzahl, NULL) --Day18
            when 37 then iif(dateadd(DAY, 18, bu.datumvon) = @dt, fl.anzahl, NULL) --Day19
            when 38 then iif(dateadd(DAY, 19, bu.datumvon) = @dt, fl.anzahl, NULL) --Day20
            when 39 then iif(dateadd(DAY, 20, bu.datumvon) = @dt, fl.anzahl, NULL) --Day21
            when 40 then iif(datepart(dw, @dt) between 3 and 6,fl.anzahl, NULL) --Tue/Wed/Thu/Fri
            when 41 then iif(datepart(dw, @dt) in(2, 4, 5, 6),fl.anzahl, NULL) --Mon/Wed/Thu/Fri
            when 42 then iif(datepart(dw, @dt) in(2, 3, 5, 6),fl.anzahl, NULL) --Mon/Tue/Thu/Fri
            when 43 then iif(datepart(dw, @dt) in(2, 3, 4, 6),fl.anzahl, NULL) --Mon/Tue/Wed/Fri
            when 44 then iif(day(bu.datumvon) = day(@dt), fl.anzahl, NULL) --Monthly based on arrival date
            else NULL
        end * bu.anzahl adults,
        0 children1,
        0 children2,
        0 children3,
        0 children4,
        0 ptyp,
        sc.ref artref,
        sc.short artshort,
        iif(substring(fl.text, 1, 2) = '//' 
            and substring(fl.text, 8, 2) = '//' 
            and substring(fl.text, 3, 5) like  '[01][0-9]:[0-5][0-9]' or substring(fl.text, 3, 5) like '2[0-3]:[0-5][0-9]', 
                substring(fl.text, 10, len(fl.text) - 9), fl.text) arttext,
        iif(iif(substring(fl.text, 1, 2) = '//'
            and substring(fl.text, 8, 2) = '//'
            and substring(fl.text, 3, 5) like '[01][0-9]:[0-5][0-9]' or substring(fl.text, 3, 5) like '2[0-3]:[0-5][0-9]',
                substring(fl.text, 10, len(fl.text) - 9), fl.text) = sc.text COLLATE Latin1_General_CI_AS, '', sc.text) data1,
        '' data2,
        '' data3,
        '' data4,
        case fl.rhythmus
            when 0 then 'Daily'
            when 1 then 'Date of arrival'
            when 2 then 'Date of departure'
            when 3 then 'Mondays'
            when 4 then 'Tuesdays'
            when 5 then 'Wednesdays'
            when 6 then 'Thursdays'
            when 7 then 'Fridays'
            when 8 then 'Saturdays'
            when 9 then 'Sundays'
            when 10 then 'Last day of month'
            when 11 then 'First day of month'
            when 12 then 'Day 15'
            when 13 then 'Sat/Sun'
            when 14 then 'Fri/Sat/Sun'
            when 15 then 'Mon/Tue/Wed/Thu'
            when 16 then 'Mon/Tue/Wed/Thu/Fri'
            when 17 then 'Mon/Tue/Wed/Thu/Fri/Sat'
            when 18 then 'Once (on specified date)'
            when 19 then 'Fri/Sat'
            when 20 then 'Day 2'
            when 21 then 'Day 3'
            when 22 then 'Day 4'
            when 23 then 'Day 5'
            when 24 then 'Day 6'
            when 25 then 'Day 7'
            when 26 then 'Day 8'
            when 27 then 'Day 9'
            when 28 then 'Day10'
            when 29 then 'Day11'
            when 30 then 'Day12'
            when 31 then 'Day13'
            when 32 then 'Day14'
            when 33 then 'Day15'
            when 34 then 'Day16'
            when 35 then 'Day17'
            when 36 then 'Day18'
            when 37 then 'Day19'
            when 38 then 'Day20'
            when 39 then 'Day21'
            when 40 then 'Tue/Wed/Thu/Fri'
            when 41 then 'Mon/Wed/Thu/Fri'
            when 42 then 'Mon/Tue/Thu/Fri'
            when 43 then 'Mon/Tue/Wed/Fri'
            when 44 then 'Monthly based on arrival date'
            else NULL
        end data5,
        isnull(sc.combined, 0) combine,
        isnull(sc.skipfirst, 0) skipfirst,
        sc.groupselect


    from #PREFIXbuch bu
    left join @resstat rs on bu.resstatus = rs.statno and rs.subquery like 'bu'
    left join #PREFIXkunden ku on bu.kundennr = ku.kdnr
    left join #PREFIXzimmer zi on bu.zimmernr = zi.zinr
    left join #PREFIXfixleist fl on bu.buchnr = fl.buchnr 
    inner join @art_sprado sc on fl.article = sc.ref and sc.mpehotel = bu.mpehotel
    left join #PREFIXbuch gm on bu.grpmaster = gm.buchnr
    left join #PREFIXkunden gk on gm.kundennr = gk.kdnr
    left join #PREFIXzimmer gz on gm.zimmernr = gz.zinr

    where bu.datumvon <= @dt
      and bu.datumbis >= dateadd(DAY, iif(fl.rhythmus = 2, 0, 1), @dt)
      and bu.mpehotel = @mpehotel
      and (zi.kat not in(#ZIHIDEKAT) or bu.zimmernr = '-10')

) fixleist
where adults is not null;

#RESULT