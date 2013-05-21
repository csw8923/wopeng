#-*- coding:utf-8 -*-
import os
import sys
if sys.getdefaultencoding() != 'utf-8':
    reload(sys)
    sys.setdefaultencoding('gb2312')
import time
import re
import urllib2
import threading
#import android
import HTMLParser
import codecs
import json
import httplib, urllib

#多线程下载处理
class MyThreadrule(threading.Thread):
    def __init__(self,part,url,listgz,listurl,listdomain,listtitle,patterns,replacements,universal,article,dpatterns,dreplacements,approval,code):
        threading.Thread.__init__(self)
        self.part=part
        self.url=url
        self.listgz=listgz
        self.listurl=listurl
        self.listdomain=listdomain
        self.listtitle=listtitle
        self.patterns=patterns
        self.replacements=replacements
        self.universal=universal
        self.article=article
        self.dpatterns=dpatterns
        self.dreplacements=dreplacements
        self.approval=approval
        self.code=code

    def run(self):
        #采集内容页面
        C = do_list(self.part,self.url,self.listgz,self.listurl,self.listdomain,self.listtitle,self.patterns,self.replacements,self.universal,self.article,self.dpatterns,self.dreplacements,self.approval,self.code)
        #操作锁
        file_object = open('lock','w')
        file_object.writelines("lock")
        file_object.close()
        #操作锁
        print C
        time.sleep(5)

#多线程下载处理
class MyThread(threading.Thread):
    def __init__(self,threadname,classname,patterns,replacements,article,dpatterns,dreplacements,approval,code):
        threading.Thread.__init__(self)
        self.name=threadname
        self.fromclass=classname
        self.patterns=patterns
        self.replacements=replacements
        self.fromarticle=article
        self.dpatterns=dpatterns
        self.dreplacements=dreplacements
        self.approval=approval
        self.code=code

    def run(self):
        #采集内容页面
        #print self.name
        A = do_article(self.fromclass,self.name,self.patterns,self.replacements,self.fromarticle,self.dpatterns,self.dreplacements,self.approval,self.code)
        print A
        time.sleep(0.01)

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
    replace = names[int(id)][16]
    target = names[int(id)][17]
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
def extracturl(part,newcontent,listurl,listdomain,patterns,replacements,article,dpatterns,dreplacements,approval,code):
    condition =  replace_condition(listurl)
    regex_url = r'%s' % condition
    urls=re.findall(regex_url,newcontent,re.M|re.I|re.S|re.U)
    #listurls=""
    op=0
    for i in urls:
        new = listdomain.encode('utf-8')
        if new == 'null':
            domain = i
        else:
            domain = '%s%s' % (new,i)
        my = MyThread(domain,part,patterns,replacements,article,dpatterns,dreplacements,approval,code)
        my.start()
        my.join()
        op+=1
        if op == len(urls):
            os.remove('lock')
        print op
        #listurls+='<li>'+domain+'</li>'
    return len(urls)

#快速通用获取标题和链接
def do_universal(part,newcontent,listdomain,patterns,replacements,article,dpatterns,dreplacements,approval,code):
    domainhost = listdomain.encode('utf-8')
    ss=newcontent.replace(" "," ")
    urls=re.findall(r"<a.*?href=.*?<\/a>",ss,re.M|re.I|re.S|re.U)
    #listuniversal=""
    op=0
    for i in urls:
        regex = r'href="(.*?)"'
        newurl =  extractData(regex, i, 1)
        my = MyThread(domainhost+newurl,part,patterns,replacements,article,dpatterns,dreplacements,approval,code)
        my.start()
        my.join()
        op+=1
        if op == len(urls):
            os.remove('lock')
        print op
        # 截取标题
        #regex_title = r'">(.*?)</a>'
        #newtitle =  extractData(regex_title, i, 1)
        #listuniversal+='<li>'+domainhost+newurl+newtitle+'</li>'
        # 截取标题
    return len(urls)

