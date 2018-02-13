@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="panel {{--panel-primary--}}" style="border: 1px solid lightgrey;">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-comments fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <h2>{{Auth::user()->blogs()->count()}}</h2>
                                            <div>Blogs</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="blogs">
                                    <div class="panel-footer">
                                        <span class="pull-left">Manage</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <div class="panel {{--panel-primary--}}" style="border: 1px solid lightgrey;">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-comments fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <h2>{{Auth::user()->leadboxes()->count()}}</h2>
                                            <div>Leadboxes</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="/leadboxes">
                                    <div class="panel-footer">
                                        <span class="pull-left">Manage</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
