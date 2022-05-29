@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Data przydatności do spożycia</th>
            <th>Kategoria</th>
            <th>ID Lodówki</th>
            <th width="280px">Akcje</th>
        </tr>
        @forelse ($products as $product)
        <tr>
            <td>ID: {{ $product->id }}</td>
            <td>Nazwa: {{ $product->name }}</td>
            <td>Data przydatności do spożycia: {{ $product->expiration_date }}</td>
            <td>Kategoria: {{ $product->category->name ?? "Brak kategorii"}}</td>
            <td>ID lodówki: {{ $product->fridge_id}}</td>
            <td>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>



                    <button type="submit" class="btn btn-danger">Usuń</button>
                </form>
            </td>
        </tr>
        @empty
            <div>Nie ma jeszcze żadnych produktów</div>
        @endforelse
    </table>


@endsection
