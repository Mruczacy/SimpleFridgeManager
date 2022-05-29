@extends('layouts.app')

@section('content')

<div class="margin-bot row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Dodawanie kategorii</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('categories.create') }}"> Stwórz nową kategorię</a>
        </div>
    </div>
</div>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Nazwa</th>
        <th width="280px">Akcje</th>
    </tr>
    @forelse ($categories as $category)
    <tr>
        <td>{{ $category->id }}</td>
        <td>{{ $category->name }}</td>
        <td>
            <form action="{{ route('categories.destroy',$category->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <a class="btn btn-primary" href="{{ route('categories.edit',$category->id) }}">Edytuj</a>
                <button type="submit" class="btn btn-danger">Usuń</button>
            </form>
        </td>
    </tr>
    @empty
        <div>Brak kategorii</div>
        <a class="btn btn-primary" href="{{route('categories.create')}}">Nie bądź żyd stwórz jakąś</a>
    @endforelse
</table>
{!! $categories->links() !!}

@endsection
