#!/bin/bash

# Zkopiruje lokalni kopii repozare na produkci, doda kredencialy DB
# a nastavi opravneni, aby PHP demon mohl zapisovat.

mkdir -p ../public_html/vpap_eshop

cp -r app ../public_html/vpap_eshop
cp -r vendor ../public_html/vpap_eshop
cp -r www ../public_html/vpap_eshop
cp -r .htaccess ../public_html/vpap_eshop

cp ../eshopconfiglocal.neon ../public_html/vpap_eshop/config/local.neon
cp ../eshopconfigcommon.neon ../public_html/vpap_eshop/config/common.neon

cd ../public_html/vpap_eshop/
mkdir -p log
chmod 777 log
mkdir -p temp
chmod 777 temp
chmod 777 www/img/products

