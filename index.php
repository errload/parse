<?php

$url = 'https://wikicity.kz/biz/janym-soul-almaty';
$html = file_get_contents($url);

$dom = new DOMDocument();
@ $dom->loadHTML($html);
$xpath = new DOMXpath($dom);

// количество отзывов на странице
$reviews_count = $xpath->query('//div[@class="review review--with-sidebar"]');

// дата, имя, отзыв
$date = $xpath->query('//div[@class="review-content"]//span[@class="rating-qualifier"]');
$name = $xpath->query('//div[@class="review-sidebar"]//a[@class="user-display-name"]');
$reviews = $xpath->query('//div[@class="review-content"]//p');

// отдельно оценка, так как содержится в аттрибуте
$grade = $xpath->query('//div[@class="review-content"]//div//img');

foreach ($grade as $n) {
    $raitings[] = $n->getAttribute('alt');
}

// результирующий массив
for ($i = 0; $i < $reviews_count->length; $i++) {
    $raiting = explode(' ', $raitings[$i])[1];
    $raiting = explode('.', $raiting)[0];

    $result[] = [
        'date' => trim($date->item($i)->nodeValue),
        'name' => trim($name->item($i)->nodeValue),
        'raiting' => $raiting,
        'preview' => trim(preg_replace('/\s+/', ' ', $reviews->item($i)->nodeValue))
    ];
}

echo '<pre>';
print_r($result);
