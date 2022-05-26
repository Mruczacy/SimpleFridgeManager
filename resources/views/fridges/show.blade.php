@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Pokaż lodówkę</h2>
            </div>
            <div class="pull-right">
                @can('isAdmin')
                    <a class="btn btn-primary" href="{{  route('fridges.index') }}"> Wróć do lodówek</a>
                @else
                    <a class="btn btn-primary" href="{{  route('myfridges.indexOwn')  }}"> Wróć do lodówek</a>
                @endcan
                <a class="btn btn-primary" href="{{  route('products.create', $fridge)  }}">Dodaj produkt</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ $fridge->name }}</strong>

            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>Nazwa</th>
                    <th width="280px">Akcje</th>
                </tr>
                @foreach ($fridge->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>
                        @can('isAdmin')
                        <form action="{{ route('products.destroy',$product->id) }}" method="POST">

                            <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edytuj</a>
                            <a class="btn btn-primary" href="{{ route('products.moveform',[$product->id,$fridge->id]) }}">Przenieś</a>
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Usuń</button>
                        </form>
                        @else
                        <form action="{{ route('myproducts.destroyOwn',$product->id) }}" method="POST">

                            <a class="btn btn-primary" href="{{ route('myproducts.editOwn',$product->id) }}">Edytuj</a>
                            <a class="btn btn-primary" href="{{ route('myproducts.moveform',[$product->id,$fridge->id]) }}">Przenieś</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Usuń</button>
                        </form>
                        @endcan

                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
