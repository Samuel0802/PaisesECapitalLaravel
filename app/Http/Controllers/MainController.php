<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    private $paises_e_capitais;

    public function __construct()
    {
        //carrega um arquivo PHP externo chamado paises_e_capitais.php dentro do app (DADOS)
        $this->paises_e_capitais = require(app_path('paises_e_capitais.php'));
    }

    public function startGame()
    {
        return view('home');
    }

    public function prepareGame(Request $request)
    {

    //Validando Campo do Formulário
      $regra = [
       'total_questions' => 'required|min:3|max:30|integer',

      ];

      $feedback = [
            'total_questions.required' => 'O campo Número de perguntas é Obrigátorio',
            'total_questions.min' => 'O campo deve ter no minimo :min caracteres',
            'total_questions.max' => 'O campo deve ter no maximo :max caracteres',
            'total_questions.integer' => 'O campo deve conter apenas números',
      ];

      $request->validate($regra, $feedback);

      //Recupera o valor do campo total_questions enviado pelo formulário.
      //Converte para inteiro, com intval().
       $total_questions = intval($request->input('total_questions'));

       //Preparar todas as perguntas
       $quiz = $this->preparePerguntas($total_questions);

      //armazenar o questionário na sessão
      session()->put([
        'quiz' => $quiz ,
        'total_questions' => $total_questions,
        'currect_question' => 1,
        'currect_answers' => 0,
        'wrong_answers' => 0,
      ]);

      return redirect()->route('game');
    }

    private function preparePerguntas($total_questions)
    {
      $questions = [];
      $total_countries = count($this->paises_e_capitais);

      //criar paises index for unico questao
      $indexes = range(0, $total_countries - 1);

      //embaralhe os meus arrays
      shuffle($indexes);

      $indexes = array_slice($indexes, 0, $total_questions);

      //create array of questions
      $question_number = 1;

      foreach($indexes as $index)
      {
        //numero da pergunta
      $question['question_number'] = $question_number ++;
      //Qual pais
      $question['country'] = $this->paises_e_capitais[$index]['country'];
      //Qual resposta correta
      $question['correct_answer'] = $this->paises_e_capitais[$index]['capital'];


      //Resposta errada!
      //Extrair todas as capitais de um array multidimensional (ou seja, um array de arrays),
      // pegando somente o valor da chave 'capital' de cada item.
      $other_capitals = array_column($this->paises_e_capitais, 'capital');

      //remover respota correta
      //compara arrays e remove os elementos do primeiro array que também existirem no segundo.
      //resposta_certa
      $other_capitals = array_diff($other_capitals, [$question['correct_answer']]);

      //embaralhe as respostas erradas
      shuffle($other_capitals);

      // recorta um pedaço de um array, retornando apenas os elementos que você pedir,
      // a partir de uma posição inicial.
      //respostas erradas
      $question['wrong_answers'] = array_slice($other_capitals, 0, 3);

     //store answer resultado
     $question['correct'] = null;

     $questions[] = $question;

    }

    return $questions;
}

public function game(): View
{
 $quiz = session('quiz');
    $total_questions = session('total_questions', 0); // Corrigido: sem $
    $current_question = session('current_question', 1);
    $current_question_index = $current_question - 1;

    // Verificação adicional de segurança
    if (!isset($quiz[$current_question_index])) {
        abort(404, 'Pergunta não encontrada.');
    }

    $answers = $quiz[$current_question_index]['wrong_answers'];
    $answers[] = $quiz[$current_question_index]['correct_answer'];
    shuffle($answers);

    return view('game')->with([
        'country' => $quiz[$current_question_index]['country'],
        'totalQuestions' => $total_questions,
        'currentQuestion' => $current_question,
        'answers' => $answers,
    ]);

}


}
