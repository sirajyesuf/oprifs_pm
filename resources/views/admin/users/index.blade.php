@extends('layouts.admin')
@section('page-title') {{ __('Users list') }} @endsection
@section('content')
    <section class="section">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if (!$user->block_status)
                                <p class="bg-info font-italic font-weight-400">not blocked</p>
                                <a href="{{ route('admin_users.block', $user->id) }}"
                                    class="btn btn-danger btn-link btn-skins">block</a>
                            @else
                                <p class="bg-info font-italic font-weight-400"> blocked</p>
                                <a href="{{ route('admin_users.un_block', $user->id) }}"
                                    class="btn btn-success btn-link btn-skins">un block</a>
                            @endif
                        </td>
                    </tr>
                       
                    @endforeach
                </tr>

            </tbody>
        </table>
        {{ $users->links() }}
    </section>
@endsection
