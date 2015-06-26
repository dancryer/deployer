@extends('layout')


@section('right-buttons')
    <div class="pull-right">
        <a href="{{ url('admin/integrations/auth', ['service' => 'bitbucket']) }}" class="btn btn-default" ><span class="fa fa-bitbucket"></span> Add Bitbucket account</a>
        <a href="{{ url('admin/integrations/auth', ['service' => 'gitlab']) }}" class="btn btn-default" ><span class="fa fa-git"></span> Add Gitlab account</a>
        <a href="{{ url('admin/integrations/auth', ['service' => 'github']) }}" class="btn btn-default" ><span class="fa fa-github"></span> Add Github account</a>
    </div>
@stop