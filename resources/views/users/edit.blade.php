@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('Edit User')}}</h2>
            </div>
            <div class="pull-right">
                @can('isAdmin')
                <a class="btn btn-primary" href="{{ route('users.index') }}">{{__('Go Back')}}</a>
                @endcan
                @can('isUser')
                <a class="btn btn-primary" href="{{ route('home') }}">{{__('Go Back')}}</a>
                @endcan
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{__('OOPS')}}</strong>{{__('There is a problem with your input')}}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @can('isAdmin')
        <form action="{{ route('users.update',$user->id) }}" method="POST">
    @else
        <form action="{{ route('users.updateOwn',$user->id) }}" method="POST">
    @endcan
        @csrf
        @method('PUT')

         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{__('Name')}}:</strong>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="{{__('Name')}}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{__('Email Address')}}:</strong>
                    <textarea class="form-control" style="height:150px" name="email" placeholder="{{__('Email Address')}}">{{ $user->email }}</textarea>
                </div>
            </div>
            @can('isAdmin')
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{__('Roles')}}:</strong>
                        <select name="role">
                            <option value="{{$roles::ADMIN}}" @if($user->isActualRank($roles::ADMIN)) selected @endif>{{ $roles::ADMIN }}</option>
                            <option value="{{$roles::USER}}" @if($user->isActualRank($roles::USER)) selected @endif>{{ $roles::USER }}</option>
                        </select>
                    </div>
                </div>
            @endcan
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            </div>
        </div>

    </form>
@endsection
