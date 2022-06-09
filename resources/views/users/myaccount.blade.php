@extends('layouts.app')

@section('content')
<div class="container text-center">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('My Account')}}</h2>
            </div>
            <form action="{{ route('users.destroyOwn',$user->id) }}" method="POST">
                @csrf
                @method('DELETE')
            <div class="pull-left">
                <button class="btn btn-danger" type="submit" }}">{{__('Delete Your Account')}}</button>
            </div>
                <div class="pull-right">
                <a class="btn btn-warning" href="{{ route('users.editOwn', $user) }}">{{__('Edit Your Account')}}</a>
            </div>
            </form>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('home') }}">{{__('Go Back')}}</a>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Your ID')}}:</strong>
                {{ $user->id }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Name')}}:</strong>
                {{ $user->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Email Address')}}:</strong>
                {{ $user->email }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Role')}}:</strong>
                {{ $user->role }}
            </div>
        </div>
    </div>
</div>
@endsection
