#-*-coding: utf-8-*-

from scrapy import Spider
#from spider.items import FinalWorkItems


class TrabajoFinalSpider(Spider):
    name = 'TrabajoFinal'
    start_urls = ["http://pastebin.com/archive/c/",
        "http://pastebin.com/archive/cpp/",
        "http://pastebin.com/archive/csharp/",
        "http://pastebin.com/archive/clojure/",
        "http://pastebin.com/archive/coffeescript/",
        "http://pastebin.com/archive/css/",
        "http://pastebin.com/archive/diff/",
        "http://pastebin.com/archive/erlang/",
        "http://pastebin.com/archive/haskell/",
        "http://pastebin.com/archive/java/",
        "http://pastebin.com/archive/javascript/",
        "http://pastebin.com/archive/json/",
        "http://pastebin.com/archive/lua/",
        "http://pastebin.com/archive/php/",
        "http://pastebin.com/archive/perl/",
        "http://pastebin.com/archive/python/",
        "http://pastebin.com/archive/ruby/",
        "http://pastebin.com/archive/rust/",
        "http://pastebin.com/archive/scala/",
        "http://pastebin.com/archive/smalltalk/",
        "http://pastebin.com/archive/sql/",
        "http://pastebin.com/archive/vbscript/",
        "http://pastebin.com/archive/xml/",
    ]

    def parse(self, response):
        filename = response.url.split("/")[-2] + '.html'
        with open(filename, 'wb') as f:
            f.write(response.body)
