![alt tag](https://travis-ci.com/kamilhurajt/phalcon-crawler.svg?branch=master)

# phalcon-crawler

## Requirements


* docker
* php 7.2+
* phalcon framework 3.x

## How to run

``docker-compose up``

## API demo

Single site analytics

*Request*: POST

*Post data*: url: https://url.com

*URL:* https://aacrawler.w-tt.eu/api/stats/fetch-single 

Multiple site analytics:

This requrest will fetch up to 5 url and provide analytis for each url and averge calculated from all

*Request*: POST

*Post data*: urls  ["https://url.com", "https://another.com"]

*URL:* https://aacrawler.w-tt.eu/api/stats/fetch-multi