@extends('layouts.main')
@section('content')

<form method="post" action="{{ url('/upload') }}" accept-charset="utf-8" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="avatar-upload">
        <input type='file' name="imageUpload" id="imageUpload" style="display:none;" accept=".png" />                
        <div class="avatar-preview">
            <div id="imagePreview" 
            style="background-image: url(/camera);">
        </div>
    </div>  
    <input type="hidden" value="{{ $id ?? '' }}" name="id">
    <div class="row" style="padding-top: 40px">
        <div class="col text-center">
            <button style="max-width:200px" class="btn btn-lg btn-primary" type="submit">Enviar</button>
        </div>
    </div>
</form>

<script>

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#imagePreview').click(function(){ $('#imageUpload').trigger('click'); });
    $("#imageUpload").change(function() { readURL(this); });

</script>

@endsection