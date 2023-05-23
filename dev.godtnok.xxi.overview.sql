select 
    rom.zimmernr,
    rom.refnr,
    isnull((select iif(len(data) <= 1, null, data) from [¤DATABASE].[¤SCHEMA].metadata where xkey like 'display_customer_global' and type = 3000 and ref = bbu.leistacc) , kun.name1) name1,
    convert(varchar, bbu.datum, 23) datum,
    bbu.anzeit,
    bbu.abzeit
from [¤DATABASE].[¤SCHEMA].bktbuch bbu 
left join [¤DATABASE].[¤SCHEMA].room rom on bbu.zimmernr = rom.refnr
left join [¤DATABASE].[¤SCHEMA].kunden kun on bbu.kundennr = kun.kdnr
left join [¤DATABASE].[¤SCHEMA].metadata mtd on bbu.leistacc = mtd.ref and mtd.type = 3000 and mtd.xkey = 'display_show_global'
where bbu.datum = '¤date'
  and bbu.status not in(¤bqHideStatus)
  and rom.mpehotel = ¤mpehotel
  and (mtd.data is null or mtd.data in(0, 2))
  ¤DYNAMIC
order by 
    anzeit, 
    name1
offset ¤offset rows fetch next ¤maxRows rows only

