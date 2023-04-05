<?php
$body = file_get_contents('https://pervouralsk.bbmprof.ru/upload/iblock_rss_6.xml');
importNews ($body);

function importNews ($body)
{
    $body = str_replace('<chelpipe:full-text>', '<fulltext>', $body);
    $body = str_replace('</chelpipe:full-text>', '</fulltext>', $body);
    $xml = new SimpleXmlElement($body, LIBXML_NOCDATA);
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);

    if (!$array) {
        die ('Неправильный формат rss');
    }


    foreach($array['channel']['item'] as $item) {
        $date = date_create_from_format('D, d M Y H:i:s O', $item['pubDate']);
        $news = array(
            'name' => ($item['title']),
            'title' => (string)$item['title'],
            'published' => 1,
            'content' => html_entity_decode ($item['description']),
            'date' => $date->format('Y-m-d'),
            'intro' => '',
            'tpl' => 1,

        );
        print_r('<pre>');
        print_r($news);
        print_r('</pre>');

//        $news_id = saveNews($news);
//        if (isset($item['enclosure']) && $news_id) {
//            $news_img = uploadImage($item['enclosure']['@attributes']['url']);
//            if ($news_img)
//                updateNewsImg($news_id, $news_img);
//        }

    }



}