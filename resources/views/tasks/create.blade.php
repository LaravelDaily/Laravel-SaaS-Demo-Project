@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add New Task</div>

                    <div class="card-body">
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf

                            Task name:
                            <br />
                            <input type="text" name="name" class="form-control" required />
                            <br />
                            <input type="submit" value="Save" class="btn btn-primary" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection