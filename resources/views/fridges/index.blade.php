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
        <th>{{__('Treshold')}}: {{__('Throw it out')}}</th>
        <th>{{__('Treshold')}}: {{__('Eat Asap')}}</th>
        <th>{{__('Treshold')}}: {{__('In Near Future')}}</th>
        <th>{{__('Products')}}</th>
        <th width="280px">{{__('Actions')}}</th>
    </tr>
    @forelse ($fridges as $fridge)
    <tr>
        <td>{{ $fridge->name }}</td>
        <td>{{ $fridge->throw_it_out_treshold }}</td>
        <td>{{ $fridge->asap_treshold }}</td>
        <td>{{ $fridge->in_near_future_treshold }}</td>
        <td>
            <table class="table table-bordered">
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Expiration Date')}}</th>
                    <th>{{__('Category')}}</th>
                    <th width="280px">{{__('Actions')}}</th>
                </tr>
                @forelse ($fridge->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td @if ($product->trashTresholdHit()) class="throw-it" @elseif ($product->asapTresholdHit()) class="eat-asap" @elseif ($product->inNearFutureTresholdHit()) class="in-near-future" @else class="long-term" @endif>{{ $product->expiration_date }}</td>
                        <td>{{ $product->category->name ?? __('No Category') }}</td>
                            @can('isAdmin')
                                <td>
                                    <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">{{__('Edit Product')}}</a>
                                        <a class="btn btn-primary" href="{{ route('products.moveform',[$product->id,$fridge->id]) }}">{{__('Move Product')}}</a>
                                        <button type="submit" class="btn btn-danger">{{__('Delete Product')}}</button>
                                    </form>
                                </td>
                            @else
                                <td>
                                    <form action="{{ route('myproducts.destroyOwn',$product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-primary" href="{{ route('myproducts.editOwn',$product->id) }}">{{__('Edit Product')}}</a>
                                        <a class="btn btn-primary" href="{{ route('myproducts.moveform',[$product->id,$fridge->id]) }}">{{__('Move Product')}}</a>
                                        <button type="submit" class="btn btn-danger">{{__('Delete Product')}}</button>
                                    </form>
                                </td>
                            @endcan
                    </tr>

                @empty
                    <div>{{__('There are no products here')}}</div>
                    <a class="btn btn-primary" href="{{ route('products.create', $fridge->id) }}">{{__('Throw sth into fridge')}}</a>
                @endforelse
                </table>
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
<table class="table table-bordered">
    <tr>
        <th>{{__('Color')}}</th>
        <th>{{__('Meaning')}}</th>
    </tr>
    <tr>
        <td class="throw-it-back"></td>
        <td>{{__('Throw it out')}}</td>
    </tr>
    <tr>
        <td class="eat-asap-back"></td>
        <td>{{__('Eat Asap')}}</td>
    </tr>
    <tr>
        <td class="in-near-future"></td>
        <td>{{__('In Near Future')}}</td>
    </tr>
    <tr>
        <td class="long-term"></td>
        <td>{{__('Long Term')}}</td>
    </tr>
</table>
@endsection
