<?php
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


$valyuta = 'btc_uah';
$siteAddress1='https://btc-trade.com.ua/api/trades/buy/' . $valyuta;
$siteAddress2='https://btc-trade.com.ua/api/trades/sell/' . $valyuta;


$listSites = array(
  'name'  => array( 'BTC_TRADE','POLONIEX') ,
  'address' => array( 'https://btc-trade.com.ua/api/trades/', 'https://poloniex.com/public?command=returnTicker'),
);
$listPairs = array(
  "name"  => array('грн/1биткоин', 'USD/1биткоин'),
  "value" => array ("btc_uah", "usdt_btc"),
);
/**


/**
 * Получаем команды от пользователя.
 */
switch($message) {
  case '/test':
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
  
  case '/BTC':
    // Отправляем приветственный текст.
    $preload_text = 'Одну секунду, ' . $first_name . ' ' . $emoji['preload'] . ' Я уточняю для Вас курс ...';
    sendMessage($chat_id, $preload_text);
    
    // Формирование ответа.
    $kurs = json_decode(file_get_contents($siteAddress1), TRUE);
    $kurs_text = 'Биржа "' . $listSites['name'][0] . '": Покупка - ' . $kurs[max_price];
    
    $kurs = json_decode(file_get_contents($siteAddress2), TRUE);
    $kurs_text = $kurs_text . '. Продажа - ' . $kurs[min_price];
    
    sendMessage($chat_id, $kurs_text );
    
    $data = json_decode(file_get_contents($listSites["address"][1]), TRUE); 
    $kurs_text = 'Биржа ' . $listSites['name'][1] . ': Покупка - ' . $data['USDT_BTC']['lowestAsk'] . ': Продажа - ' . $data['USDT_BTC']['highestBid'];

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
