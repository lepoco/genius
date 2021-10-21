<div class="h-100 p-5 {{ isset($dark) ? ($dark ? 'text-white bg-dark' : 'bg-light') : 'bg-light' }} -rounded-2">
  <h5>{{ $title ?? 'Title' }}</h5>
  <p>{{ $text ?? 'text' }}</p>
  <a href="{{ $button_link ?? '#' }}" class="btn btn-outline-{{ isset($dark) ? ($dark ? 'light' : 'dark') : 'dark' }}"
    type="button">{{ $button ?? 'Example button' }}</a>
</div>
