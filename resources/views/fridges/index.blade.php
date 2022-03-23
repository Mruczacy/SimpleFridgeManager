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
        <th width="280px">Akcje</th>
    </tr>
    @foreach ($fridges as $fridge)
    <tr>
        <td>{{ $fridge->name }}</td>
        <td>
            @can('isAdmin')
                <form action="{{ route('fridges.destroy',$fridge->id) }}" method="POST">
                    <a class="btn btn-secondary" href="{{ route('fridges.show',$fridge->id) }}">Pokaż</a>
                    <a class="btn btn-primary" href="{{ route('fridges.edit',$fridge->id) }}">Zmień nazwę</a>
                    <a class="btn btn-primary" href="{{  route('products.create', $fridge->id)  }}">Dodaj produkt</a>
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
            @else
                <form action="{{ route('myfridges.destroyOwn',$fridge->id) }}" method="POST">
                    <a class="btn btn-secondary" href="{{ route('myfridges.showOwn',$fridge->id) }}">Pokaż</a>
                    <a class="btn btn-primary" href="{{ route('myfridges.editOwn',$fridge->id) }}">Zmień nazwę</a>
                    <a class="btn btn-primary" href="{{  route('products.create', $fridge->id)  }}">Dodaj produkt</a>
                    @csrf
                    @method('DELETE')
                    @if(Auth::user()->isFridgeOwner($fridge))
                        <button type="submit" class="btn btn-danger">Usuń</button>
                    @endif
                </form>
            @endcan

        </td>
    </tr>
    @endforeach
</table>
{!! $fridges->links() !!}

@endsection
