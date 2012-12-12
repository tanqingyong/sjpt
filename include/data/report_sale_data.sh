#销售运营数据,日报
datetime=`date -d"-1 day" +%Y-%m-%d`

mysql  -uyangzhimin -p'!zW-ya9:' --default-character-set=utf8 >/home/yangzhimin/data/tmp/web_country_day_pvuvip.log  <<EOF
use sinan;
select id,data_date,total_pv,total_uv,total_ip,index_pv,index_uv,index_ip,goods_pv,goods_uv,goods_ip  from web_country_day_pvuvip where data_date='$datetime';

EOF


mysql  -uyangzhimin -p'!zW-ya9:' --default-character-set=utf8 >/home/yangzhimin/data/tmp/buss_country_day_order.log  <<EOF
use sinan;
select * from buss_country_day_order where data_date='$datetime';

EOF


mysql  -uyangzhimin -p'!zW-ya9:' --default-character-set=utf8 >/home/yangzhimin/data/tmp/webbuss_country_day_other.log  <<EOF
use sinan;
select * from webbuss_country_day_other where data_date='$datetime';

EOF


mysql  -uyangzhimin -p'!zW-ya9:' --default-character-set=utf8 >/home/yangzhimin/data/tmp/country_day_outfromdamain.log  <<EOF
use sinan;
select * from country_day_outfromdomain where data_date='$datetime';

EOF


mysql  -uyangzhimin -p'!zW-ya9:' --default-character-set=utf8 >/home/yangzhimin/data/tmp/country_day_outfromurl.log  <<EOF
use sinan;
select * from country_day_outfromurl where data_date='$datetime';

EOF


mysql  -uyangzhimin -p'!zW-ya9:' --default-character-set=utf8 >/home/yangzhimin/data/tmp/country_day_frequser.log  <<EOF
use sinan;
select * from country_day_frequser  where data_date='$datetime';

EOF


mysql -h10.8.210.108 -usjpt -psjpt001 -e "use sjpt;delete from web_country_day_pvuvip where data_date='$datetime';load  data local infile '/home/yangzhimin/data/tmp/web_country_day_pvuvip.log' into table web_country_day_pvuvip ignore 1 lines;"

mysql -h10.8.210.108 -usjpt -psjpt001 -e "use sjpt;delete from buss_country_day_order where data_date='$datetime';load  data local infile '/home/yangzhimin/data/tmp/buss_country_day_order.log' into table buss_country_day_order ignore 1 lines;"

mysql -h10.8.210.108 -usjpt -psjpt001 -e "use sjpt;delete from  webbuss_country_day_other where data_date='$datetime';load  data local infile '/home/yangzhimin/data/tmp/webbuss_country_day_other.log' into table webbuss_country_day_other ignore 1 lines;"

mysql -h10.8.210.108 -usjpt -psjpt001 -e "use sjpt;delete from country_day_outfromdamain where data_date='$datetime';load  data local infile '/home/yangzhimin/data/tmp/country_day_outfromdamain.log' into table country_day_outfromdamain ignore 1 lines;"

mysql -h10.8.210.108 -usjpt -psjpt001 -e "use sjpt;delete from country_day_outfromurl where data_date='$datetime';load  data local infile '/home/yangzhimin/data/tmp/country_day_outfromurl.log' into table country_day_outfromurl ignore 1 lines;"

mysql -h10.8.210.108 -usjpt -psjpt001 -e "use sjpt;delete from country_day_frequser where data_date='$datetime';load  data local infile '/home/yangzhimin/data/tmp/country_day_frequser.log' into table country_day_frequser ignore 1 lines;"

