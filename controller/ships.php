<?php
class ships extends spController{
    public function index(){
        $rdf = "";
        $item = "";
        $workingShipsModel = spClass('activecontentinfo');
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $dayBegin = mktime(0, 0, 0, $month, $day, $year);
        $dayEnd = mktime(23, 59, 59, $month, $day, $year);
        $condition = "ApplicantDate BETWEEN ".$dayBegin." AND ".$dayEnd;
        $workingShips = $workingShipsModel->findAll($condition);
        for($i = 0; $i < count($workingShips); $i++){
            $point = explode("#", $workingShips[$i]['calldata']);
            $rdf = $rdf."<rdf:li resource='".$i."'/>";
            $item = $item."<item rdf:about='".$i."'><link></link><title>测试</title><description>wo ai zhong guo</description><georss:point>".$point[5]." ".$point[6]."</georss:point></item>";
        }
        $dom = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/css" href="/css/rss.css" ?> <rdf:RDF  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:georss="http://www.georss.org/georss"> <docs></docs><channel rdf:about="http://platial.com"> <link>http://platial.com</link> <title>Crschmidt\'s Places At Platial</title> <description></description> <items> <rdf:Seq>'.$rdf.'</rdf:Seq></items></channel>'.$item.'</rdf:RDF>';
        $fp = fopen("./test.xml", "w+");
        fwrite($fp, $dom);
        fclose($fp);
    }
}
