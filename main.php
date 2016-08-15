﻿<?php
/**
 * Telegram Bot access token и URL.
 */
$access_token = '262304479:AAGFJ14BvUIY460f8He_gJ_L6JFXfKSc36M';
$api = 'https://api.telegram.org/bot' . $access_token;

/**
 * Задаём основные переменные.
 */
$output = json_decode(file_get_contents('php://input'), TRUE);
$chat_id = $output['message']['chat']['id'];
$first_name = $output['message']['chat']['first_name'];
$message = $output['message']['text'];

$listSites = array(
  'name'  => array( 'BTC_TRADE','POLONIEX') ,
  'address' => array( 'https://btc-trade.com.ua/api/trades/', 'https://poloniex.com/public?command=returnTicker'),
  "val_name"  => array('грн', 'USD'),
  "val_value" => array ("BTC_UAH", "USDT_BTC"),
);

$emoji = array(
  'preload' => json_decode('"\uD83D\uDE03"'), // Улыбочка.
  'weather' => array(
    'clear' => json_decode('"\u2600"'), // Солнце.
    'clouds' => json_decode('"\u2601"'), // Облака.
    'rain' => json_decode('"\u2614"'), // Дождь.
    'snow' => json_decode('"\u2744"'), // Снег.
  ),
);


/**
 * Получаем команды от пользователя.
 */
switch($message) {
  case '/test':
    
    
    $valyuta = 'btc_uah';
    $siteAddress1='https://btc-trade.com.ua/api/trades/buy/' . $valyuta;
    $siteAddress2='https://btc-trade.com.ua/api/trades/sell/' . $valyuta;

    // Отправляем приветственный текст.
    $preload_text = 'Одну секунду, ' . $first_name . ' ' . $emoji['preload'] . ' Я уточняю для Вас курс ...';
    sendMessage($chat_id, $preload_text);
    
    // Формирование ответа.
    $kurs_text = 'Сейчас по паре ' . $valyuta  . ' такие показатели: ';
    sendMessage($chat_id, $kurs_text );
    
    // Отправка ответа пользователю Telegram.
   
    $kurs = json_decode(file_get_contents($siteAddress1), TRUE);
    sendMessage($chat_id, 'Покупка: ' . $kurs[max_price] );
    
    $kurs = json_decode(file_get_contents($siteAddress2), TRUE);
    sendMessage($chat_id, 'Продажа: ' . $kurs[min_price] );
    
    break;
  
  case '/btc':
    // Отправляем приветственный текст.
    $preload_text = 'Одну секунду, ' . $first_name . ' ' . $emoji['preload'] . ' Я уточняю для Вас курс ...';
    sendMessage($chat_id, $preload_text);
    
    // Формирование ответа.
    $kurs = json_decode(file_get_contents($listSites['address'][0] . 'buy/'. $listSites['val_value'][0]), TRUE);
    $kurs_text = $listSites['name'][0] . ': ' . $kurs[max_price];
    
    $kurs = json_decode(file_get_contents($listSites['address'][0] . 'sell/'. $listSites['val_value'][0]), TRUE);
    $kurs_text = $kurs_text . ' - ' . $kurs[min_price] . ' ' . $listSites['val_name'][0];
    
    sendMessage($chat_id, $kurs_text );
    
    $data = json_decode(file_get_contents($listSites["address"][1]), TRUE); 
    $kurs_text = $listSites['name'][1] . ': ' . $data['USDT_BTC']['highestBid'] . ' - ' . $data['USDT_BTC']['lowestAsk'] . ' ' . $listSites['val_name'][1];

    sendMessage($chat_id, $kurs_text );
  
    
    break;
  default:
    break;
}

/**
 * Функция отправки сообщения sendMessage().
 */
function sendMessage($chat_id, $message) {
  file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message));
}
