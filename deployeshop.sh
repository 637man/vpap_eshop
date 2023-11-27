#!/bin/bash

cd ~
cp -r vpap_eshop public_html
cp eshopconfiglocal.neon public_html/vpap_eshop/config/local.neon
chmod 777 public_html/vpap_eshop/log
chmod 777 public_html/vpap_eshop/temp
chmod 777 public_html/vpap_eshop/www/img/products

