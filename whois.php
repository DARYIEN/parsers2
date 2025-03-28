<?php
// Устанавливаем не ограниченное время выполнения скрипта
set_time_limit(0);
$servers = array(
    array("ac","whois.nic.ac","No match"),
    array("ac.cn","whois.cnnic.net.cn","No entries found"),
    array("ac.jp","whois.nic.ad.jp","No match"),
    array("ac.uk","whois.ja.net","no entries"),
    array("ad.jp","whois.nic.ad.jp","No match"),
    array("adm.br","whois.nic.br","No match"),
    array("adv.br","whois.nic.br","No match"),
    array("aero","whois.information.aero","is available"),
    array("ag","whois.nic.ag","does not exist"),
    array("agr.br","whois.nic.br","No match"),
    array("ah.cn","whois.cnnic.net.cn","No entries found"),
    array("al","whois.ripe.net","No entries found"),
    array("am.br","whois.nic.br","No match"),
    array("arq.br","whois.nic.br","No match"),
    array("at","whois.nic.at","nothing found"),
    array("au","whois.aunic.net","No Data Found"),
    array("art.br","whois.nic.br","No match"),
    array("as","whois.nic.as","Domain Not Found"),
    array("asn.au","whois.aunic.net","No Data Found"),
    array("ato.br","whois.nic.br","No match"),
    array("be","whois.geektools.com","No such domain"),
    array("bg","whois.digsys.bg","does not exist"),
    array("bio.br","whois.nic.br","No match"),
    array("biz","whois.biz","Not found"),
    array("bj.cn","whois.cnnic.net.cn","No entries found"),
    array("bmd.br","whois.nic.br","No match"),
    array("br","whois.registro.br","No match"),
    array("ca","whois.cira.ca","Status: AVAIL"),
    array("cc","whois.nic.cc","No match"),
    array("cd","whois.cd","No match"),
    array("ch","whois.nic.ch","We do not have an entry"),
    array("cim.br","whois.nic.br","No match"),
    array("ck","whois.ck-nic.org.ck","No entries found"),
    array("cl","whois.nic.cl","no existe"),
    array("cn","whois.cnnic.net.cn","No entries found"),
    array("cng.br","whois.nic.br","No match"),
    array("cnt.br","whois.nic.br","No match"),
    array("com","whois.verisign-grs.net","No match"),
    array("com.au","whois.aunic.net","No Data Found"),
    array("com.br","whois.nic.br","No match"),
    array("com.cn","whois.cnnic.net.cn","No entries found"),
    array("com.eg","whois.ripe.net","No entries found"),
    array("com.hk","whois.hknic.net.hk","No Match for"),
    array("com.mx","whois.nic.mx","Nombre del Dominio"),
    array("com.ru","whois.ripn.ru","No entries found"),
    array("com.tw","whois.twnic.net","NO MATCH TIP"),
    array("conf.au","whois.aunic.net","No entries found"),
    array("co.jp","whois.nic.ad.jp","No match"),
    array("co.uk","whois.nic.uk","No match for"),
    array("cq.cn","whois.cnnic.net.cn","No entries found"),
    array("csiro.au","whois.aunic.net","No Data Found"),
    array("cx","whois.nic.cx","No match"),
    array("cz","whois.nic.cz","No data found"),
    array("de","whois.denic.de","No entries found"),
    array("dk","whois.dk-hostmaster.dk","No entries found"),
    array("ecn.br","whois.nic.br","No match"),
    array("ee","whois.eenet.ee","NOT FOUND"),
    array("edu","whois.verisign-grs.net","No match"),
    array("edu.au","whois.aunic.net","No Data Found"),
    array("edu.br","whois.nic.br","No match"),
    array("eg","whois.ripe.net","No entries found"),
    array("es","whois.ripe.net","No entries found"),
    array("esp.br","whois.nic.br","No match"),
    array("etc.br","whois.nic.br","No match"),
    array("eti.br","whois.nic.br","No match"),
    array("eun.eg","whois.ripe.net","No entries found"),
    array("emu.id.au","whois.aunic.net","No Data Found"),
    array("eng.br","whois.nic.br","No match"),
    array("far.br","whois.nic.br","No match"),
    array("fi","whois.ripe.net","No entries found"),
    array("fj","whois.usp.ac.fj",""),
    array("fj.cn","whois.cnnic.net.cn","No entries found"),
    array("fm.br","whois.nic.br","No match"),
    array("fnd.br","whois.nic.br","No match"),
    array("fo","whois.ripe.net","no entries found"),
    array("fot.br","whois.nic.br","No match"),
    array("fst.br","whois.nic.br","No match"),
    array("fr","whois.nic.fr","No entries found"),
    array("g12.br","whois.nic.br","No match"),
    array("gd.cn","whois.cnnic.net.cn","No entries found"),
    array("ge","whois.ripe.net","no entries found"),
    array("ggf.br","whois.nic.br","No match"),
    array("gl","whois.ripe.net","no entries found"),
    array("gr","whois.ripe.net","no entries found"),
    array("gr.jp","whois.nic.ad.jp","No match"),
    array("gs","whois.adamsnames.tc","is not registered"),
    array("gs.cn","whois.cnnic.net.cn","No entries found"),
    array("gov.au","whois.aunic.net","No Data Found"),
    array("gov.br","whois.nic.br","No match"),
    array("gov.cn","whois.cnnic.net.cn","No entries found"),
    array("gov.hk","whois.hknic.net.hk","No Match for"),
    array("gob.mx","whois.nic.mx","Nombre del Dominio"),
    array("gs","whois.adamsnames.tc","is not registered"),
    array("gz.cn","whois.cnnic.net.cn","No entries found"),
    array("gx.cn","whois.cnnic.net.cn","No entries found"),
    array("he.cn","whois.cnnic.net.cn","No entries found"),
    array("ha.cn","whois.cnnic.net.cn","No entries found"),
    array("hb.cn","whois.cnnic.net.cn","No entries found"),
    array("hi.cn","whois.cnnic.net.cn","No entries found"),
    array("hl.cn","whois.cnnic.net.cn","No entries found"),
    array("hn.cn","whois.cnnic.net.cn","No entries found"),
    array("hm","whois.registry.hm","(null)"),
    array("hk","whois.hknic.net.hk","No Match for"),
    array("hk.cn","whois.cnnic.net.cn","No entries found"),
    array("hu","whois.ripe.net","MAXCHARS:500"),
    array("id.au","whois.aunic.net","No Data Found"),
    array("ie","whois.domainregistry.ie","no match"),
    array("ind.br","whois.nic.br","No match"),
    array("imb.br","whois.nic.br","No match"),
    array("inf.br","whois.nic.br","No match"),
    array("info","whois.afilias.info","Not found"),
    array("info.au","whois.aunic.net","No Data Found"),
    array("it","whois.nic.it","No entries found"),
    array("idv.tw","whois.twnic.net","NO MATCH TIP"),
    array("int","whois.iana.org","not found"),
    array("is","whois.isnic.is","No entries found"),
    array("il","whois.isoc.org.il","No data was found"),
    array("jl.cn","whois.cnnic.net.cn","No entries found"),
    array("jor.br","whois.nic.br","No match"),
    array("jp","whois.nic.ad.jp","No match"),
    array("js.cn","whois.cnnic.net.cn","No entries found"),
    array("jx.cn","whois.cnnic.net.cn","No entries found"),
    array("kr","whois.krnic.net","is not registered"),
    array("la","whois.nic.la","NO MATCH"),
    array("lel.br","whois.nic.br","No match"),
    array("li","whois.nic.ch","We do not have an entry"),
    array("lk","whois.nic.lk","No domain registered"),
    array("ln.cn","whois.cnnic.net.cn","No entries found"),
    array("lt","ns.litnet.lt","No matches found"),
    array("lu","whois.dns.lu","No entries found"),
    array("lv","whois.ripe.net","no entries found"),
    array("ltd.uk","whois.nic.uk","No match for"),
    array("mat.br","whois.nic.br","No match"),
    array("mc","whois.ripe.net","No entries found"),
    array("med.br","whois.nic.br","No match"),
    array("mil","whois.nic.mil","No match"),
    array("mil.br","whois.nic.br","No match"),
    array("mn","whois.nic.mn","Domain not found"),
    array("mo.cn","whois.cnnic.net.cn","No entries found"),
    array("ms","whois.adamsnames.tc","is not registered"),
    array("mus.br","whois.nic.br","No match"),
    array("mx","whois.nic.mx","Nombre del Dominio"),
    array("name","whois.nic.name","No match"),
    array("ne.jp","whois.nic.ad.jp","No match"),
    array("net","whois.verisign-grs.net","No match"),
    array("net.au","whois.aunic.net","No Data Found"),
    array("net.br","whois.nic.br","No match"),
    array("net.cn","whois.cnnic.net.cn","No entries found"),
    array("net.eg","whois.ripe.net","No entries found"),
    array("net.hk","whois.hknic.net.hk","No Match for"),
    array("net.lu","whois.dns.lu","No entries found"),
    array("net.mx","whois.nic.mx","Nombre del Dominio"),
    array("net.uk","whois.nic.uk","No match for "),
    array("net.ru","whois.ripn.ru","No entries found"),
    array("net.tw","whois.twnic.net","NO MATCH TIP"),
    array("nl","whois.domain-registry.nl","is not a registered domain"),
    array("nm.cn","whois.cnnic.net.cn","No entries found"),
    array("no","whois.norid.no","no matches"),
    array("nom.br","whois.nic.br","No match"),
    array("not.br","whois.nic.br","No match"),
    array("ntr.br","whois.nic.br","No match"),
    array("nx.cn","whois.cnnic.net.cn","No entries found"),
    array("nz","whois.domainz.net.nz","Not Listed"),
    array("plc.uk","whois.nic.uk","No match for"),
    array("odo.br","whois.nic.br","No match"),
    array("oop.br","whois.nic.br","No match"),
    array("or.jp","whois.nic.ad.jp","No match"),
    array("org","whois.verisign-grs.net","No match"),
    array("org.au","whois.aunic.net","No Data Found"),
    array("org.br","whois.nic.br","No match"),
    array("org.cn","whois.cnnic.net.cn","No entries found"),
    array("org.hk","whois.hknic.net.hk","No Match for"),
    array("org.lu","whois.dns.lu","No entries found"),
    array("org.ru","whois.ripn.ru","No entries found"),
    array("org.tw","whois.twnic.net","NO MATCH TIP"),
    array("org.uk","whois.nic.uk","No match for"),
    array("pl","nazgul.nask.waw.pl","does not exists"),
    array("pp.ru","whois.ripn.ru","No entries found"),
    array("ppg.br","whois.nic.br","No match"),
    array("pro.br","whois.nic.br","No match"),
    array("psi.br","whois.nic.br","No match"),
    array("psc.br","whois.nic.br","No match"),
    array("pt","whois.ripe.net","No entries found"),
    array("qh.cn","whois.cnnic.net.cn","No entries found"),
    array("qsl.br","whois.nic.br","No match"),
    array("rec.br","whois.nic.br","No match"),
    array("ro","whois.rotld.ro","No entries found"),
    array("ru","whois.ripn.net","No entries found"),
    array("sc.cn","whois.cnnic.net.cn","No entries found"),
    array("sd.cn","whois.cnnic.net.cn","No entries found"),
    array("se","whois.nic-se.se","No data found"),
    array("sg","whois.nic.net.sg","NO entry found"),
    array("sh","whois.nic.sh","No match for"),
    array("sh.cn","whois.cnnic.net.cn","No entries found"),
    array("si","whois.arnes.si","No entries found"),
    array("sk","whois.ripe.net","no entries found"),
    array("slg.br","whois.nic.br","No match"),
    array("sm","whois.ripe.net","no entries found"),
    array("sn.cn","whois.cnnic.net.cn","No entries found"),
    array("srv.br","whois.nic.br","No match"),
    array("st","whois.nic.st","No entries found"),
    array("sx.cn","whois.cnnic.net.cn","No entries found"),
    array("tc","whois.adamsnames.tc","is not registered"),
    array("th","whois.nic.uk","No entries found"),
    array("tj.cn","whois.cnnic.net.cn","No entries found"),
    array("tmp.br","whois.nic.br","No match"),
    array("to","whois.tonic.to","No match"),
    array("tr","whois.ripe.net","Not found in database"),
    array("trd.br","whois.nic.br","No match"),
    array("tur.br","whois.nic.br","No match"),
    array("tv","whois.tv","MAXCHARS:75"),
    array("tv.br","whois.nic.br","No match"),
    array("tw","whois.twnic.net","NO MATCH TIP"),
    array("tw.cn","whois.cnnic.net.cn","No entries found"),
    array("uk","whois.thnic.net","No match for"),
    array("va","whois.ripe.net","No entries found"),
    array("vet.br","whois.nic.br","No match"),
    array("vg","whois.adamsnames.tc","is not registered"),
    array("wattle.id.au","whois.aunic.net","No Data Found"),
    array("ws","whois.worldsite.ws","No match for"),
    array("xj.cn","whois.cnnic.net.cn","No entries found"),
    array("xz.cn","whois.cnnic.net.cn","No entries found"),
    array("yn.cn","whois.cnnic.net.cn","No entries found"),
    array("zlg.br","whois.nic.br","No match"),
    array("zj.cn","whois.cnnic.net.cn","No entries found")
);

