<?php

 include 'class/BD.php';//расширения класса PDO
 $connection = new BD('тест');


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// определяем кодировку
header('Content-type: text/html; charset=utf-8');
// Создаем объект бота
$bot = new Bot();
// Обрабатываем пришедшие данные test
$bot->init('php://input');
/**
 * Class Bot
 */
class Bot 
{
    // <bot_token> - созданный токен для нашего бота от @BotFather
    private $botToken = "5511090125:AAEUg1NCSPTWFbfFi9ZL8JKv8FqAFtnsMDQ";
    // адрес для запросов к API Telegram
    private $apiUrl = "https://api.telegram.org/bot";
    // url sites
    private $url_sites = 'https://country-excel-manager.jsonb.ru/';
    // админы
    private $ADMIN = [70171414, 472611922, 5574121203, 5453854424, 5391936892, 659025951, 122815990, 169024420, 802243803, 569032193, 196620115, 1440214573, 483595318, 1660455309, 1862633986, 1872329574, 1927819764, 1656594297, 2034540659, 2137518532, 1295698464, 5081659868, 882013448, 1928875918, 5217432820, 732595243, 5423441684, 5343624925, 5471719802];

    public function init($data_php)
    {
        // создаем массив из пришедших данных от API Telegram
        $data = $this->getData($data_php);
        // id чата отправителя
        $chat_id = $data['message']['chat']['id'];
        //включаем логирование будет лежать рядом с этим файлом
        //$this->setFileLog($data, "log.txt");

        $justKeyboard = $this->getKeyBoard([
            [
                ["text" => "Получить партнеров по странам"]
            ]    

                //        [ "text" => "Ещё" ],
   
        ]);

        
        
        // Кнопка отмены
        $otmena = $this->getKeyBoard([
            [
                ["text" => "Отмена"]
            ]
        ]);
        

        if (array_key_exists('message', $data)) {
            // пришла команда /start
            if ($data['message']['text'] == "/start") {
                //  проверка на существование файла
                if ($this->fwd($chat_id) == false) {
                    $this->fwclose($chat_id);
                }
                

                // проверка на админа
                $textAd = $this->isAdmin($chat_id);
                if ($textAd == "Привет админ") {

                    $dataSend = array(
                        'text' => "Приветствую Менеджер, выберите действие.",
                        'chat_id' => $chat_id,
                        'reply_markup' => $justKeyboard,
                    );
                    $this->requestToTelegram($dataSend, "sendMessage");
                } else {
                    $this->sendMessage($chat_id, $textAd);
                }
            }
        }

        $textAd = $this->isAdmin($chat_id);
        if ($textAd == "Привет админ") {
            $message = $data['message']['text'];

            if (!file_exists("img/$chat_id/")) {
                mkdir("img/$chat_id/", 0700);
            }

            $file = file_get_contents("file/$chat_id.txt");
            //$file1 = file_get_contents("file/phone$chat_id.txt");
           
            if (
                !empty($file)  && $message != "UZB 🇺🇿" && $message != "RUS 🇷🇺" && $message != "IN 🇮🇳"
                
                && $file != "<" && $message != "/start" && $message != "/stop" && $message != "Экспресс дня"
            ) {

                $this->fwclose($chat_id);
                $this->fwclose3($chat_id);
                //Создаём картинку
                header('Content-Type: image/jpeg');
                $text = $message;
                $text = mb_strtoupper($text);
                $time = time();


                if ($file == "Ваша ссылка на скачивание") {

                    $mas_telegram_id = [
                        "882013448" => "Австрия", //Игорь Австрия
                        "472611922" => "Австрия", //Фёдор Азербайджан
                        "70171414" => "Ангола" //Дмитрий Ангола
                    ];

                    $country = $mas_telegram_id[$chat_id];

                    //в дальнейшем исправить вынести в класс BD
                    $connection1 = new PDO('mysql:host=localhost;dbname=country-excel-manager;charset=utf8', 'manadger', 'rZ2pD7jV8z');
                    $is_null = $connection1->query('SELECT country FROM manadger WHERE country = "' . $country . '" AND is_downloaded = 0')->fetchAll(PDO::FETCH_ASSOC);
                    $text_country = "Данные по стране " . $country . " закончились";

                    //проверка есть ли в базе доступные записи по стране
                    if (empty($is_null)) {
                        $dataSend = array(
                            'text' => $text_country,
                            'chat_id' => $chat_id,
                            'reply_markup' => $justKeyboard,
                        );
                        $this->requestToTelegram($dataSend, "sendMessage");
                    } else {

                        //обработчик выгрузки в эксель
                        file_get_contents("https://country-excel-manager.jsonb.ru/get.php?country=" . $country . "&user_id=" . $chat_id);
                        
                        header('Content-type: text/html; charset=utf-8');
                        
                        $file_name = 'партнёры_по_странам_' . $country . date('Y-m-d') . '.xlsx';
                        $this->senddocument($chat_id, $file_name);
                        // $date_pref = date('Y-m-d',strtotime("-7 days"));
                        $date = "Следующая выгрузка доступна со следующей недели ";
                        $dataSend = array(
                            'text' => $date,
                            'chat_id' => $chat_id,
                            'reply_markup' => $justKeyboard,
                        );
                        $this->requestToTelegram($dataSend, "sendMessage");

                        unlink($file_name);//удаляем файл xlsx
                        $this->fwclose($chat_id);//чистим временный файл
                    }
                }

                $this->fpwritecount();

                $this->fwclose($chat_id);
  
            } 
            
            if ($message == "Получить партнеров по странам") {
                $this->fpaddwrite($chat_id, "Ваша ссылка на скачивание");
                
            }
            
            if ($message == "Отмена") {
                $dataSend = array(
                    'text' => "Отмена действий",
                    'chat_id' => $chat_id,
                    'reply_markup' => $justKeyboard,
                );
                $this->requestToTelegram($dataSend, "sendMessage");
                $this->fwclose($chat_id);
            }
        } else {
            if ($data['message']['text'] != '/start') $this->sendMessage($chat_id, $textAd);
        }

    }



