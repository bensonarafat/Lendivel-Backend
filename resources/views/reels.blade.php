@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/reels.js') }}"></script>
@endsection

@section('content')
<style>
    .w-30 {
    width: 30% !important;
}
</style>
    <div class="card mt-3">
        <div class="card-header">
            <h4>{{ __('Reels') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive col-12">
                <table class="table table-striped w-100 word-wrap" id="reelsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Thumb') }}</th>
                            <th class="w-30">{{ __('Description') }}</th>
                            <th>{{ __('Doctor') }}</th>
                            <th>{{ __('Stats') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Video Modal --}}
    <div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('View Reel') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="videoDesc"></p>
                    <video rel="" id="video" width="450" height="450" controls>
                        <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

            </div>
        </div>
    </div>
@endsection
