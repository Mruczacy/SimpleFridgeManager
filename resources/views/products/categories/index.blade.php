@extends('layouts.app')

@section('content')

<div class="margin-bot row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{__('Categories')}}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('categories.create') }}">{{__('Add New Category')}}</a>
        </div>
    </div>
</div>

<table class="table table-bordered">
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Name')}}</th>
        <th width="280px">{{__('Actions')}}</th>
    </tr>
    @forelse ($categories as $category)
    <tr>
        <td>{{ $category->id }}</td>
        <td>{{ $category->name }}</td>
        <td>
            <form action="{{ route('categories.destroy',$category->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <a class="btn btn-primary" href="{{ route('categories.edit',$category->id) }}">{{__('Edit')}}</a>
                <button type="submit" class="btn btn-danger">{{__("Delete")}}</button>
            </form>
        </td>
    </tr>
    @empty
        <div>{{__('No Categories')}}</div>
        <a class="btn btn-primary" href="{{route('categories.create')}}">{{__('Dont be mean, create one')}}</a>
    @endforelse
</table>
{!! $categories->links() !!}

@endsection
