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

$listSites = array(
  'name'  => array( 'BTC_TRADE','POLONIEX', 'CEX', 'BITSTAMP','GEMINI') ,
  'address' => array( 'https://btc-trade.com.ua/api/trades/', 
                      'https://poloniex.com/public?command=returnTicker', 
                      'https://cex.io/api/ticker/',
                      'https://www.bitstamp.net/api/v2/ticker/',
                      'https://api.gemini.com/v1/pubticker/'),
  "val_name"  => array('грн', 'USD','USD', 'USD','USD'),
  "val_value" => array ("_UAH", "USDT_",'/USD','usd', 'USD'),
);
$list_val = array(
  'btc' => array('ref' =>'11111'),
  'ltc' => array('ref' =>'11100'),
  'etc' => array('ref' =>'01001'),
  'dash'=> array('ref' =>'01000'),
  'eth' => array('ref' =>'01100'),
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
  case '/game':
      sendPhoto($chat_id,'field.png'); 
      break;
      
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
    
  case '/help':
    
    $help="Возможно использовать такие команды:  /help, /test, /btc, /ltc, /etc, /eth, /dash ";
    sendMessage($chat_id, $help );
    
    break;
  
  case '/ltc':
  case '/btc':
  case '/etc':
  case '/eth':
  case '/dash':
     
    $name=substr($message,1);
 
    // Отправляем приветственный текст.
    $preload_text = 'Одну секунду, ' . $first_name . ' ' . $emoji['preload'] . ' Я уточняю для Вас курс ' . strtoupper($name) . '...';
    sendMessage($chat_id, $preload_text);
    
    $ref=$list_val[$name]['ref'];
    
    if (substr($ref,0,1)=='1' ) {
        
      // Формирование ответа.
      $kurs = json_decode(file_get_contents($listSites['address'][0] . 'buy/' . $name . $listSites['val_value'][0]), TRUE);
      $kurs_text = $listSites['name'][0] . ': ' . $kurs[max_price];
      
      $kurs = json_decode(file_get_contents($listSites['address'][0] . 'sell/' . $name . $listSites['val_value'][0]), TRUE);
      $kurs_text = $kurs_text . ' - ' . $kurs[min_price] . ' ' . $listSites['val_name'][0];
      
      sendMessage($chat_id, $kurs_text );
    }
    
    if (substr($ref,1,1)=='1' ) {
      $data = json_decode(file_get_contents($listSites["address"][1]), TRUE); 
      $kurs_text = $listSites['name'][1] . ': ' . $data[$listSites['val_value'][1] . strtoupper($name) ]['highestBid'] . ' - ' . $data[$listSites['val_value'][1] . strtoupper($name) ]['lowestAsk'] . ' ' . $listSites['val_name'][1];
  
      sendMessage($chat_id, $kurs_text );
    }
    
    if (substr($ref,2,1)=='1' ) {
      $data = json_decode(file_get_contents($listSites["address"][2] . strtoupper($name) . $listSites['val_value'][2] ), TRUE); 
      $kurs_text = $listSites['name'][2] . ': ' . $data['bid'] . ' - ' . $data['ask'] . ' ' . $listSites['val_name'][2];
  
      sendMessage($chat_id, $kurs_text );
    }
    
    if (substr($ref,3,1)=='1' ) {
      $data = json_decode(file_get_contents($listSites["address"][3] . $name . $listSites['val_value'][3] ), TRUE); 
      $kurs_text = $listSites['name'][3] . ': ' . $data['bid'] . ' - ' . $data['ask'] . ' ' . $listSites['val_name'][3];
  
      sendMessage($chat_id, $kurs_text );
    }
    if (substr($ref,4,1)=='1' ) {
      $data = json_decode(file_get_contents($listSites["address"][4] . strtoupper($name) . $listSites['val_value'][4] ), TRUE); 
      $kurs_text = $listSites['name'][4] . ': ' . $data['bid'] . ' - ' . $data['ask'] . ' ' . $listSites['val_name'][4];
  
      sendMessage($chat_id, $kurs_text );
    }
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

function sendPhoto($chat_id, $name) {
//  file_get_contents($GLOBALS['api'] . '/sendPhoto?chat_id=' . $chat_id . '&photo="' . $photo_id . '"');
  $url = $GLOBALS['api'] . '/sendPhoto' ;
  imagedestroy (draw_game ($name,'example.png',3,2);
  /*
  $post_fields = array( 'chat_id' => $chat_id,
        'photo'     =>   
  );
  */
  //  работает
  $post_fields = array( 'chat_id' => $chat_id,
        'photo'     =>  new CURLFile( $name)
  );
  //
  /*
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type:multipart/form-data"
  ));
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
  curl_setopt($ch, CURLOPT_INFILESIZE, filesize($name);
  $output = curl_exec($ch);
  log $output;
  */
    //  open connection
    $ch = curl_init();
    //  set the url
    curl_setopt($ch, CURLOPT_URL, $url);
    //  number of POST vars
    curl_setopt($ch, CURLOPT_POST, count($post_fields));
    //  POST data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    //  To display result of curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    //  execute post
    $result = curl_exec($ch);
    //  close connection
    curl_close($ch);
 

}

function draw_game($const_game_field, $img_add, $i,$j)
{
  $new_img = imagecreatefrompng($const_game_field);
  $img    = imagecreatefrompng($img_add);
  
  # расчет размеров изображения (ширина и высота)  
  $img_w = imagesx( $new_img ); 
  $img_h = imagesy( $new_img ); 
  
  # пройдемся по изображению 
  for( $y = 0; $y < 16; $y++ ) 
  { 
    for ($x = 0; $x < 16; $x++ ) 
    { 
      $return_color = imagecolorat( $img, $x, $y ); 
      imagesetpixel($new_img, 1+16*$i+$x, 1+16*$j+$y, $return_color );  
    }
  }
  imagedestroy($img);
  return $new_img;
}
