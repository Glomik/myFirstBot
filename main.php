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


/**
 * Emoji для лучшего визуального оформления.
 */
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
    // Отправляем приветственный текст.
    $preload_text = 'Одну секунду, ' . $first_name . ' ' . $emoji['preload'] . ' Я уточняю для вас курс..';
    sendMessage($chat_id, $preload_text);
    
    // API key для OpenWeatherMap.
    $kurs1 = json_decode(file_get_contents($siteAddress1), TRUE);
    $kurs2 = json_decode(file_get_contents($siteAddress2), TRUE);
  
    // Формирование ответа.
    $kurs_text = 'Сейчас по паре ' . $valyuta  . ' такие показатели: ';
    
    // Отправка ответа пользователю Telegram.
    sendMessage($chat_id, $kurs_text );
    sendMessage($chat_id, 'Покупка: ' . $kurs1[max_price] );
    sendMessage($chat_id, 'Продажа: ' . $kurs2[min_price] );
    
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
