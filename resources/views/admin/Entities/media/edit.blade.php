@extends('admin.Layouts.Layout')

@section('content')
    <section class="content-header">
        <h1>
            <span style="text-transform: capitalize"> Medium</span> Management
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Medium</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12 addPromo">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title" style="text-transform: uppercase">
                            Edit Medium
                        </h2>
                        <a href="{{ url('/admin/media') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                    </div>


                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($medium, [
                            'method' => 'PATCH',
                            'url' => ['/admin/media', $medium->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('admin.Entities.media.form', ['submitButtonText' => 'Update'])

                        {!! Form::close() !!}

                </div>
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
@endsection

