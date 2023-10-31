@extends('layouts.main')
@section('content')

    <?php phpinfo(); ?>

    <p>{{ $msg ?? '' }}</p>
    <div class="row">
        <div class="tag-body">
            <div class="tag-border reserva">
                <span class="tag-content">
                    <span class="fa fa-luggage-cart"></span>            
                    <span style="font-size: 16px" class="tag-title">MDE Informática</span><br />
                    <span class="tag-description">+55 11 9999-9999</span>
                    <span class="tag-description">contato@mdeinformatica.com.br</span>                    
                </span>
            </div>
        </div>
    </div>
@endsection