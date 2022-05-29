@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edytuj produkt</h2>
            </div>
            <div class="pull-right">
                @can('isAdmin')
                    <a class="btn btn-primary" href="{{ route('fridges.index') }}">Wróć (lodówki)</a>
                    <a class="btn btn-primary" href="{{ route('products.index') }}">Wróć (produkty)</a>
                @else
                    <a class="btn btn-primary" href="{{ route('myfridges.indexOwn') }}">Wróć</a>
                @endcan

            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @forelse ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @can('isAdmin')
        <form action="{{ route('products.update',$product->id) }}" method="POST">
    @else
        <form action="{{ route('myproducts.updateOwn',$product->id) }}" method="POST">
    @endcan
            @csrf
            @method('PUT')

         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nazwa:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Nazwa" value="{{ $product->name }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Expiration Date:</strong>
                    <input type="date" name="expiration_date" class="form-control" value="{{ $manipulate_date->format('Y-m-d') }}" min="{{ $manipulate_date->subDays(30)->format('Y-m-d') }}" max="{{ $manipulate_date->addDays(2137)->format('Y-m-d') }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Lodówka:</strong>
                    <select name="fridge_id">
                        @forelse($fridges as $fridge)
                            <option value="{{ $fridge->id }}" @if($product->isActualFridge($fridge)) selected @endif>{{ $fridge->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Kategoria:</strong>
                    <select name="product_category_id">
                        @forelse($categories as $category)
                            <option value="{{ $category->id }}" @if($product->isActualCategory($category)) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Potwierdź</button>
            </div>
        </div>

    </form>
@endsection
