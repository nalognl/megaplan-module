<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http\Requests;

use Nalognl\MegaplanModule\Http\RequestInfo;
use stdClass;

class Request1 implements Request
{
    /** @var string Идентификатор пользователя */
    protected $access_id;

    /** @var string Секретный ключ */
    protected $secret_key;

    /** @var string Название хоста */
    protected $host;

    /** @var bool Индикатор использования https */
    protected $https = false;

    /** @var string Результат последнего запроса */
    protected $result;

    /** @var array Информация о последнем запросе */
    protected $info;

    /** @var int Таймаут соединения в секундах */
    protected $timeout;

    /** @var string Последняя ошибка CURL-запроса */
    protected $error;

    /**
     * @var string|null Путь к файлу, который будет записан всё содержимое ответа
     */
    protected $output_file = null;

    /**
     * Создает объект
     *
     * @param string $access_id Идентификатор пользователя
     * @param string $secret_key Секретный ключ
     * @param string $host Имя хоста мегаплана
     * @param bool|null $https Использовать SSL-соединение (true)
     * @param int|null $timeout Таймаут подключения
     */
    public function __construct(
        string $access_id,
        string $secret_key,
        string $host,
        ?bool $https = true,
        ?int $timeout = 60
    ) {
        $this->access_id = $access_id;
        $this->secret_key = $secret_key;
        $this->host = $host;
        $this->https = $https;
        $this->timeout = $timeout;
    }

    /**
     * Устанавливает нужно ли использовать https-соединение
     *
     * @param bool|null $is_ssl
     */
    public function useHttps(?bool $is_ssl = true): void
    {
        $this->https = $is_ssl;
    }

    /**
     * Устанавливает путь к файлу, в который будет записан всё содержимое ответа
     *
     * @param string $file_path Путь к файлу
     */
    public function setOutputFile(string $file_path): void
    {
        $this->output_file = $file_path;
    }

    /**
     * Отправляет GET-запрос
     *
     * @param string $uri
     * @param array $params GET-параметры
     * @return \stdClass|null Ответ на запрос
     * @throws \Exception
     */
    public function get(string $uri, ?array $params = null): ?stdClass
    {
        $uri = $this->processUri($uri, $params);

        $request = RequestInfo::create('GET', $this->host, $uri, [
            'Date' => date('r'),
        ]);

        return $this->send($request);
    }

    /**
     * Отправляет POST-запрос
     *
     * @param string $uri
     * @param array $params GET-параметры
     * @return \stdClass|null Ответ на запрос
     * @throws \Exception
     */
    public function post(string $uri, array $params = null): ?stdClass
    {
        $uri = $this->processUri($uri);

        $headers = [
            'Date' => date('r'),
            'Post-Fields' => $params,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $request = RequestInfo::create('POST', $this->host, $uri, $headers);

        return $this->send($request);
    }

    /**
     * Собирает строку запроса из URI и параметров
     *
     * @param string $uri URI
     * @param array $params Параметры запроса
     * @return string
     */
    public function processUri(string $uri, array $params = null): string
    {
        $part = parse_url($uri);

        if (!preg_match("/\.[a-z]+$/u", $part['path'])) {
            $part['path'] .= '.easy';
        }

        if ($params) {
            if (!empty($part['query'])) {
                parse_str($part['query'], $params);
            }
            $uri .= '?'.http_build_query($params);
        } elseif (!empty($part['query'])) {
            $uri .= '?'.$part['query'];
        }

        return $uri;
    }

    /**
     * Осуществляет отправку запроса
     *
     * @param RequestInfo $request Параметры запроса
     * @return \stdClass|null Ответ на запрос
     */
    protected function send(RequestInfo $request): ?stdClass
    {
        $signature = self::calcSignature($request, $this->secret_key);

        $headers = [
            'Date: '.$request->Date,
            'X-Authorization: '.$this->access_id.':'.$signature,
            'Accept: application/json'
        ];

        if ($request->ContentType) {
            $headers[] = 'Content-Type: '.$request->ContentType;
        }

        if ($request->ContentMD5) {
            $headers[] = 'Content-MD5: '.$request->ContentMD5;
        }

        $ch = curl_init($this->generateUrl($request));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, __CLASS__);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->Method);

        if ($request->Method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);

            if ($request->PostFields) {
                $postFields = is_array($request->PostFields)
                    ? http_build_query($request->PostFields)
                    : $request->PostFields;

                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($this->https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        if ($this->output_file) {
            $fh = fopen($this->output_file, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fh);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        if ($this->output_file && isset($fh)) {
            curl_exec($ch);
            $this->result = null;

            if (isset($fh)) {
                fclose($fh);
            }
        } else {
            $this->result = curl_exec($ch);
        }

        $this->info = curl_getinfo($ch);
        $this->error = curl_error($ch);

        curl_close($ch);

        return json_decode($this->result);
    }

    /**
     * Вычисляет подпись запроса
     *
     * @param RequestInfo $request Параметры запроса
     * @param string $secret_key Секретный ключ
     * @return string Подпись запроса
     */
    public static function calcSignature(RequestInfo $request, string $secret_key): string
    {
        $stringToSign = $request->Method."\n".
            $request->ContentMD5."\n".
            $request->ContentType."\n".
            $request->Date."\n".
            $request->Host.$request->Uri;

        $signature = base64_encode(self::hashHmac('sha1', $stringToSign, $secret_key));

        return $signature;
    }

    /**
     * Клон функции hash_hmac
     *
     * @param string $algo алгоритм, по которому производится шифрование
     * @param string $data строка для шифрования
     * @param string $key ключ
     * @param boolean $raw_output
     * @return string|false
     */
    public static function hashHmac(string $algo, string $data, string $key, ?bool$raw_output = false)
    {
        if (function_exists('hash_hmac')) {
            return hash_hmac($algo, $data, $key, $raw_output);
        }

        $algo = strtolower($algo);
        $pack = 'H'.strlen($algo('test'));
        $size = 64;
        $opad = str_repeat(chr(0x5C), $size);
        $ipad = str_repeat(chr(0x36), $size);

         $key = strlen($key) > $size
            ? str_pad(pack($pack, $algo($key)), $size, chr(0x00))
            : str_pad($key, $size, chr(0x00));

        for ($i = 0; $i < strlen($key) - 1; $i++) {
            $opad[$i] = $opad[$i] ^ $key[$i];
            $ipad[$i] = $ipad[$i] ^ $key[$i];
        }

        $output = $algo($opad.pack($pack, $algo($ipad.$data)));

        return $raw_output ? pack($pack, $output) : $output;
    }

    /**
     * Возвращает результат последнего запроса
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Возвращает информацию о последнем запросе
     * Возвращает информацию о последнем запросе
     *
     * @param string $param Параметр запроса (если не указан, возвращается вся информация)
     * @return mixed
     */
    public function getInfo($param = null)
    {
        if ($param) {
            return $this->info[$param] ?? null;
        }
        return $this->info;
    }

    /**
     * Возвращает последнюю ошибку запроса
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    private function generateUrl(RequestInfo $request): string
    {
        $protocol = $this->https ? 'https' : 'http';
        return "{$protocol}://{$this->host}{$request->Uri}";
    }
}
