@extends('frontend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang('Enter a char for each space delimited card, 2 - 10, j, q, k, a')<br />
                        @lang('Choose cards higher than your opponent (i.e. computer) to win')
                    </x-slot>

                    <x-slot name="body">
                            <play-card :plays="{{$plays}}" :scores="{{$scores}}"></play-card>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-12-->
        </div><!--row-->
    </div><!--container-->

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang('Leader Board')<br />
                    </x-slot>

                    <x-slot name="body">
                        <table class="col-md-12">
                            <tr><th>Player</th><th>Games Played</th><th>Games Won</th></tr>
                            @foreach (json_decode($totals, true, 512, JSON_THROW_ON_ERROR) as $total)
                                <tr>
                                    <td>{{$total['username']}}</td>
                                    <td>{{$total['plays']}}</td>
                                    <td>{{$total['wins']}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-12-->
        </div><!--row-->
    </div><!--container-->
@endsection
