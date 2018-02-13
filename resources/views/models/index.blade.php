@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>{{$modelName}} <a href="/{{strtolower(str_replace(' ', '', $modelName))}}/create"><small class="pull-right">+ create new</small></a></h3>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        @foreach($modelKeys as $key)

                                            <th>{{$key}}</th>

                                        @endforeach
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($models as $model)

                                        <tr>
                                            @foreach($modelKeys as $key)
                                                <td>{{$model[$key]}}</td>
                                            @endforeach

                                            <td><a class="btn btn-warning" href="{{strtolower(str_replace(' ', '', $modelName))}}/{{$model['id']}}"><i class="fa fa-pencil"></i> Edit</a></td>

                                        </tr>


                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
