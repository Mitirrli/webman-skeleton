<?php

namespace app\controller;

use app\service\JwtInterface;
use support\Request;
use support\bootstrap\Container;

class Index
{
    public function index(Request $request)
    {
        // $result = Container::get(JwtInterface::class)->generate(1, ['data' => 'test', 'remark' => 'doc']);

        // $token = 'eeyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjEsImRhdGEiOjAsInJlbWFyayI6MCwidGltZSI6MTYyMDI3ODExMCwiZXhwIjoxNjIwMjc5OTEwfQ.GmP55IHRT1U-L69NDg7COWZ5ExVzFbpYzeTHLvSbzYU';

        // $result = Container::get(JwtInterface::class)->verify($token);

        // return response($result);
    }

    public function view(Request $request)
    {
        return view('index/view', ['name' => 'webman']);
    }

    public function json(Request $request)
    {
        return json(['code' => 0, 'msg' => 'ok']);
    }

    public function file(Request $request)
    {
        $file = $request->file('upload');
        if ($file && $file->isValid()) {
            $file->move(public_path().'/files/myfile.'.$file->getUploadExtension());
            return json(['code' => 0, 'msg' => 'upload success']);
        }
        return json(['code' => 1, 'msg' => 'file not found']);
    }
}
