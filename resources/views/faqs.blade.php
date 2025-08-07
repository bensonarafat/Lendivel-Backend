@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/faqs.js') }}"></script>
@endsection

@section('content')
    <style>
        #Section2 table.dataTable td {
            white-space: normal !important;
        }

        .w-30 {
            width: 30% !important;
        }
    </style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('FAQs') }}</h4>

            <a data-toggle="modal" data-target="#addCatModal" href=""
                class="ml-auto btn btn-primary text-white">{{ __('Add Category') }}</a>

            <a data-toggle="modal" data-target="#addFaqModal" href=""
                class="ml-2 btn btn-primary text-white">{{ __('Add FAQ') }}</a>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills border-b mb-3  ml-0">

                <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1"
                        aria-controls="home" role="tab" data-toggle="tab">{{ __('Categories') }}<span
                            class="badge badge-transparent "></span></a>
                </li>

                <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab"
                        data-toggle="tab">{{ __('FAQs') }}
                        <span class="badge badge-transparent "></span></a>
                </li>
            </ul>

            <div class="tab-content tabs" id="home">
                {{-- Section 1 --}}
                <div role="tabpanel" class="row tab-pane active" id="Section1">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Section 2 --}}
                <div role="tabpanel" class="row tab-pane" id="Section2">
                    <div class="table-responsive col-12">
                        <table class="table table-striped w-100" id="faqTable">
                            <thead>
                                <tr>
                                    <th class="w-30">{{ __('Question') }}</th>
                                    <th class="w-30">{{ __('Answer') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Edit Category Modal --}}
    <div class="modal fade" id="editCatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Edit Category') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="editCatForm" autocomplete="off">
                        @csrf

                        <input type="hidden" name="id" id="editCatId">

                        <div class="form-group">
                            <label> {{ __('Category') }}</label>
                            <input id="editCatTitle" type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- Add Category Modal --}}
    <div class="modal fade" id="addCatModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add Category') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" id="addCatForm" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Category') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- Edit FAQ Modal --}}
    <div class="modal fade" id="editFaqModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add FAQ') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" id="editFaqForm"
                        autocomplete="off">
                        @csrf

                        <input type="hidden" id="editFaqId" name="id">

                        <div class="form-group">
                            <label> {{ __('Category') }}</label>
                            <div class="form-group">
                                <select name="category_id" id="editFaqCategory" class="form-control form-control-sm"
                                    aria-label="Default select example">

                                </select>
                            </div>

                            <div class="form-group">
                                <label> {{ __('Question') }}</label>
                                <input id="editFaqQuestion" type="text" name="question" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label> {{ __('Answer') }}</label>
                                <textarea id="editFaqAnswer" rows="10" style="height:200px !important;" type="text" name="answer"
                                    class="form-control" required></textarea>
                            </div>


                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- Add FAQ Modal --}}
    <div class="modal fade" id="addFaqModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Add FAQ') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" id="addFaqForm"
                        autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label> {{ __('Category') }}</label>

                            <div class="form-group">
                                <select name="category_id" id="category" class="form-control form-control-sm"
                                    aria-label="Default select example">
                                    @foreach ($cats as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label> {{ __('Question') }}</label>
                                <input type="text" name="question" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label> {{ __('Answer') }}</label>
                                <textarea rows="10" style="height:100px !important;" type="text" name="answer" class="form-control"
                                    required></textarea>
                            </div>


                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
