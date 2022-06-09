@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('Change Name')}}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('categories.index') }}">{{__('Go Back')}}</a>
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
        <form action="{{ route('categories.update',$category->id) }}" method="POST">
        @csrf
        @method('PUT')

         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{__('Name')}}:</strong>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" placeholder="{{__('Name')}}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
            </div>
        </div>

    </form>
@endsection
