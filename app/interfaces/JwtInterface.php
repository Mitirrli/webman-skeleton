<?php

namespace app\interfaces;

interface JwtInterface
{
    //生成一个JWT密钥
    public function generate(int $uid, array $data, int $exp = 1800): string;

    //验证JWT是否正确
    public function verify(string $token): object;
}
