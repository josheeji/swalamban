@if(file_exists('storage/' . $news->image) && $news->image != '' && $news->show_image == 1)
<img class="blog-detail-img" alt="" src="{{ asset('storage/'. $news->image ) }}">
@endif
{!! $news->description !!}
@if(!empty($news->document))
<a href="{{ asset('storage/'.$news->document) }}" target="_blank" class="btn"><i class="fa fa-file-pdf"></i> View Document</a>
@endif