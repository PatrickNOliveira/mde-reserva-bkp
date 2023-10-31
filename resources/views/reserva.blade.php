@extends('layouts.main')
@section('content')

    @include('layouts.reserva')
    
    <div class="row">
        <div class="tag-body">
            <div class="tag-border hospedes">
                <span class="tag-content">
                    <span class="fa fa-users"></span>            
                    <span class="tag-title">Ocupação</span>
                    <span class="tag-description">{{ $viewModel->reserva()->ocupacao() }}</span>
                </span>
            </div>
        </div>
        <div class="tag-body">
            <div class="tag-border check-in">
                <span class="tag-content">
                    <span class="fa fa-calendar-alt"></span>            
                    <span class="tag-title">Check-in</span>
                    <span class="tag-description">{{ $viewModel->reserva()->checkin() }}</span>
                </span>
            </div>
        </div>
        <div class="tag-body">
            <div class="tag-border check-out">
                <span class="tag-content">
                    <span class="fa fa-calendar-alt"></span>            
                    <span class="tag-title">Check-out</span>
                    <span class="tag-description">{{ $viewModel->reserva()->checkout() }}</span>
                </span>
            </div>
        </div>
    </div>
    <a class="btn btn-lg btn-primary" role="button" 
        href="/hospedes/{{ $viewModel->reserva()->ChaveIdentificacao }}">Hóspedes
    </a>
@endsection