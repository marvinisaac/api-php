<?php

    namespace Session;

    use \Carbon\Carbon;
    use \Slim\Http\Cookies;
    use \Slim\Http\Response;
    use Api\Shared\BaseClass\Output;

final class SessionOutput extends Output
{
    public function success(int $status, array $output = []) : Response
    {
        $response = $this->response->withStatus($status);

        if (count($output) > 0) {
            $cookies = $this->createCookies($output['token']);
            $response = $response->withHeader('Set-Cookie', $cookies->toHeaders());
        }

        $responseBody = json_decode((string)$response->getBody(), true);
        return $response;
    }

    private function createCookies(string $token) : Cookies
    {
        $cookieExpiry = Carbon::now()
            ->addHours($_ENV['JWT_EXPIRY_HOURS'])
            ->toCookieString();
        $tokenParts = explode('.', $token);

        $header = [
            'expires' => $cookieExpiry,
            'path' => '/',
            'value' => $tokenParts[0],
        ];
        $payload = [
            'expires' => $cookieExpiry,
            'path' => '/',
            'value' => $tokenParts[1],
        ];
        $signature = [
            'httponly' => true,
            'expires' => $cookieExpiry,
            'path' => '/',
            'value' => $tokenParts[2],
        ];

        if ($_ENV['PHP_ENVIRONMENT'] !== 'LOCAL') {
            $signature['secure'] = true;
        }

        $cookies = new Cookies();
        $cookies->set('header', $header);
        $cookies->set('payload', $payload);
        $cookies->set('signature', $signature);

        return $cookies;
    }
}
