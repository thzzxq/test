@extends('admin.layouts.admin')

@section('admin-css')
    <link href="{{ asset('asset_admin/assets/plugins/parsley/src/parsley.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('admin-content')
    <div id="content" class="content">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb pull-right">
            <li><a href="javascript:;">Home</a></li>
            <li><a href="javascript:;">Form Stuff</a></li>
            <li class="active">Form Validation</li>
        </ol>
        <!-- end breadcrumb -->
        <!-- begin page-header -->
        <h1 class="page-header">Form Validation <small>header small text goes here...</small></h1>
        <!-- end page-header -->

        <!-- begin row -->
        <div class="row">
            <!-- begin col-6 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                        <h4 class="panel-title">Basic Form Validation</h4>
                    </div>
                    @if(count($errors)>0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body panel-form">
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="{{route('category.update',$cate['id'])}}" method="post">
                            {{ csrf_field() }}

                    <div class="form-group ">
                        <label class="control-label col-md-4 col-sm-4" for="name">分类名 * :</label>
                         <div class="col-md-6 col-sm-6">
                        <input value="{{$cate['title']}}" name="title" type="text" class="form-control" id="regular2">
                        </div>
                    </div>


                    <div class="form-group ">
                        <label class="control-label col-md-4 col-sm-4" for="name">排序 * :</label>
                         <div class="col-md-6 col-sm-6">
                        <input value="{{$cate['order']}}" name="order" type="text" class="form-control" id="regular2">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="name">更改分级 * :</label>
                         <div class="col-md-6 col-sm-6">
                        <select id="select2" name="parent_id" class="form-control">
                            <?php $p = DB::table('categories')->where('id','=',$cate['parent_id'])->first();?>
                            @if($cate['parent_id']!=0)
                                <option value="{{$p->id}}">{{$p->title}}</option>
                            @else
                            @endif
                            <option value="0">顶级分类</option>
                            @foreach($cates as $list)
                                <option value="{{$list['id']}}">{{$list['title']}}</option>
                            @endforeach
                        </select>
                        </div>

                    </div>


                    <div class="form-group">
                     <label class="control-label col-md-4 col-sm-4"></label>
                      <div class="col-md-6 col-sm-6">
                        <button class="btn btn-raised ink-reaction btn-primary" type="submit">保存</button>
                        <a href="{{route('category.index')}}"><button class="btn-raised ink-reaction btn-danger btn" type="button">返回</button></a>
                        </div>
                    </div>

                        </form>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-6 -->
        </div>
        <!-- end row -->
    </div>
@endsection

@section('admin-js')
    <script src="{{ asset('asset_admin/assets/plugins/parsley/dist/parsley.js') }}"></script>
    <script src="{{ asset('asset_admin/assets/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script>
        $('.selectpicker').selectpicker('render');
    </script>
@endsection