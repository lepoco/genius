@extends('layouts.app', [
'title' => 'Payments'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
    <div class="row">
        <div class="col-12">
            <h4 class="-font-secondary -fw-700 -pb-3">@translate('Payments')</h4>
          </div>
        <div class="col-12 dashboard__section">
            <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
              <div>
                <h4 class="-mb-2">&commat;{{ $user->getName() ?? 'user' }}</h4>

                <a href="@url('dashboard/send')" class="btn btn-outline-dark btn-mobile -lg-mr-1">@translate('Send') &xrarr;</a>
                <a href="@url('dashboard/request')" class="btn btn-outline-dark btn-mobile">&xlarr; @translate('Request')</a>
              </div>
            </div>
          </div>
        <div class="col-12">
            <div class="timeline -mt-3 -mb-3">
                <div class="timeline--wrapper">
                    @php

                    $transactions = [
                    '2021.06.01' => [
                    ['time' => '17:55', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '17:43', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '17:13', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '16:43', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '16:33', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '15:33', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '11:33', 'currency' => 'EUR', 'amount' => 55.33]
                    ],
                    '2021.07.30' => [
                    ['time' => '17:55', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '17:43', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '17:13', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '16:43', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '16:33', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '15:33', 'currency' => 'EUR', 'amount' => 55.33],
                    ['time' => '11:33', 'currency' => 'EUR', 'amount' => 55.33]
                    ]
                    ];

                    @endphp
                    <div class="a-s--group-by-date">
                        @foreach ($transactions as $group => $transactionsPack)
                        <div class="a-s--title">
                            <h3>{{ $group }}</h3>
                        </div>

                        @foreach ($transactionsPack as $transaction)
                        <div class="a-s--item">
                            <div class="a-s--time">
                                {{ $transaction['time'] ?? '--:--' }}
                            </div>
                            <div class="a-s--icon">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </div>
                            <div class="a-s--body">
                                <h4>{{ $transaction['amount'] ?? '--:--' }}</h4>
                                <p>{{ $transaction['currency'] ?? '--:--' }}</p>
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
