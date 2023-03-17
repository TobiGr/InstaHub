@extends('errors.layout')

@section('title', __('No Ad!'))

@section('message', __('messages.missedAd'))

@section('help')
    <div class="message-noad">{!!__('messages.noad')!!}</div>
    <div class="message-back">
        <a class="btn btn-primary" href="javascript:history.back()">{!!__('back')!!}</a>
    </div>
@endsection