// Доменное имя
$domains = file("domains.txt");
// Извлекаем домен первого уровня
$freeDomain = [];
foreach ($domains as $domain){
    sleep(1);
    try{
        $domain = trim($domain);
        $first_dom = substr($domain, strpos($domain, ".") + 1);
        // Получаем имя whois-сервера, который отвечает за
        // домен $first_dom
        for($i = 0; $i < count($servers); $i++)
        {
            if($servers[$i][0] == $first_dom)
            {
                // Запоминаем имя сервера
                $whois = $servers[$i][1];
                // и фразу, означающую, что домен отсутствует
                $not_found_string = $servers[$i][2];
                // Покидаем цикл
                break;
            }
        }

        // Проверяем определён ли whois-сервер который несёт
        // ответственность за данный доменный уровень
        if(empty($whois)) throw new Exception("Whois не найден");
        // Обращаемся к whois-серверу и получаем информацию
        // о доменном имени
        $fp = fsockopen($whois, 43);
        fputs($fp, "$domain\r\n");
        $str = "";
        while(!feof($fp))
        {
            $str .= fgets($fp,128);
        }
        fclose($fp);
        // если в ответе имеется фраза-отказ, домен не
        // зарегистрирован, если такой фразы нет -
        // следовательно домен зарегистрирован
        if(!preg_match("/".$not_found_string."/is", $str))
        {
            echo "Домен ".$domain." уже зарегистрирован<br />";
        }
        else
        {
            $freeDomain[] = $domain;
            echo "Домен ".$domain." не зарегистрирован<br />";
        }
        file_put_contents("freeDomains.txt",implode("\n",$freeDomain));
    }catch (Exception $e){
        echo $e->getMessage()." ".$domain."<br />";
    }
}
