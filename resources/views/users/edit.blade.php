@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edytuj użytkownika</h2>
            </div>
            <div class="pull-right">
                @can('isAdmin')
                <a class="btn btn-primary" href="{{ route('users.index') }}">Wróć</a>
                @endcan
                @can('isUser')
                <a class="btn btn-primary" href="{{ route('home') }}">Wróć</a>
                @endcan
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @can('isAdmin')
    a
        <form action="{{ route('users.update',$user->id) }}" method="POST">
    @else
        <form action="{{ route('users.updateOwn',$user->id) }}" method="POST">
    @endcan
        @csrf
        @method('PUT')

         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nazwa:</strong>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email:</strong>
                    <textarea class="form-control" style="height:150px" name="email" placeholder="Email">{{ $user->email }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Potwierdź</button>
            </div>
        </div>

    </form>
@endsection
