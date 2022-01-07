@extends('layouts.admin')

@section('page-title') {{__('Personal Notes')}} @endsection

@section('action-button')
    @auth('web')
        <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Note') }}" data-url="{{route('notes.create',$currentWorkspace->slug)}}">
            <i class="fa fa-plus"></i> {{ __('Create') }}
        </a>
    @endauth
@endsection

@section('content')

    @if($currentWorkspace)

        <section class="section">

            @if(count($personal_notes) > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row notes-list">
                            @foreach($personal_notes as $note)
                                <div class="col-md-4">
                                    <div class="card mb-0 mt-3 {{$note->color}}">
                                        <div class="card-body p-4">
                                            <div class="card-widgets float-right">
                                                <a href="#" class="edit-icon" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Note') }}" data-url="{{route('notes.edit',[$currentWorkspace->slug,$note->id])}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{$note->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <form id="delete-form-{{$note->id}}" action="{{ route('notes.destroy',[$currentWorkspace->slug,$note->id]) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                            <h5 class="card-title note-title mb-0">{{$note->title}}</h5>
                                            <div class="note-text pt-3">
                                                {{$note->text}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body p-4">
                        <div class="page-error">
                            <div class="page-inner">
                                <div class="page-description">
                                    {{ __('No Personal Notes available') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <div class="row justify-content-between align-items-center mt-5 mb-3">
            <div class="col-xl-5 col-lg-4 col-md-12 d-flex align-items-center justify-content-between justify-content-md-start">
                <div class="d-inline-block">
                    <h5 class="h4 d-inline-block font-weight-400">{{ __('Shared Notes') }}</h5>
                </div>
            </div>
        </div>

        <section class="section">

            @if(count($shared_notes) > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row notes-list">
                            @foreach($shared_notes as $note)
                                <div class="col-md-4">
                                    <div class="card mb-0 mt-3 {{$note->color}}">
                                        <div class="card-body p-4">
                                            @if($note->created_by == Auth::id())
                                                <div class="card-widgets float-right">
                                                    <a href="#" class="edit-icon" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Note') }}" data-url="{{route('notes.edit',[$currentWorkspace->slug,$note->id])}}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="#" class="delete-icon" data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="document.getElementById('delete-form-{{$note->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <form id="delete-form-{{$note->id}}" action="{{ route('notes.destroy',[$currentWorkspace->slug,$note->id]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            @endif
                                            <h5 class="card-title note-title mb-0">{{$note->title}}</h5>
                                            <div class="note-text pt-3">
                                                {{$note->text}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body p-4">
                        <div class="page-error">
                            <div class="page-inner">
                                <div class="page-description">
                                    {{ __('No Shared Notes available') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    @else
        <div class="container mt-5">
            <div class="card">
                <div class="card-body p-4">
                    <div class="page-error">
                        <div class="page-inner">
                            <h1>404</h1>
                            <div class="page-description">
                                {{ __('Page Not Found') }}
                            </div>
                            <div class="page-search">
                                <p class="text-muted mt-3">{{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")}}</p>
                                <div class="mt-3">
                                    <a class="btn-return-home badge-blue" href="{{route('home')}}"><i class="fas fa-reply"></i> {{ __('Return Home')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).on('click', 'input[name="type"]', function () {
            var type = $('input[name="type"]:checked').val();

            if (type == 'shared') {
                $('.assign_to_selection').show();
            } else {
                $('.assign_to_selection').hide();
            }
        });
    </script>
@endpush
