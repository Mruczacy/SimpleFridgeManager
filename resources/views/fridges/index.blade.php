@extends('layouts.app')

@section('content')

<div class="margin-bot row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{__('Fridges')}}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('fridges.create') }}">{{__('Create New Fridge')}}</a>
        </div>
    </div>
</div>

<table class="table table-bordered">
    <tr>
        <th>{{__('Name')}}</th>
        <th>{{__('Products')}}</th>
        <th width="280px">{{__('Actions')}}</th>
    </tr>
    @forelse ($fridges as $fridge)
    <tr>
        <td>{{ $fridge->name }}</td>
        <td>
            @forelse ($fridge->products as $product)
                <div>{{__('Name')}}: {{ $product->name }}</div>
                <div>{{__('Expiration Date')}}: {{ $product->expiration_date }}</div>
                <div>{{__('Category')}}: {{ $product->category->name ?? "Brak kategorii"  }}</div>
                @can('isAdmin')
                    <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">{{__('Edit Product')}}</a>
                        <a class="btn btn-primary" href="{{ route('products.moveform',[$product->id,$fridge->id]) }}">{{__('Move Product')}}</a>
                        <button type="submit" class="btn btn-danger">{{__('Delete Product')}}</button>
                    </form>
                @else
                    <form action="{{ route('myproducts.destroyOwn',$product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <a class="btn btn-primary" href="{{ route('myproducts.editOwn',$product->id) }}">{{__('Edit Product')}}</a>
                        <a class="btn btn-primary" href="{{ route('myproducts.moveform',[$product->id,$fridge->id]) }}">{{__('Move Product')}}</a>
                        <button type="submit" class="btn btn-danger">{{__('Delete Product')}}</button>
                    </form>
                @endcan
            @empty
                <div>{{__('There are no products here')}}</div>
                <a class="btn btn-primary" href="{{ route('products.create', $fridge->id) }}">{{__('Throw sth into fridge')}}</a>
            @endforelse
        <td>
            @can('isAdmin')
                <form action="{{ route('fridges.destroy',$fridge->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-secondary" href="{{ route('fridges.show',$fridge->id) }}">{{__('Show Fridge')}}</a>
                    @if(Auth::user()->isFridgeUser($fridge))
                        <a class="btn btn-primary" href="{{  route('products.create', $fridge->id)  }}">{{__('Add Product')}}</a>
                    @endif


                    <a class="btn btn-primary" href="{{ route('fridges.edit',$fridge->id) }}">{{__('Edit Fridge Name')}}</a>
                    <button type="submit" class="btn btn-danger">{{__('Delete Fridge')}}</button>
                </form>
            @else
                <form action="{{ route('myfridges.destroyOwn',$fridge->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-secondary" href="{{ route('myfridges.showOwn',$fridge->id) }}">{{__('Show Fridge')}}</a>
                    @if(Auth::user()->isFridgeUser($fridge))
                        <a class="btn btn-primary" href="{{  route('products.create', $fridge->id)  }}">{{__('Add Product')}}</a>
                    @endif

                    @if(Auth::user()->isFridgeOwner($fridge))
                        <a class="btn btn-primary" href="{{ route('myfridges.editOwn',$fridge->id) }}">{{__('Edit Fridge Name')}}</a>
                        <button type="submit" class="btn btn-danger">{{__('Delete Fridge')}}</button>
                    @endif
                </form>
            @endcan
            @if (Auth::user()->isFridgeUserNoOwner($fridge))
                <form action="{{ route('manage.resign',$fridge->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">{{__('Resign From Fridge')}}</button>
                </form>
            @endif
            @if (Auth::user()->isFridgeOwner($fridge))
                <form action="{{ route('manage.showAManageForm',$fridge->id) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary">{{__('Manage Fridge')}}</button>
                </form>
            @endif
        </td>
    </tr>
    @empty
        <div>{{__('There Are No Fridges Here')}}</div>
    @endforelse
</table>

@endsection
