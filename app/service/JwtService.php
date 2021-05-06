<?php

namespace app\service;

use app\interfaces\JwtInterface;
use Firebase\JWT\JWT;

class JwtService implements JwtInterface
{
    private int $leeway = 30;

    public function generate(int $uid, array $data, int $exp = 1800): string
    {
        $payload = array_merge(['uid' => $uid], array_map('intval', array_merge($data, ['time' => time()])));
        //JWT有效时间
        $payload['exp'] = time() + $exp;
        //生成一个JWT密钥
        return JWT::encode($payload, config('app.jwt_key'));
    }

    public function verify(string $token): object
    {
        try {
            //对token进行解码
            $tokens = explode('.', $token);
            $payload = count($tokens) === 3 ? JWT::jsonDecode(JWT::urlsafeB64Decode($tokens[1])) : [];

            JWT::$leeway = $this->leeway;
            JWT::decode($token, config('app.jwt_key'), ['HS256']);

            return $payload;
        } catch (\Throwable $e) {
        }
    }
}
