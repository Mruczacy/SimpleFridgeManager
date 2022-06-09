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
                    <th width="280px">{{__('Actions')}}</th>
                </tr>
                @forelse ($fridge->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
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
                    <div>Brak produktów w lodówce</div>
                    <a class="btn btn-primary" href="{{ route('products.create', $fridge->id)}}">{{__('Throw sth into the Fridge')}}</a>
                @endforelse
            </table>
        </div>
    </div>
@endsection
