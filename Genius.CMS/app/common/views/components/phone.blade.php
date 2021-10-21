<section class="phone">
    <div class="phone__container">
        @if(isset($notch) && true === $notch)
        <div class="phone__notch">
            <div class="phone__notch-body"></div>
        </div>
        @endif

        <img loading="lazy" src="{{ $image ?? '' }}" alt="Phone device content">
    </div>
</section>
