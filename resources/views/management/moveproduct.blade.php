@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('myfridges.showOwn', $def_fridge->id) }}">Wróć</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                @can('isAdmin')
                <form action="{{route('products.move',$product->id)}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="input-group">
                        <strong>Lodówka</strong>
                        <select name="fridge_id">
                            @foreach($fridges as $fridge)
                                <option value="{{ $fridge->id }}" @if($def_fridge == $fridge) selected @endif>{{ $fridge->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                @else
                <form action="{{route('myproducts.move',$product->id)}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="input-group">
                        <strong>Lodówka</strong>
                        <select name="fridge_id">
                            @foreach($fridges as $fridge)
                                <option value="{{ $fridge->id }}" @if($def_fridge == $fridge) selected @endif>{{ $fridge->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Zmień</button>
                </form>
                @endcan
            </div>
        </div>
    </div>


@endsection