    // Очистка файла
    private function fwclose($id)
    {
        $fd = fopen("file/$id.txt", 'w+') or die("не удалось создать файл");
        $str = "";
        fwrite($fd, $str);
        fclose($fd);
    }
    // Очистка файла
    private function fwclose2($id)
    {
        $fd = fopen("file/phone$id.txt", 'w+') or die("не удалось создать файл");
        $str = "";
        fwrite($fd, $str);
        fclose($fd);
    }
    // Очистка файла
    private function fwclose3($id)
    {
        $fd = fopen("file/number$id.txt", 'w+') or die("не удалось создать файл");
        $str = "";
        fwrite($fd, $str);
        fclose($fd);
    }

    

    //проверка существование файла
    private function fwd($id)
    {
        $fd = fopen("file/$id.txt", 'r');
        return $fd;
        fclose($fd);
    }
    //проверка существование файла
    private function fwd2($id)
    {
        $fd = fopen("file/phone$id.txt", 'r');
        return $fd;
        fclose($fd);
    }
    //проверка существование файла
    private function fwd3($id)
    {
        $fd = fopen("file/number$id.txt", 'r');
        return $fd;
        fclose($fd);
    }

    //клавиатура
    private function getKeyBoard($data)
    {
        $keyboard = array(
            "keyboard" => $data,
            "one_time_keyboard" => false,
            "resize_keyboard" => true
        );
        return json_encode($keyboard);
    }

    // Дозапись в файл
    private function fpaddwrite($id, $text)
    {
        $fd = fopen("file/$id.txt", 'a+') or die("не удалось создать файл");
        $str = $text;
        fwrite($fd, $str);
        fclose($fd);
    }

    // проверка на админа
    private function isAdmin($chat_id)
    {
        if (in_array($chat_id, $this->ADMIN)) {
            $text = "Привет админ";
        } else {
            $text = "Вам отказано в доступе";
        }
        return $text;
    }

    // функция отправки текстового сообщения
    private function sendMessage($chat_id, $text, $buttons = false)
    {
        $this->requestToTelegram([
            'chat_id' => $chat_id,
            'text' => $text,
            "parse_mode" => "HTML",
            'reply_markup' => $buttons
        ], "sendMessage");
    }

    

    // функция получения метонахождения файла
    private function getPhotoPath($file_id)
    {
        // получаем объект File
        $array = json_decode($this->requestToTelegram(['file_id' => $file_id], "getFile"), TRUE);
        // возвращаем file_path
        return $array['result']['file_path'];
    }

    // функция логирования в файл
    private function setFileLog($data, $file)
    {
        $fh = fopen($file, 'a') or die('can\'t open file');
        ((is_array($data)) || (is_object($data))) ? fwrite($fh, print_r($data, TRUE) . "\n") : fwrite($fh, $data . "\n");
        fclose($fh);
    }

    /**
     * Парсим что приходит преобразуем в массив
     * @param $data
     * @return mixed
     */
    private function getData($data)
    {
        return json_decode(file_get_contents($data), TRUE);
    }

    /** Отправляем запрос в Телеграмм
     * @param $data
     * @param string $type
     * @return mixed
     */
    private function requestToTelegram($data, $type)
    {
        $result = null;

        if (is_array($data)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->botToken . '/' . $type);
            curl_setopt($ch, CURLOPT_POST, count($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return $result;
    }
}
