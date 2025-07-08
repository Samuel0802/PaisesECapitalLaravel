<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    private $paises_e_capitais;

    public function __construct()
    {
       //carrega um arquivo PHP externo chamado paises_e_capitais.php dentro do app
       $this->paises_e_capitais = require(app_path('paises_e_capitais.php'));

    }

    //Enviar uma resposta JSON com os dados de países e capitais para quem acessar essa função — geralmente via uma rota da aplicação Laravel.
    public function showData(){
        return response()->json($this->paises_e_capitais);
    }
}
