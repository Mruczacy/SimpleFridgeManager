@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('myfridges.indexOwn') }}">Wróć</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                @forelse ($users as $user)
                    @if($user->isFridgeOwner($fridge))
                        <strong>{{ $user->name }}</strong>
                        <strong>Właściciel (nie można go usunąć!)</strong>
                    @else
                        <form action="{{route('manage.detach', [$fridge, $user])}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <strong>{{$user->name}}</strong>
                            <button type="submit" class="btn btn-danger">Usuń użytkownika</button>

                        </form>
                    @endif

                @endforeach
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <h1>Dodaj użytkownika</h1>
                <form action="{{route('manage.attach', $fridge)}}" method="POST">
                    @csrf
                    <div class="input-group">
                        <strong>ID Użytkownika</strong>
                        <input type="text" name="user_id" class="form-control" placeholder="ID Użytkownika">
                        <strong>Menadżer, a może użytkownik?</strong>
                        <select name="is_manager">
                            <option value="1">Menadżer</option>
                            <option value="0" selected>Użytkownik</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Dodaj użytkownika</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        @if(Auth::user()->isFridgeOwner($fridge))
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <h1>Zaaktualizuj rangę użytkownika</h1>
                <form action="{{route('manage.updateUserRank', $fridge)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <strong>Wybierz użytkownika z listy</strong>
                        <select name="user_id">
                            @forelse($users as $user)
                                @if(!$user->isEqualToAuth())<option value="{{$user->id}}">{{"ID: ".$user->id." Nazwa: ".$user->name}}</option>@endif
                            @endforeach
                        </select>
                        <strong>Menadżer, a może użytkownik?</strong>
                        <select name="is_manager">
                            <option value="1">Menadżer</option>
                            <option value="0" selected>Użytkownik</option>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Potwierdź</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <h1>Przekaż status właściciela</h1>
                <form action="{{route('manage.transferOwnership', $fridge)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-group">
                        <strong>Wybierz użytkownika z listy</strong>
                        <select name="owner_id">
                            @forelse($users as $user)
                                @if(!$user->isEqualToAuth())<option value="{{$user->id}}">{{"ID: ".$user->id." Nazwa: ".$user->name}}</option>@endif
                            @endforeach
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Potwierdź</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>


@endsection
