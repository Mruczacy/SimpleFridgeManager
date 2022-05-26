@extends('layouts.app')

@section('content')

<div class="margin-bot row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Lodówki</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('fridges.create') }}"> Stwórz nową lodówkę</a>
        </div>
    </div>
</div>

<table class="table table-bordered">
    <tr>
        <th>Nazwa</th>
        <th>Produkty</th>
        <th width="280px">Akcje</th>
    </tr>
    @foreach ($fridges as $fridge)
    <tr>
        <td>{{ $fridge->name }}</td>
        <td>
            @foreach ($fridge->products as $product)
                <div>Nazwa: {{ $product->name }}</div>
                <div>Data przydatności do spożycia: {{ $product->expiration_date }}</div>
                <div>Kategoria: {{ $product->category->name ?? "Brak kategorii"  }}</div>
                @can('isAdmin')
                    <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                        <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edytuj produkt</a>
                        <a class="btn btn-primary" href="{{ route('products.moveform',[$product->id,$fridge->id]) }}">Przenieś</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Usuń produkt</button>
                    </form>
                @else
                    <form action="{{ route('myproducts.destroyOwn',$product->id) }}" method="POST">
                        <a class="btn btn-primary" href="{{ route('myproducts.editOwn',$product->id) }}">Edytuj produkt</a>
                        <a class="btn btn-primary" href="{{ route('myproducts.moveform',[$product->id,$fridge->id]) }}">Przenieś</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Usuń produkt</button>
                    </form>
                @endcan
            @endforeach
        <td>
            @can('isAdmin')
                <form action="{{ route('fridges.destroy',$fridge->id) }}" method="POST">
                    <a class="btn btn-secondary" href="{{ route('fridges.show',$fridge->id) }}">Pokaż</a>
                    @if(Auth::user()->isFridgeUser($fridge))
                        <a class="btn btn-primary" href="{{  route('products.create', $fridge->id)  }}">Dodaj produkt</a>
                    @endif

                    @csrf
                    @method('DELETE')
                    <a class="btn btn-primary" href="{{ route('fridges.edit',$fridge->id) }}">Zmień nazwę</a>
                    <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
            @else
                <form action="{{ route('myfridges.destroyOwn',$fridge->id) }}" method="POST">
                    <a class="btn btn-secondary" href="{{ route('myfridges.showOwn',$fridge->id) }}">Pokaż</a>
                    @if(Auth::user()->isFridgeUser($fridge))
                        <a class="btn btn-primary" href="{{  route('products.create', $fridge->id)  }}">Dodaj produkt</a>
                    @endif
                    @csrf
                    @method('DELETE')
                    @if(Auth::user()->isFridgeOwner($fridge))
                        <a class="btn btn-primary" href="{{ route('myfridges.editOwn',$fridge->id) }}">Zmień nazwę</a>
                        <button type="submit" class="btn btn-danger">Usuń</button>
                    @endif
                </form>
            @endcan
            @if (Auth::user()->isFridgeUserNoOwner($fridge))
                <form action="{{ route('manage.resign',$fridge->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Zrezygnuj z korzystania z lodówki</button>
                </form>
            @endif
            @if (Auth::user()->isFridgeOwner($fridge))
                <form action="{{ route('manage.showAManageForm',$fridge->id) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary">Zarządzaj lodówką</button>
                </form>
            @endif
        </td>
    </tr>
    @endforeach
</table>

@endsection
