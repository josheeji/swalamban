@extends('layouts.backend.app')

@section('title', 'Audit Log')
@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#sidebar li').removeClass('active');
        $('#sidebar a').removeClass('active');
        $('#sidebar').find('#privilege').addClass('active');
        $('#sidebar').find('#logs').addClass('active');

        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            endDate: '0d'
        });

        $('#tablebody').on('click', '.view-log', function(e) {
            e.preventDefault();
            $object = $(this);
            var id = $object.attr('id').replace(/^\D+/g, '');
            $.ajax({
                type: "GET",
                url: "{{ url('/log/') }}" + "/" + id,
                dataType: 'json',
                success: function(response) {
                    $('#viewLogModal').modal().show();
                    $('#viewLogModal').find(".full_name").text(response.user.full_name);
                    $('#viewLogModal').find(".email_address").text(response.user.email_address);
                    $('#viewLogModal').find(".ip_address").text(response.log.ip_address);
                    $('#viewLogModal').find(".created_at").text(response.log.created_at);

                    $('#viewLogModal').find(".before_details").text(response.log.before_details.split(',').join(' '));

                    $('#viewLogModal').find(".after_details").text(response.log.after_details);
                    $('#viewLogModal').find(".description").text(response.log.description);
                },
                error: function(e) {
                    Swal.fire('Oops...', 'Something went wrong!', 'error');
                }
            });
        });
    });
</script>
@endsection
@section('content')

@endsection