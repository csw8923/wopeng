#-*- coding:utf-8 -*-
import sys
import time
import re
import urllib2
import threading
#import android
import HTMLParser
import codecs
import httplib
import json

#替换特殊字符
def escape(txt):
    '''将txt文本中的空格、&、<、>、（"）、（'）转化成对应的的字符实体，以方便在html上显示'''
    txt = txt.replace('&','&#38;')
    txt = txt.replace(' ','&#160;')
    txt = txt.replace('<','&#60;')
    txt = txt.replace('>','&#62;')
    txt = txt.replace('"','&#34;')
    txt = txt.replace('\'','&#39;')
    return txt

#过滤html内容
def strip_tags(html):
    from HTMLParser import HTMLParser
    html=html.strip()
    html=html.strip("\n")
    result=[]
    parse=HTMLParser()
    parse.handle_data=result.append
    parse.feed(html)
    parse.close()
    return "".join(result)

#正则获取指定范围
def extractData(regex, content, index=1):
    r = '0'
    p = re.compile(regex,re.U|re.S)
    m = p.search(content)
    if m:
        r = m.group(index)
    return r

#正则替换特殊字符
def replace_condition(condition):
    condition_1 = condition.replace('[','<')
    condition_2 = condition_1.replace(']','>')
    condition_3 = condition_2.replace('&quot;','"')
    condition_4 = condition_3.replace('&gt;','>')
    return condition_4

#正则替换特殊字符
def replace_redundant(redundant):
    redundant_1 = redundant.replace('<b>','')
    redundant_2 = redundant_1.replace('</b>','')
    return redundant_2

#正则替换特殊字符
def replace_content(content):
    rulexml = urllib2.urlopen("http://www.xmgbuy.com/py/s.php").read()
    s = json.loads(rulexml)
    replace =  s["replace_content"]
    target =  s["target_content"] 
    replacearray = replace.split(',')
    targetarray = target.split(',')
    s=0
    for subreplace in replacearray:
        subreplace_t = subreplace.encode('utf-8')
        subtarget_t = targetarray[s].encode('utf-8')
        content = content.replace(subreplace_t,subtarget_t)
        s=s+1
    return content

#正则获取网址数组
def extracturl(newcontent,listurl,listdomain):
    condition =  replace_condition(listurl)
    regex_url = r'%s' % condition
    urls=re.findall(regex_url,newcontent,re.M|re.I|re.S|re.U)
    listurls=""
    for i in urls:
        new = listdomain.encode('utf-8')
        if listdomain != 'null':
            domain = '%s%s' % (new,i)
        else:
            domain = '%s' % (i)
        listurls+='<li>'+domain+'</li>'
    return listurls

def do_list(url,listgz,listurl,listdomain,detail,intercept,cjurl):
    content = urllib2.urlopen(url).read()
    content_txt = escape(content)
    #采集范围设定
    condition =  replace_condition(listgz)
    regex = r'%s' % condition
    newcontent =  extractData(regex, content, 1)
    #获取网址
    newurls =  extracturl(newcontent,listurl,listdomain)

    return_msg=''
    if detail == '1':
        return_msg += content_txt
    elif intercept == '1':
        return_msg +=  newcontent
    elif cjurl == '1':
        return_msg +=  newurls
    return return_msg

def pages_article(articlepageurl,articlepage,i):
        contentpage =  urllib2.urlopen(articlepageurl.replace('[num]','%s') % i).read()
        #分页内容采集
        conditionpage =  replace_condition(articlepage)
        regex = r'%s' % conditionpage
        newcontentpage =  extractData(regex, contentpage,1)
        newcontentpage = replace_content(newcontentpage)
        if len(newcontentpage):
            return newcontentpage
        else:
            return 'null'

def do_article(url,article,articlepageurl,pagerange,articlepage,ispage):
    content = urllib2.urlopen(url).read()
    #采集范围设定
    condition =  replace_condition(article)
    regex = r'%s' % condition
    newcontent =  extractData(regex, content, 1)
    newcontent = replace_content(newcontent)
    pagecontent = ''

    if ispage == '1':
        #采集范围设定
        condition_range =  replace_condition(pagerange)
        regex_range = r'%s' % condition_range
        rangecontent =  extractData(regex_range, content, 1)
        rangecontent = replace_content(rangecontent)
        urls=re.findall(r"<a.*?href=.*?<\/a>",rangecontent,re.M|re.I|re.S|re.U)
        for i in range(1,len(urls)):
            pagecontent += pages_article(articlepageurl,articlepage,i)

    if ispage == '1':
        return newcontent + pagecontent
    else:
        return newcontent

if __name__ == '__main__':
    id = sys.argv[1]
    detail = sys.argv[2]
    intercept = sys.argv[3]
    cjurl = sys.argv[4]
    isarticle = sys.argv[5]
    rulexml = urllib2.urlopen("http://www.xmgbuy.com/py/s.php?id="+id).read()
    s = json.loads(rulexml)
if id:
    C = do_list(s["url"],s["list"],s["listurl"],s["domain"],detail,intercept,cjurl)
    if isarticle == '1':
        A = do_article(s["articleurl"],s["article"],s["articlepageurl"],s["getrange"],s["articlepage"],s["ispage"])
        print A

print C