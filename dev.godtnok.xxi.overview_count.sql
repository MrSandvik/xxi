select 
	count(*) count
from protel.proteluser.bktbuch bbu
left join protel.proteluser.metadata mtd on bbu.leistacc = mtd.ref and mtd.type = 3000 and mtd.xkey = 'display_show_global'
left join protel.proteluser.room rom on bbu.zimmernr = rom.refnr
where bbu.datum = '¤date'
  and rom.mpehotel = ¤mpehotel
  and bbu.status not in(¤bqHideStatus)
  and (mtd.data is null or mtd.data in(0, 2))
  ¤DYNAMIC