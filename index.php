<?php

include_once 'db.php';
include_once 'simple_html_dom.php';


//print_r($_SERVER);
function curlGetPage($url, $referer = 'https://google.com/')
{
/*    $proxies = [
        '1.1.1.1',
        '2.2.2.2',
    ];
    $proxyCount = count($proxies);
    $proxyId = rand(0, $proxyCount - 1);
*/
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //curl_setopt($ch, CURLOPT_PROXY, $proxies[$proxyId]);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$page = curlGetPage('https://www.sport-express.ru/football/reviews/');
$html = str_get_html($page);

$pageCount = $html->find('.se-material-list-page__nav', 0)->getAttribute("data-prop-max-page");

//$posts = [];
$postCount = 0;
for ($i = 1; $i <= $pageCount; $i++) {

    $referer = 'https://google.com/';
    if ($i > 2) {
        $referer = 'https://www.sport-express.ru/football/reviews'.($i-1).'/';
    }   

    if ($i > 5) {
        break;
    } 
    $page = curlGetPage('https://www.sport-express.ru/football/reviews'.$i.'/', $referer);
    $html = str_get_html($page);
    foreach ($html->find('.se-press-list-page__item') AS $element) {
        $img = $element->find('.se-material__content-media a img', 0);
        $link = $element->find('.se-material__title a', 0);
        $post = [
            'img' => $img->src,
            'title' => trim($link->plaintext),
            'link' => $link->href,
        ];
        $db->query("INSERT IGNORE INTO posts (`title`, `img`, `link`) 
                VALUES('{$post['title']}', '{$post['img']}', '{$post['link']}')");

        $postCount++;
        print_r($postCount . ' - ' . $post['title'] . "<br />");
    }
    usleep(1000000);
}

//print_r($posts);