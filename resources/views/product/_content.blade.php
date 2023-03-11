<div class="leftsection">
@if (file_exists('storage/thumbs/' . $product->image) && $product->image != '' && $product->show_image)
<p><img class="mb-3" src="{{ asset('storage/thumbs/'. $product->image) }}" alt=""></p>
@endif
{!! $product->description !!}

@if (isset($product->download_id) && !empty($product->download_id) && $product->download)
    <a href="{{ asset('storage/' . $product->download->file) }}"
        class="product-btn3 mb-2 w-25">{{ trans('general.download-form') }} <i class="fas fa-download"></i></a>
@endif
</div>