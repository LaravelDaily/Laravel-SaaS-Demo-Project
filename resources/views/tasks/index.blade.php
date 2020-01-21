@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">My Tasks</div>

                    <div class="card-body">
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add new task</a>
                        <br /><br />
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Task</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($tasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            @csrf
                                            <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No tasks found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection