@foreach($parents as $parent)
@if($parent->parent_id == '' || $parent->parent_id == NULL)
<option value="{{ $parent->id }}" {{ ($selected_id == $parent->id) ? "selected" : "" }}> --{{ $parent->title }}</option>
@else
@if(isset($parent->parent) && $parent->parent->parent_id == '')
<option value="{{ $parent->id }}" {{ ($selected_id == $parent->id) ? "selected" : "" }}> ----{{ $parent->title }}</option>
@endif
@endif
@include('admin.internalWebCategory.recursive_options', ['parents' => $parent->child, 'selected_id' => $selected_id ?? ""])
@endforeach