#获取文章 并录入数据库
def do_article(part,url,patterns,replacements,article,dpatterns,dreplacements,approval,code):
    content = urllib2.urlopen(url).read()
    if code == '1':
    #数据转换
        content = content.encode('utf-8')
    #数据转换
    #采集范围设定
    article_t = article.decode('gb2312')
    condition =  replace_condition(article_t)
    regex = r'%s' % condition
    newcontent =  extractData(regex, content, 1)
    #采集范围设定

    #获取标题
    t_regex = r'<title>(.*?)</title>'
    titlecontent =  extractData(t_regex, content, 1)
    #print titlecontent
    #获取标题
    patternssarray = patterns.split(',')
    replacementssarray = replacements.split(',')
    op=0
    for th in patternssarray:
        th_t = th.encode('utf-8')
        subtarget_t = replacementssarray[op].encode('utf-8')
        titlecontent = titlecontent.replace(th_t,subtarget_t)
        op+=1

    #替换内容
    dpatternssarray = dpatterns.split(',')
    dreplacementssarray = dreplacements.split(',')
    opt=0
    for optdata in dpatternssarray:
        tht_t = optdata.encode('utf-8')
        subtarget_t_c = dreplacementssarray[opt].encode('utf-8')
        newcontent = newcontent.replace(tht_t,subtarget_t_c)
        opt+=1
    nowtime = time.time()
    part = part.encode('utf-8')
    part = part.replace("┝","")

    httpClient = None
    try:
        params = urllib.urlencode({'part': part, 'titlecontent': titlecontent, 'newcontent': newcontent, 'nowtime': nowtime, 'url': url, 'approval': approval})
        headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "text/plain"}
        httpClient = httplib.HTTPConnection("www.xmgbuy.com", 80, timeout=30)
        httpClient.request("POST", "/py/test.php", params, headers)
        response = httpClient.getresponse()
        print response.status
        print response.reason
        print response.read()
        print response.getheaders() #获取头信息
    except Exception, e:
        print e
    finally:
        if httpClient:
            httpClient.close()


    return titlecontent

#处理路径
def do_list(part,url,listgz,listurl,listdomain,listtitle,patterns,replacements,universal,article,dpatterns,dreplacements,approval,code):
    content = urllib2.urlopen(url).read()
    #采集范围设定
    condition =  replace_condition(listgz)
    regex = r'%s' % condition
    newcontent =  extractData(regex, content, 1)
    re = ''
    if universal == 1:
        s = do_universal(part,newcontent,listdomain,patterns,replacements,article,dpatterns,dreplacements,approval,code)
        re = newcontent+s
    else:
        #获取网址
        newurls =  extracturl(part,newcontent,listurl,listdomain,patterns,replacements,article,dpatterns,dreplacements,approval,code)
        #获取标题
        #newtitles =  extracttitle(newcontent,listtitle)
        re = newurls
    return re

if __name__ == '__main__':
    id = sys.argv[1]
    lock = os.path.isfile("lock")
    print lock
    rulexml = urllib2.urlopen("http://www.xmgbuy.com/py/s.php?id="+id).read()
    s = json.loads(rulexml)
    #print s["url"]
    if lock == 0:
        #操作锁
        file_object = open('lock','w')
        file_object.writelines("lock")
        file_object.close()
        #操作锁
        if id != 'd':
            # #i = int(id)
            # i = 0
            part = s["part"]
            url = s["url"]
            listgz = s["list"]
            listurl = s["listurl"]
            listdomain = s["domain"]
            listtitle = s["gettitle"]
            patterns = s["patterns"]
            replacements = s["replacements"]
            universal = s["universal"]
            article = s["article"]
            dpatterns = s["replace_content"]
            dreplacements = s["target_content"]
            approval = s["approval"]
            code = s["code"]
            listgz = listgz.replace('[list]','(.*?)')
            listurl = listurl.replace('[link]','(.*?)')
            listtitle = listtitle.replace('[title]','(.*?)')
            article = article.replace('[article]','(.*?)')
            C = do_list(part,url,listgz,listurl,listdomain,listtitle,patterns,replacements,universal,article,dpatterns,dreplacements,approval,code)
            print C
    else:
        print 'opening'
