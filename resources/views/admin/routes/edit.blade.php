@extends('admin.layout.app')
@section('title', 'अपडेट रूट')
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('routes.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>अपडेट रूट</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route' => array('routes.update',$route->id),'method'=>'put' ,'onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <label for="route_name">रूट का नाम<span style="color: red"> *</span></label>
                                        <input type="text" name="route_name" class="form-control "
                                               placeholder="Route Name" required=""
                                               value="{{old('route_name',$route->route_name)}}">
                                        <p class="help-block" style="color: red">{{$errors->first('route_name')}}</p>
                                    </div>
                                </div>

                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <button type="submit" id="submitBtn" class="btn btn-primary ">सेव</button>
                                        &nbsp&nbsp&nbsp
                                        <button type="reset" class="btn btn-default ">रीसेट</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection





