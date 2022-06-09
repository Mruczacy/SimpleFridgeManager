@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{__('Add Product')}}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('myfridges.indexOwn') }}">{{__('Go Back')}}</a>
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

<form action="{{ route('products.store') }}" method="POST">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Name')}}:</strong>
                <input type="text" name="name" class="form-control" placeholder="{{__('Name')}}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Expiration Date')}}:</strong>
                <input type="date" name="expiration_date" class="form-control" value="{{ $now->format('Y-m-d') }}" min="{{ $now->subDays(30)->format('Y-m-d') }}" max="{{ $now->addDays(2137)->format('Y-m-d') }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('A Fridge')}}:</strong>
                <select name="fridge_id">
                    @foreach($fridges as $fridge)
                        <option value="{{ $fridge->id }}" @if($def_fridge == $fridge) selected @endif>{{ $fridge->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{__('Category')}}:</strong>
                <select name="product_category_id">
                    <option value="">{{__('No')}}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
        </div>
    </div>

</form>
@endsection
