@extends('layouts.backend.app')
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#validator')
                .formValidation({
                    framework: 'bootstrap',
                    icon: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh'
                    },
                    fields: {
                        page: {
                            validators: {
                                notEmpty: {
                                    message: 'The page is required'
                                }
                            }
                        },
                        meta_title: {
                            validators: {
                                notEmpty: {
                                    message: 'The meta title is required'
                                }
                            }
                        },
                        meta_description: {
                            validators: {
                                notEmpty: {
                                    message: 'The meta description is required'
                                }
                            }
                        },

                    }
                });

            $('#meta_title').NobleCount('#counttitle', {
                max_chars: 70,
                block_negative: true
            });

            $('#meta_description').NobleCount('#countdesc', {
                max_chars: 160,
                block_negative: true
            });
        });

        (function (c) {
            c.fn.NobleCount = function (i, h) {
                var j;
                var g = false;
                if (typeof i == "string") {
                    j = c.extend({}, c.fn.NobleCount.settings, h);
                    if (typeof h != "undefined") {
                        g = ((typeof h.max_chars == "number") ? true : false)
                    }
                    return this.each(function () {
                        var k = c(this);
                        f(k, i, j, g)
                    })
                }
                return this
            };
            c.fn.NobleCount.settings = {
                on_negative: null,
                on_positive: null,
                on_update: null,
                max_chars: 140,
                block_negative: false,
                cloak: false,
                in_dom: false
            };

            function f(g, m, n, h) {
                var l = n.max_chars;
                var j = c(m);
                if (!h) {
                    var k = j.text();
                    var i = (/^[1-9]\d*$/).test(k);
                    if (i) {
                        l = k
                    }
                }
                b(g, j, n, l, true);
                c(g).keydown(function (o) {
                    b(g, j, n, l, false);
                    if (a(o, g, n, l) == false) {
                        return false
                    }
                });
                c(g).keyup(function (o) {
                    b(g, j, n, l, false);
                    if (a(o, g, n, l) == false) {
                        return false
                    }
                })
            }

            function a(k, g, l, j) {
                if (l.block_negative) {
                    var h = k.which;
                    var i;
                    if (typeof document.selection != "undefined") {
                        i = (document.selection.createRange().text.length > 0)
                    } else {
                        i = (g[0].selectionStart != g[0].selectionEnd)
                    }
                    if ((!((e(g, j) < 1) && (h > 47 || h == 32 || h == 0 || h == 13) && !k.ctrlKey && !k.altKey && !i)) == false) {
                        return false
                    }
                }
                return true
            }

            function e(g, h) {
                return h - (c(g).val()).length
            }

            function b(g, i, l, j, h) {
                var k = e(g, j);
                if (k < 0) {
                    d(l.on_negative, l.on_positive, g, i, l, k)
                } else {
                    d(l.on_positive, l.on_negative, g, i, l, k)
                }
                if (l.cloak) {
                    if (l.in_dom) {
                        i.attr("data-noblecount", k)
                    }
                } else {
                    i.text(k)
                }
                if (!h && jQuery.isFunction(l.on_update)) {
                    l.on_update(g, i, l, k)
                }
            }

            function d(i, g, h, j, l, k) {
                if (i != null) {
                    if (typeof i == "string") {
                        j.addClass(i)
                    } else {
                        if (jQuery.isFunction(i)) {
                            i(h, j, l, k)
                        }
                    }
                }
                if (g != null) {
                    if (typeof g == "string") {
                        j.removeClass(g)
                    }
                }
            }
        })(jQuery);
    </script>
@endsection

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> -
                    Seo</h4>
            </div>
        </div>
        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="icon-home2 position-left"></i> Home</a>
                </li>
                <li class="active">Seo</li>
            </ul>
        </div>
    </div>
@endsection
@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title"><i class="icon-file-plus position-left"></i>Update Seo</h5>
            <div class="heading-elements">
                <a href="{{ route('admin.seos.index') }}" class="btn btn-default legitRipple pull-right">
                    <i class="icon-undo2 position-left"></i>
                    Back
                    <span class="legitRipple-ripple"></span>
                </a>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(array('route' => ['admin.seos.update', $seo->id],'class'=>'form-horizontal','id'=>'validator', 'files' => 'true')) !!}
            <fieldset class="content-group">

                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="control-label col-lg-2">Page *</label>
                    <div class="col-lg-6">
                        <span>{!! $seo->page !!}</span>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="control-label col-lg-2">Meta Title</label>
                    <div class="col-lg-6">
                        {!! Form::textarea('meta_title', $seo->meta_title, array('class'=>'form-control m-input','placeholder'=>'Meta Title','rows'=>3,'id'=>'meta_title')) !!}
                        <p class="text-primary">You have <span id="counttitle">70</span> characters left. </p>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="control-label col-lg-2">Meta Description</label>
                    <div class="col-lg-6">
                        {!! Form::textarea('meta_description', $seo->meta_description, array('class'=>'form-control m-input','placeholder'=>'Meta Description','rows'=>3,'id'=>'meta_description')) !!}
                        <p class="text-primary">You have <span id="countdesc">160</span> characters left. </p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </fieldset>
            <div class="text-left col-lg-offset-2">
                <button type="submit" class="btn btn-primary legitRipple">
                    Update <i class="icon-arrow-right14 position-right"></i></button>
            </div>
            {!! method_field('PUT') !!}
            {!! Form::close() !!}
        </div>
    </div>
@endsection