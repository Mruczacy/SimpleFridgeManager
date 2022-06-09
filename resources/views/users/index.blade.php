@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('User List')}}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('users.showMyAccount', Auth::id()) }}">{{__('My Account')}}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>{{__('ID')}}:</th>
            <th>{{__('Name')}}:</th>
            <th>{{__('Email Address')}}:</th>
            <th width="280px">{{__('Actions')}}</th>
        </tr>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">{{__('Edit')}}</a>



                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

@endsection
