<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex mb-2">
                        <div id="add" class="d-flex justify-content-start pt-2 align-items-center">
                            <div class="a-button" data-url="{{ $list->toArray()['first_page_url'] }}">
                                &nbsp; + &nbsp;
                            </div>
                        </div>
                        <div id="sort-div" class="d-flex justify-content-end pt-2 align-items-center ml-1">
                            <select name="sort" id="sort">
                                @foreach($sort as $val)
                                <option @if($val['value']===Request::get('sort') && $val['sort']===Request::get('dir')) selected @else @endif value="{{ $val['value'] }}" data-sort="{{ $val['sort'] }}">{{ $val['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="filter-div" class="d-flex justify-content-end pt-2 align-items-center ml-1">
                            <select name="filter" id="filter">
                                @foreach($status as $value)
                                <option @if($value->id == Request::get('filter')) selected @else '' @endif value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="search-div" class="d-flex flex-grow-1 justify-content-end pt-2 align-items-center">
                            <input type="text" id="search" class="paginate-field" value="{{ Request::get('keyword') ?? '' }}" placeholder="Search" />
                        </div>
                    </div>
                    <table class="w-100 p-1">
                        <thead>
                            <tr class="text-center">
                                <!-- <th></th> -->
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Published</th>
                                <th>Date Create</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="content">
                            @if(count($list) > 0)
                            @foreach($list as $val)
                            <tr class="text-center">
                                <!-- <td>
                                    <div class="circle @if($val->is_published) publish @else draft @endif"></div>
                                </td> -->
                                <td>{{ $val->id }}</td>
                                <td>{{ $val->title }}</td>
                                <td>{{ $val->status->name }}</td>
                                <td>{{ $val->is_published ? 'Yes' : 'No' }}</td>
                                <td>{{ $val->created_at }}</td>
                                <td>
                                    <button type="button" class="btn btn-link" data-id="{{ $val->id }}" >Edit</button>
                                    <button type="button" class="btn btn-link" data-id="{{ $val->id }}" >Delete</button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="text-center p-2"> No Result(s) </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="d-flex">
                        <div id="paginate" class="d-flex flex-grow-1 justify-content-end pt-2 align-items-center">
                            <div class="p-button" data-url="{{ $list->toArray()['first_page_url'] }}">
                                &nbsp; << &nbsp; </div>
                                    <div class="p-button" data-url="{{ $list->previousPageUrl() }}">
                                        &nbsp; < &nbsp; </div>
                                            <input type="text" id="paginate-limit" class="paginate-field" value="{{ Request::get('limit') ?? 15 }}" />
                                            <div class="p-button" data-url="{{ $list->nextPageUrl() }}">
                                                &nbsp; > &nbsp;
                                            </div>
                                            <div class="p-button" data-url="{{ $list->toArray()['first_page_url'] }}">
                                                &nbsp; >> &nbsp;
                                            </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                table,
                th,
                td {
                    border: 1px solid black;
                    border-collapse: collapse;
                }

                .circle {
                    width: 10px;
                    height: 10px;
                    border: 1px solid black;
                    border-radius: 50%;
                    margin: 0 auto;
                }

                .draft {
                    background-color: gray;
                }

                .publish {
                    background-color: green;
                }

                #paginate,
                #add {
                    div {
                        border: 1px solid #6b7280;
                        margin-left: 1px;
                        margin-right: 1px;
                        height: 42px !important;
                        display: flex;
                        align-items: center;
                        cursor: pointer;
                    }

                    input {
                        width: 80px;
                        text-align: center;
                    }
                }

                #sort,
                #filter {
                    margin: 0px 1px;
                }

                td a.action-link {
                    color: blue !important;
                    text-decoration: underline !important;
                }
            </style>
            <script>
                $('#paginate-limit').on('keypress', function(e) {
                    if (e.which === 13) {
                        window.location.href = "task?limit=" + $(this).val();
                    }
                });

                $('.p-button').on('click', function(e) {
                    if ($(this).data('url').length > 0) {
                        window.location.href = $(this).data('url') + "&limit=" + $('#paginate-limit').val();
                    }
                });

                $('#sort').on('change', function(e) {
                    let val = $(this).val();
                    let sort = $(this).find(":selected").data('sort');

                    window.location.href = '/task?sort=' + val + '&dir=' + sort;
                });

                $('#filter').on('change', function(e) {
                    let val = $(this).val();

                    window.location.href = '/task?filter=' + val;
                });


                $('#search').on('keypress', function(e) {
                    if (e.which === 13) {
                        window.location.href = "task?keyword=" + $(this).val();
                    }
                });
            </script>
</x-app-layout>