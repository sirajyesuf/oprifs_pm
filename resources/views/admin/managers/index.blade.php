@extends('layouts.admin')
@section('page-title') {{ __('Managers list') }} @endsection
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
                @foreach ($managers as $manager)
                    <tr>

                        <td>{{ $manager->name }}</td>
                        <td>{{ $manager->email }}</td>
                        <td>
                            @if (!$manager->block_status)
                                <p class="bg-info font-italic font-weight-400">not blocked</p>
                                <a href="{{ route('managers.block', $manager->id) }}"
                                    class="btn btn-danger btn-link btn-skins">block</a>
                            @else
                                <p class="bg-info font-italic font-weight-400"> blocked</p>
                                <a href="{{ route('managers.un_block', $manager->id) }}"
                                    class="btn btn-success btn-link btn-skins">un block</a>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        {{ $managers->links() }}
    </section>

@endsection
