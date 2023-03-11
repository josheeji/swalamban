{!! $content->description !!}
@if (file_exists('storage/' . $content->image) && $content->image != '' && $content->show_image == 1)
    <img class="img-fluid" alt="Image is of {{ $content->title }}" src="{{ asset('storage/' . $content->image) }}">
@endif
