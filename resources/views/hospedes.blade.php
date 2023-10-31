@extends('layouts.main')
@section('content')

    @include('layouts.reserva')

    <div class="row">
        <?php $i=1; ?>
        @foreach ($viewModel->hospedes() as $hospede)
            <?php $semNome = $hospede->semNome() ? 'sem-nome' : ''; ?>
            <div class="tag-body"
                onclick="location.href='/hospede/{{ $viewModel->reserva()->ChaveIdentificacao }}/{{ $hospede->NrHospede }}/{{ $i }}'">
                <div class="tag-border hospede {{ $semNome }}">
                    <span class="tag-content">
                        <span class="fa fa-user"></span>            
                        <span class="tag-title">Hóspede nº {{ $i++ }}</span>                    
                        <span style="font-size: 16px;" class="tag-description">{{ $hospede->NomeHospede() }}</span>
                    </span>
                </div>
            </div>
            <!-- 
            <a href="/mde/hospede/{{ $viewModel->reserva()->NrReserva }}/{{ $hospede->Id }}">{{ $hospede->Nome }}</li>
            -->
        @endforeach
    </div>

    <a class="btn btn-lg btn-secondary" role="button"  href="/reserva/{{ $viewModel->reserva()->ChaveIdentificacao }}">
        Voltar
    </a>

@endsection