@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Dodaj produkt</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('myfridges.indexOwn') }}">Wróć</a>
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

<form action="{{ route('products.store') }}" method="POST">
    @csrf

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nazwa:</strong>
                <input type="text" name="name" class="form-control" placeholder="Name">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Expiration Date:</strong>
                <input type="date" name="expiration_date" class="form-control" value="{{ $now->format('Y-m-d') }}" min="{{ $now->subDays(30)->format('Y-m-d') }}" max="{{ $now->addDays(2137)->format('Y-m-d') }}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Lodówka:</strong>
                <select name="fridge_id">
                    @forelse($fridges as $fridge)
                        <option value="{{ $fridge->id }}" @if($def_fridge == $fridge) selected @endif>{{ $fridge->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Kategoria:</strong>
                <select name="product_category_id">
                    <option value="">Brak</option>
                    @forelse($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>
@endsection
