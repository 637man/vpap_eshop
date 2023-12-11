#!/bin/bash
# Zkopiruje lokalni kopii repozare na produkci, doda kredencialy DB
# a nastavi opravneni, aby PHP demon mohl zapisovat.
cd ~
cp -r vpap_eshop public_html
cp eshopconfiglocal.neon public_html/vpap_eshop/config/local.neon
chmod 777 public_html/vpap_eshop/log
chmod 777 public_html/vpap_eshop/temp
chmod 777 public_html/vpap_eshop/www/img/products

