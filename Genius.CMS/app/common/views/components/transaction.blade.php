@php
  $method = \App\Common\Money\TransactionsRepository::getMethodName($transaction->getMethodId());
  $type = \App\Common\Money\TransactionsRepository::getTypeName($transaction->getTypeId());
  $currency = $transaction->getWalletTo()->getCurrency();

  $methodName = \App\Common\Money\PaymentMethods::getName($method);

  if ('topup' === $type) {
    $header = \App\Core\Facades\Translate::string('Top-up via') . ' ' . $methodName;
  }
@endphp

<div class="-w-100 -reveal">
  <a href="{{ $transaction->getUrl() ?? '#' }}">
    <div class="transactions__single">
      <div>
        <img src="@asset('img/pexels-watch-pay.jpeg')" alt="image">
      </div>

      <span>
        <p><strong>{{ $header ?? '' }}</strong></p>
        @if('topup' === $type)
        <span>{{ $transaction->getCreatedAt() ?? '' }}</span>
        @else
        <span>
          @translate('From') <strong>US@you</strong> @translate('to') <strong>EUR@you</strong> - {{ $transaction->getCreatedAt() ?? '' }}
        </span>
        @endif
      </span>

      <strong>
        {{ $currency->isSignLeft() ? $currency->getSign() : '' }} {{ number_format((float) $transaction->getAmount() ?? 0, 2, '.', '') }}
        {{ !$currency->isSignLeft() ? $currency->getSign() : '' }}
      </strong>
    </div>
  </a>
</div>