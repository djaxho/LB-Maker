@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>{{$model->name}}</h3>
                        <a href="/{{$model->route}}"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> back</a>
                    </div>

                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-12">
                                @if (count($errors))
                                    <ul class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="col-sm-12">
                                <form action="/{{$model->route}}/{{$model->id}}" method="POST">

                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}

                                    {{-- Selectable fields--}}
                                    @foreach($modelSelectableFields as $field => $data)

                                        <div class="form-group">
                                            <label for="{{$field}}">{{$data['fieldLabel']}}</label>

                                            <select class="form-control" name="{{$field}}" id="{{$field}}">

                                                @foreach($data['fieldOptions'] as $option)
                                                    <option value="{{$option->id}}" {{($option->id === $model->$field) ? 'selected' : ''}}>{{$option->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                    @endforeach

                                     {{--Standard fields--}}
                                    @foreach($modelStandardFields as $field)
                                        <div class="form-group">
                                            <label for="{{$field}}">{{ucwords(str_replace('_', ' ', $field))}}</label>
                                            <input name="{{$field}}" type="text" class="form-control" id="{{$field}}" placeholder="{{ucwords(str_replace('_', ' ', $field))}}" value="{{old($field) ?: $model->$field}}" required>
                                        </div>
                                    @endforeach

                                    <button type="submit" style="margin-right:10px;" class="btn btn-primary pull-left"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>

                                </form>

                                @can('delete', $model)

                                    <form action="/{{$model['route']}}/{{$model['id']}}" method="POST">

                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                                    </form>

                                @endcan

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
