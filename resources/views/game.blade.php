<x-main-layout pageTitle="Países e Capitais">


    <div class="container">
        <x-perguntas :country="$country" :currentQuestion="$currentQuestion" :totalQuestions="$totalQuestions" />

        <div class="row">

            {{-- São as capitais da pergunta --}}
         @foreach ($answers as $answer)
             <x-answer :capital="$answer" />
         @endforeach

        </div>

    </div>

    <!-- cancel game -->
    <div class="text-center mt-5">
        <a href="#" class="btn btn-outline-danger mt-3 px-5">CANCELAR JOGO</a>
    </div>

</x-main-layout>
