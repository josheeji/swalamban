@inject('menuRepo', 'App\Repositories\MenuRepository')
@extends('layouts.backend.app')
@section('title', 'Manage - ' . $menu->title)
@section('styles')
    <link href="{{ asset('backend/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dd {
            position: relative;
            display: block;
            margin: 0;
            padding: 0;
            max-width: 600px;
            list-style: none;
            font-size: 13px;
            line-height: 20px;
        }

        .dd-list {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .dd-list .dd-list {
            padding-left: 30px;
        }

        .dd-collapsed .dd-list {
            display: none;
        }

        .dd-item,
        .dd-empty,
        .dd-placeholder {
            display: block;
            position: relative;
            margin: 0;
            padding: 0;
            min-height: 20px;
            font-size: 13px;
            line-height: 20px;
        }

        .dd-handle {
            display: block;
            height: 30px;
            margin: 5px 0;
            padding: 5px 10px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd-handle:hover {
            color: #2ea8e5;
            background: #fff;
        }

        .dd-item>button {
            display: block;
            position: relative;
            cursor: pointer;
            float: left;
            width: 25px;
            height: 20px;
            margin: 5px 0;
            padding: 0;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 0;
            background: transparent;
            font-size: 12px;
            line-height: 1;
            text-align: center;
            font-weight: bold;
        }

        .dd-item>button:before {
            content: '+';
            display: block;
            position: absolute;
            width: 100%;
            text-align: center;
            text-indent: 0;
        }

        .dd-item>button[data-action="collapse"]:before {
            content: '-';
        }

        .dd-placeholder,
        .dd-empty {
            margin: 5px 0;
            padding: 0;
            min-height: 30px;
            background: #f2fbff;
            border: 1px dashed #b6bcbf;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd-empty {
            border: 1px dashed #bbb;
            min-height: 100px;
            background-color: #e5e5e5;
            background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
            background-size: 60px 60px;
            background-position: 0 0, 30px 30px;
        }

        .dd-dragel {
            position: absolute;
            pointer-events: none;
            z-index: 9999;
        }

        .dd-dragel>.dd-item .dd-handle {
            margin-top: 0;
        }

        .dd-dragel .dd-handle {
            -webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
            box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
        }

        .nestable-lists {
            display: block;
            clear: both;
            padding: 30px 0;
            width: 100%;
            border: 0;
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
        }

        #nestable-menu {
            padding: 0;
            margin: 0;
        }

        #nestable-output,
        #nestable2-output {
            width: 100%;
            height: 7em;
            font-size: 0.75em;
            line-height: 1.333333em;
            font-family: Consolas, monospace;
            padding: 5px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        #nestable2 .dd-handle {
            color: #fff;
            border: 1px solid #999;
            background: #bbb;
            background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
            background: -moz-linear-gradient(top, #bbb 0%, #999 100%);
            background: linear-gradient(top, #bbb 0%, #999 100%);
        }

        #nestable2 .dd-handle:hover {
            background: #bbb;
        }

        #nestable2 .dd-item>button:before {
            color: #fff;
        }

        @media only screen and (min-width: 700px) {
            .dd {
                float: left;
                width: 80%;
            }

            .dd+.dd {
                margin-left: 2%;
            }
        }

        .dd-hover>.dd-handle {
            background: #2ea8e5 !important;
        }

        .dd3-content {
            display: block;
            height: 30px;
            margin: 5px 0;
            padding: 5px 10px 5px 40px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border: 1px solid #ccc;
            background: #fafafa;
            background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
            background: linear-gradient(top, #fafafa 0%, #eee 100%);
            -webkit-border-radius: 3px;
            border-radius: 3px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .dd3-content:hover {
            color: #2ea8e5;
            background: #fff;
        }

        .dd-dragel>.dd3-item>.dd3-content {
            margin: 0;
        }

        .dd3-item>button {
            margin-left: 30px;
        }

        .dd3-handle {
            position: absolute;
            margin: 0;
            left: 0;
            top: 0;
            cursor: pointer;
            width: 30px;
            text-indent: 100%;
            white-space: nowrap;
            overflow: hidden;
            border: 1px solid #aaa;
            background: #ddd;
            background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
            background: -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
            background: linear-gradient(top, #ddd 0%, #bbb 100%);
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .dd3-handle:before {
            content: '≡';
            display: block;
            position: absolute;
            left: 0;
            top: 3px;
            width: 100%;
            text-align: center;
            text-indent: 0;
            color: #fff;
            font-size: 20px;
            font-weight: normal;
        }

        .dd3-handle:hover {
            background: #ddd;
        }

        .edit-wrap {
            position: absolute;
            top: 0;
            left: 101%;
        }

        .delete-wrap {
            position: absolute;
            top: 0;
            left: 107%;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('backend/plugins/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/plugins/nestable/jquery.nestable.js') }}"></script>
    <script>
        $(function() {
            var updateOutput = function(e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.menu-item.sort', $menu->id) }}",
                    data: {
                        list: list.nestable('serialize')
                    }
                }).done(function() {
                    Swal.fire({
                        title: "Success!",
                        text: "Menu list has been sorted.",
                        timer: 2000,
                        icon: 'success',
                        showConfirmButton: false
                    });
                });
            };

            $('.dd').nestable({
                'serialize': true,
                'maxDepth': 4,
                'includeContent': true
            }).on('change', updateOutput);

            $('.panel-title > a').click(function() {
                $(this).find('i').toggleClass('fa-plus fa-minus')
                    .closest('panel').siblings('panel')
                    .find('i')
                    .removeClass('fa-minus').addClass('fa-plus');
            });

            $('#nestable-menu').on('click', function(e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            $('.defaultTable').dataTable({
                "pageLength": 50
            });

            $('#sortable').sortable({
                axis: 'y',
                update: function(event, ui) {
                    var data = $(this).sortable('serialize');
                    var url = "{{ url('admin/menu/sort') }}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        datatype: "json",
                        data: {
                            order: data,
                            _token: '{!! csrf_token() !!}'
                        },
                        success: function(data) {
                            console.log(data);
                            var obj = jQuery.parseJSON(data);
                            Swal.fire({
                                title: "Success!",
                                text: "contents has been sorted.",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $(".defaultTable").on("click", ".delete", function() {
                $object = $(this);
                var id = $object.attr('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "/admin/menu" + "/" + id,
                            data: {
                                id: id,
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire("Deleted!", response.message, "success");
                                var oTable = $('.defaultTable').dataTable();
                                var nRow = $($object).parents('tr')[0];
                                oTable.fnDeleteRow(nRow);
                            },
                            error: function(e) {
                                if (e.responseJSON.message) {
                                    Swal.fire('Error', e.responseJSON.message, 'error');
                                } else {
                                    Swal.fire('Error',
                                        'Something went wrong while processing your request.',
                                        'error')
                                }
                            }
                        });
                    }
                });
            });

            $('.remove-item').on('click', function() {
                $object = $(this);
                var id = $object.closest('li').data('id');
                var menu = "{{ $menu->id }}";
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "/admin/menu/" + menu + "/menu-item/" + id,
                            data: {
                                id: id,
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire("Deleted!", response.message, "success");
                                $object.closest('li').remove();
                            },
                            error: function(e) {
                                console.log(e);
                                if (e.responseJSON.message) {
                                    Swal.fire('Error', e.responseJSON.message, 'error');
                                } else {
                                    Swal.fire('Error',
                                        'Something went wrong while processing your request.',
                                        'error')
                                }
                            }
                        });
                    }
                });
            });
        });

        $('form').submit(function() {
            $(this).find("button[type='submit']").prop('disabled', true);
        });
    </script>
