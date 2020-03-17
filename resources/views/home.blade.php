@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (!is_null($revenueChart))
                        {!! $revenueChart->renderHtml() !!}
                    @else
                        You are logged in!
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent

@if (!is_null($revenueChart))
{!! $revenueChart->renderChartJsLibrary() !!}
{!! $revenueChart->renderJs() !!}
@endif

@endsection
