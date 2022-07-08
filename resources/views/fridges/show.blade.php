@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('A Fridge')}}</h2>
            </div>
            <div class="pull-right">
                @can('isAdmin')
                    <a class="btn btn-primary" href="{{  route('fridges.index') }}">{{__('Go Back To The Fridges List')}}</a>
                @else
                    <a class="btn btn-primary" href="{{  route('myfridges.indexOwn')  }}">{{__('Go Back To The Fridges List')}}</a>
                @endcan
                <a class="btn btn-primary" href="{{  route('products.create', $fridge)  }}">{{__('Add Product')}}</a>
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
                    <th>{{__('Name')}}</th>
                    <th>{{__('Expiration Date')}}</th>
                    <th>{{__('Category')}}</th>
                    <th width="280px">{{__('Actions')}}</th>
                </tr>
                @forelse ($fridge->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td @if ($product->trashTresholdHit()) class="throw-it" @elseif ($product->asapTresholdHit()) class="eat-asap" @elseif ($product->inNearFutureTresholdHit()) class="in-near-future" @else class="long-term" @endif>{{ $product->expiration_date }}</td>
                    <td> @isset($product->category) {{__('Category')}}: @endisset {{ $product->category->name ?? __('No Category')  }}</td>
                    <td>
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

                    </td>
                </tr>
                @empty
                    <div>{{__('There are no products here')}}</div>
                    <a class="btn btn-primary" href="{{ route('products.create', $fridge->id)}}">{{__('Throw sth into the Fridge')}}</a>
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
        </div>
    </div>
@endsection
