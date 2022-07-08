@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{__('Create New Fridge')}}</h2>
        </div>
        <div class="pull-right">
            @can('isAdmin')
                <a class="btn btn-primary" href="{{  route('fridges.index') }}">{{__('Go Back To The Fridges List')}}</a>
            @else
                <a class="btn btn-primary" href="{{  route('myfridges.indexOwn')  }}">{{__('Go Back To The Fridges List')}}</a>
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

<form action="{{ route('fridges.store') }}" method="POST">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Fridge Name')}}</strong>
                <input type="text" name="name" class="form-control" placeholder="{{__('Name')}}" required>
            </div>
        </div>
        <strong class="padding-vertical-10" >{{__('Warning Tresholds')}}</strong>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Throw it out')}}</strong>
                <input type="number" name="throw_it_out_treshold" class="form-control" placeholder="{{__('Throw it out')}}" value="0" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Eat Asap')}}</strong>
                <input type="number" name="asap_treshold" class="form-control" placeholder="{{__('Eat Asap')}}" value="2" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('In Near Future')}}</strong>
                <input type="number" name="in_near_future_treshold" class="form-control" placeholder="{{__('In Near Future')}}" value="7" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
        </div>
    </div>

</form>
@endsection
