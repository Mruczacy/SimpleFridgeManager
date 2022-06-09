@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('myfridges.indexOwn') }}">{{__('Go Back')}}</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                @foreach ($users as $user)
                    @if($user->isFridgeOwner($fridge))
                        <strong>{{ $user->name }}</strong>
                        <strong>{{__('Owner (You Cannot Detach Him)')}}</strong>
                    @else
                        <form action="{{route('manage.detach', [$fridge, $user])}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <strong>{{$user->name}}</strong>
                            <button type="submit" class="btn btn-danger">{{__('Detach User')}}</button>

                        </form>
                    @endif

                @endforeach
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <h1>{{__('Attach User')}}</h1>
                <form action="{{route('manage.attach', $fridge)}}" method="POST">
                    @csrf
                    <div class="input-group">
                        <strong>{{__('User ID')}}</strong>
                        <input type="text" name="user_id" class="form-control" placeholder="ID Użytkownika">
                        <strong>{{__('Manager Or User?')}}</strong>
                        <select name="is_manager">
                            <option value="1">{{__('Manager')}}</option>
                            <option value="0" selected>{{__('User')}}</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">{{__('Attach User')}}</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        @if(Auth::user()->isFridgeOwner($fridge))
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <h1>{{__('Update User Rank')}}</h1>
                <form action="{{route('manage.updateUserRank', $fridge)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <strong>{{__('Select User From List')}}</strong>
                        <select name="user_id">
                            @foreach($users as $user)
                                @if(!$user->isEqualToAuth())<option value="{{$user->id}}">{{"ID: ".$user->id." Nazwa: ".$user->name}}</option>@endif
                            @endforeach
                        </select>
                        <strong>{{__('Manager Or User?')}}</strong>
                        <select name="is_manager">
                            <option value="1">{{__('Manager')}}</option>
                            <option value="0" selected>{{__('User')}}</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <h1>{{__('Transfer Ownership')}}</h1>
                <form action="{{route('manage.transferOwnership', $fridge)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <strong>{{__('Manager Or User?')}}</strong>
                        <select name="owner_id">
                            @foreach($users as $user)
                                @if(!$user->isEqualToAuth())<option value="{{$user->id}}">{{"ID: ".$user->id." Nazwa: ".$user->name}}</option>@endif
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>


@endsection