@endsection
@section('page-header')
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-2">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Menu</h5>
                <!--end::Page Title-->
                <!--begin::Actions-->
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-dark-50 font-weight-bold" id="kt_subheader_total">{{ $menu->title }}</span>
                <!--end::Actions-->
            </div>
            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-success font-weight-bolder">Back</a>
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->
@endsection
@section('content')
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Add menu items
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="accordion accordion-solid accordion-toggle-plus" id="accordion" role="tablist"
                                aria-multiselectable="true">
                                @if (!$modules->isEmpty())
                                    @foreach ($modules as $key => $module)
                                        <div class="card">
                                            <div class="card-header" role="tab" id="module-{{ $key }}">
                                                <div class="card-title collapsed" data-toggle="collapse"
                                                    data-target="#collapse-{{ $key }}">
                                                    {{ $module->name }}
                                                </div>
                                            </div>
                                            <div id="collapse-{{ $key }}" class="panel-collapse collapse"
                                                role="tabpanel" aria-labelledby="heading-{{ $key }}">
                                                <div class="card-body">
                                                    @include('admin.menuItem.module', [
                                                        'module' => $module,
                                                        'dataProvider' => $menuRepo->moduleContents(
                                                            $module->alias
                                                        ),
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="card">
                                    <div class="card-header" role="tab" id="custom">
                                        <div class="card-title {{ old('type') == 2 ? '' : 'collapsed' }}"
                                            data-toggle="collapse" data-target="#collapse-custom">
                                            Custom Links
                                        </div>
                                    </div>
                                    <div id="collapse-custom"
                                        class="panel-collapse collapse {{ old('type') == 2 ? 'show' : '' }}"
                                        role="tabpanel" aria-labelledby="heading-custom">
                                        <div class="card-body">
                                            @include('admin.menuItem.create', [
                                                'customLinks' => $customLinks,
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-custom gutter-b">
                        <div class="card-header">
                            <div class="card-title w-100">
                                <h3 class="card-label w-100 mr-0">
                                    Menu structure
                                    <span class="float-right">
                                        <menu id="nestable-menu">
                                            <button type="button" data-action="expand-all"
                                                class="btn btn-outline-success btn-sm"><i class="la la-angle-down"></i>
                                                Expand All</button>
                                            <button type="button" data-action="collapse-all"
                                                class="btn btn-outline-success btn-sm"><i class="la la-angle-up"></i>
                                                Collapse All</button>
                                        </menu>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">

                            @if (!empty($items))
                                <div class="dd">
                                    <ol class="dd-list">
                                        @foreach ($items['parent'] as $lvl => $item)
                                            <li class="dd-item" data-id="{{ $item['id'] }}"
                                                data-npl="{{ $item['multiId'] }}"
                                                data-nplTitle="{{ $item['multiTitle'] }}"
                                                data-new="{{ $item['is_new'] }}">
                                                <div class="dd-handle">
                                                    {{ Helper::shortText($item['title'], 30) }}
                                                    <span
                                                        class="float-right badge badge-flat ml-5 {{ !empty($item['multiContent']) ? 'border-success text-success' : 'border-warning text-warning' }}">{{ !empty($item['multiContent']) ? 'N' : '--' }}</span>
                                                    <span
                                                        class="float-right badge badge-flat border-info text-info text-uppercase">{{ !empty($item['module']) ? $item['module'] : 'Custom' }}</span>
                                                </div>
                                                <span class="edit-wrap">
                                                    <a href="{{ route('admin.menu-item.edit', ['menu_id' => $menu->id, 'id' => $item['id']]) }}"
                                                        class="btn btn-icon btn-circle btn-xs btn-info"
                                                        data-toggle="tooltip" title="Edit"><i
                                                            class="la la-pencil"></i></a></span>
                                                <span class="delete-wrap"><a href="javascript:void(0);"
                                                        class="btn btn-icon btn-circle btn-xs btn-danger remove-item"
                                                        data-toggle="tooltip" title="Delete"><i
                                                            class="la la-times"></i></a></span>
                                                @if (isset($items['child']) && array_key_exists($lvl, $items['child']))
                                                    <ol class="dd-list">
                                                        @foreach ($items['child'][$lvl] as $lvl2 => $item2)
                                                            <li class="dd-item" data-id="{{ $item2['id'] }}"
                                                                data-npl="{{ $item2['multiId'] }}"
                                                                data-nplTitle="{{ $item2['multiTitle'] }}"
                                                                data-new="{{ $item2['is_new'] }}">
                                                                <div class="dd-handle">
                                                                    {{ Helper::shortText($item2['title'], 30) }}
                                                                    <span
                                                                        class="float-right badge badge-flat ml-5 {{ !empty($item2['multiContent']) ? 'border-success text-success' : 'border-warning text-warning' }}">{{ !empty($item2['multiContent']) ? 'N' : '--' }}</span>
                                                                    <span
                                                                        class="float-right badge badge-flat border-info text-info text-uppercase">{{ !empty($item2['module']) ? $item2['module'] : 'Custom' }}</span>
                                                                </div>
                                                                <span class="edit-wrap">
                                                                    <a href="{{ route('admin.menu-item.edit', ['menu_id' => $menu->id, 'id' => $item2['id']]) }}"
                                                                        class="btn btn-icon btn-circle btn-xs btn-info"
                                                                        data-toggle="tooltip" title="Edit"><i
                                                                            class="la la-pencil"></i></a></span>
                                                                <span class="delete-wrap"><a href="javascript:void(0);"
                                                                        class="btn btn-icon btn-circle btn-xs btn-danger remove-item"
                                                                        data-toggle="tooltip" title="Delete"><i
                                                                            class="la la-times"></i></a></span>
                                                                @if (array_key_exists($lvl2, $items['child']))
                                                                    <ol class="dd-list">
                                                                        @foreach ($items['child'][$lvl2] as $lvl3 => $item3)
                                                                            <li class="dd-item"
                                                                                data-id="{{ $item3['id'] }}"
                                                                                data-npl="{{ $item3['multiId'] }}"
                                                                                data-nplTitle="{{ $item3['multiTitle'] }}">
                                                                                <div class="dd-handle"
                                                                                    data-new="{{ $item3['is_new'] }}">
                                                                                    {{ Helper::shortText($item3['title'], 30) }}
                                                                                    <span
                                                                                        class="float-right badge badge-flat ml-5 {{ !empty($item3['multiContent']) ? 'border-success text-success' : 'border-warning text-warning' }}">{{ !empty($item3['multiContent']) ? 'N' : '--' }}</span>
                                                                                    <span
                                                                                        class="float-right badge badge-flat border-info text-info text-uppercase">{{ !empty($item3['module']) ? $item3['module'] : 'Custom' }}</span>
                                                                                </div>
                                                                                <span class="edit-wrap">
                                                                                    <a href="{{ route('admin.menu-item.edit', ['menu_id' => $menu->id, 'id' => $item3['id']]) }}"
                                                                                        class="btn btn-icon btn-circle btn-xs btn-info"
                                                                                        data-toggle="tooltip"
                                                                                        title="Edit"><i
                                                                                            class="la la-pencil"></i></a></span>
                                                                                <span class="delete-wrap"><a
                                                                                        href="javascript:void(0);"
                                                                                        class="btn btn-icon btn-circle btn-xs btn-danger remove-item"
                                                                                        data-toggle="tooltip"
                                                                                        title="Delete"><i
                                                                                            class="la la-times"></i></a></span>
                                                                                @if (array_key_exists($lvl3, $items['child']))
                                                                                    <ol class="dd-list">
                                                                                        @foreach ($items['child'][$lvl3] as $lvl4 => $item4)
                                                                                            <li class="dd-item"
                                                                                                data-id="{{ $item4['id'] }}"
                                                                                                data-npl="{{ $item4['multiId'] }}"
                                                                                                data-nplTitle="{{ $item4['multiTitle'] }}"
                                                                                                data-new="{{ $item4['is_new'] }}">
                                                                                                <div class="dd-handle">
                                                                                                    {{ Helper::shortText($item4['title'], 30) }}
                                                                                                    <span
                                                                                                        class="float-right badge badge-flat ml-5 {{ !empty($item4['multiContent']) ? 'border-success text-success' : 'border-warning text-warning' }}">{{ !empty($item4['multiContent']) ? 'N' : '--' }}</span>
                                                                                                    <span
                                                                                                        class="float-right badge badge-flat border-info text-info text-uppercase">{{ !empty($item4['module']) ? $item4['module'] : 'Custom' }}</span>
                                                                                                </div>
                                                                                                <span class="edit-wrap">
                                                                                                    <a href="{{ route('admin.menu-item.edit', ['menu_id' => $menu->id, 'id' => $item4['id']]) }}"
                                                                                                        class="btn btn-icon btn-circle btn-xs btn-info"
                                                                                                        data-toggle="tooltip"
                                                                                                        title="Edit"><i
                                                                                                            class="la la-pencil"></i></a></span>
                                                                                                <span
                                                                                                    class="delete-wrap"><a
                                                                                                        href="javascript:void(0);"
                                                                                                        class="btn btn-icon btn-circle btn-xs btn-danger remove-item"
                                                                                                        data-toggle="tooltip"
                                                                                                        title="Delete"><i
                                                                                                            class="la la-times"></i></a></span>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ol>
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ol>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ol>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
