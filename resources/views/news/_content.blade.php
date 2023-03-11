<div class="col-md-9 pd-right">
    @if(file_exists('storage/' . $news->image) && $news->image != '' && $news->show_image == 1)
    <p><img class="img-fluid" alt="News Image for Surya Jyoti Life Insurance" src="{{ asset('storage/'. $news->image ) }}"></p>
    @endif

    {!! $news->description !!}
    @if(!empty($news->document))
    <a href="{{ asset('storage/'.$news->document) }}" target="_blank" class="btn btn-primary"><i
            class="fa fa-file-pdf"></i> View Document</a>
    @endif

    <div class="sharethis-inline-share-buttons"></div>
</div>