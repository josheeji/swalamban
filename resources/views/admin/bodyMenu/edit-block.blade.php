<div class="card-block" data-index="{{ $index }}">
    <div class="panel">
        <div class="panel-heading">
            <div class="heading-elements" style="margin-left: 1%">
                <a href="javascript:void(0)" class="btn btn-danger btn-icon btn-rounded btn-remove-blockk-{{ $index }}"
                   data-id="{{$index}}" data-index="{{ $index }}"><i class="la la-trash"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-lg-2">Contact Person Name</label>
                <div class="col-lg-10">
                    <input type="text" name="contact[{{ $index }}][contact_person]"
                           class="form-control" value="{{ isset($block) ? $block->contact_person : '' }}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">Contact Number</label>
                <div class="col-lg-10">
                    <input type="number" name="contact[{{ $index }}][contact_number]"
                           class="form-control" value="{{ isset($block) ? $block->contact_number : '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
{{--@section('add-js')--}}
    {{--<script>--}}
        {{--$('.btn-remove-block-{{ $index }}').on('click', function () {--}}
            {{--var index = $(this).data('index');--}}
            {{--var id = $(this).data('id');--}}
            {{--Swal.fire({--}}
                {{--title: 'Are you sure?',--}}
                {{--text: 'Do you want to remove the content block.',--}}
                {{--type: 'warning',--}}
                {{--showCancelButton: true,--}}
                {{--confirmButtonText: 'Yes, remove it!',--}}
                {{--cancelButtonText: 'No, keep it!'--}}
            {{--}).then((result) => {--}}
                {{--if (result.value) {--}}
                    {{--if (id != '') {--}}
                        {{--$.ajax({--}}
                            {{--type: "POST",--}}
                            {{--url: "{{ route('admin.asba.remove-block') }}",--}}
                            {{--data: {--}}
                                {{--'id': id,--}}
                                {{--'index': index,--}}

                            {{--},--}}
                            {{--dataType: 'html',--}}
                            {{--success: function (response) {--}}
                                {{--if (response != 'success') {--}}
                                    {{--swal("Oops!", 'Cannot remove the content block.', "error");--}}
                                    {{--return;--}}
                                {{--}--}}
                            {{--},--}}
                            {{--error: function (e) {--}}
                            {{--}--}}
                        {{--});--}}
                    {{--}--}}
                    {{--$(this).closest('.card-block').remove();--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}
{{--@endsection--}}