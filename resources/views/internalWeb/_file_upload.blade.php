<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    @if (Session::has('flash_success'))
        <div class="alert alert-success alert-dismissible flash_message" role="alert">
            <strong><i class="icon fa fa-check mr-2"></i></strong> {!! Session::get('flash_success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @endif
    <form class="contact-form internalweb" enctype="multipart/form-data" method="POST" action="{{ route('internal-web.upload') }}">
        {!! csrf_field() !!}
        <div class="career-form internal-form">
        
            <div class="row ">
                <input type="hidden" value="{{ $category->id }}" name="category_id">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <!-- <div class="form-floating mb-3">
                    <input type="text" class="form-control" placeholder="Title" name="title" value="{{ old('title') }}" required="">
                        @if ($errors->has('title'))
                            <div class="error text-danger">{{ $errors->first('title') }}</div>
                        @endif
  <label for="floatingInput">Title <span class="asterisk">*</span></label>
</div> -->
                    <div class="mb-4">
                        <label for="exampleFormControlInput1" class="form-label">Title <span class="asterisk">*</span></label>
                        <input type="text" class="form-control" placeholder="Title" name="title" value="{{ old('title') }}" required="">
                        @if ($errors->has('title'))
                            <div class="error text-danger">{{ $errors->first('title') }}</div>
                        @endif
                    </div>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <div class="mb-4">
                        <label for="floatingTextarea">Attach File <span
                                class="asterisk">*</span></label>
                        <input type="file" class="form-control" placeholder="file"
                            name="file" value="{{ old('file') }}" required="">
                        @if ($errors->has('file'))
                            <div class="error text-danger">{{ $errors->first('file') }}</div>
                        @endif
                    </div>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                    <div class="mb-4">
                        <label for="" class="form-label">Year</label>
                        <select name="year" class="form-control form-select">
                            <option value="">Select Year</option>
                            @foreach (PageHelper::year() as $value)
                                <option value="{{ $value }}">
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('year'))
                            <div class="error text-danger">{{ $errors->first('year') }}</div>
                        @endif
                    </div>

                    <!-- <div class="form-floating">
                        <select class="form-control form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option selected>Select Year</option>
                            @foreach (PageHelper::year() as $value)
                                <option value="{{ $value }}">
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Year</label>
                        @if ($errors->has('year'))
                            <div class="error text-danger">{{ $errors->first('year') }}</div>
                        @endif
                    </div> -->
                </div>

         
                {{-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                    <div class="mb-4">
                        <label for="floatingTextarea">Month <span class="asterisk">*</span></label>
                        <select name="month" class="form-control form-select">
                            <option value="">Select Month</option>
                            @foreach (PageHelper::month() as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('month'))
                            <div class="error text-danger">{{ $errors->first('month') }}</div>
                        @endif
                    </div>

                </div> --}}
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                    <div class="mb-4">
                        <label for="" class="form-label">Category</label>
                        <select name="category_id" class="form-control form-select">
                            <option value="{{$category->id}}">Select Category </option>
                            @foreach ($category->allChild as $child)
                                <option value="{{ $child->id }}">
                                    {{ $child->title }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('month'))
                            <div class="error text-danger">{{ $errors->first('month') }}</div>
                        @endif
                    </div>

                    <!-- <div class="form-floating">
                        <select class="form-control form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option value="{{$category->id}}" selected>Select Category </option>
                            @foreach ($category->allChild as $child)
                                <option value="{{ $child->id }}">{{ $child->title }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Category</label>
                        @if ($errors->has('month'))
                            <div class="error text-danger">{{ $errors->first('month') }}</div>
                        @endif
                    </div> -->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
                    <button class="btn btn-primary tw-mt-30" type="submit">Upload </button>
                </div>
            </div>

        </div>
    </form>
</div>
