<?php declare(strict_types=1);

namespace Nalognl\MegaplanModule\Http\RequestMegaplan;

use Nalognl\MegaplanModule\AuthApi\AuthApi;
use Exception;
use stdClass;

class RequestMegaplan
{
    /**
     * Authorized request object ready to make get and post requests
     *
     * @var \Nalognl\MegaplanModule\Http\Requests\Request|null
     */
    protected $request;

    /**
     * RequestMegaplan constructor.
     *
     * @param \Nalognl\MegaplanModule\AuthApi\AuthApi|null $auth
     */
    public function __construct(?AuthApi $auth)
    {
        $this->request = $auth->getRequest();
    }

    /**
     * Checks status code in given response object, if status is not okay
     * it will throw an exception with error message.
     *
     * @param \stdClass|null $res
     * @param string $error_message
     * @throws \Exception
     */
    protected function throwIfError(?stdClass $res, string $error_message): void
    {
        $code = $res->status->code ?? $res->meta->status ?? null;
        $err = $res->status->message ?? $res->meta->errors ?? null;

        if (!in_array($code, ['ok', 200])) {
            $message = is_array($err)
                ? "$error_message что-то пошло не так. Мегаплан ответил: " . json_encode($err, JSON_UNESCAPED_UNICODE)
                : "$error_message что-то пошло не так. Мегаплан ответил: $err";

            tiny_log($message);
            throw new Exception($message);
        }
    }
}