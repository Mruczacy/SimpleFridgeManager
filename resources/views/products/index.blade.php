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
            <th>{{__('ID')}}</th>
            <th>{{__('Name')}}</th>
            <th>{{__('Expiration Date')}}</th>
            <th>{{__('Category')}}</th>
            <th>{{__('Fridge ID')}}</th>
            <th width="280px">{{__('Actions')}}</th>
        </tr>
        @forelse ($products as $product)
        <tr>
            <td>{{__('ID')}}: {{ $product->id }}</td>
            <td>{{__('Name')}}: {{ $product->name }}</td>
            <td>{{__('Expiration Date')}}: {{ $product->expiration_date }}</td>
            <td>{{__('Category')}}: {{ $product->category->name ?? __('No Category')}}</td>
            <td>{{__('Fridge ID')}}: {{ $product->fridge_id}}</td>
            <td>
                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">{{__('Edit')}}</a>



                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                </form>
            </td>
        </tr>
        @empty
            <div>{{__('There is no products here')}}</div>
        @endforelse
    </table>


@endsection
