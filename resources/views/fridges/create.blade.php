@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Dodaj nową lodówkę</h2>
        </div>
        <div class="pull-right">
            @can('isAdmin')
                <a class="btn btn-primary" href="{{  route('fridges.index') }}"> Wróć do lodówek</a>
            @else
                <a class="btn btn-primary" href="{{  route('myfridges.indexOwn')  }}"> Wróć do lodówek</a>
            @endcan
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>KURWAAAAAA!</strong> Jest problem z podanymi przez ciebie informacjami.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('fridges.store') }}" method="POST">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nazwa:</strong>
                <input type="text" name="name" class="form-control" placeholder="Nazwa">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Potwierdź</button>
        </div>
    </div>

</form>
@endsection
