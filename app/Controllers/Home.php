<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function indexs(): string
    {
        return view('welcome_message');
    }

    public function index(){
        $data=[
            'products'=>['Laptop','desktop','water dispencer','cctv']

        ];
        return view('products/products_view',$data);
    }
}
