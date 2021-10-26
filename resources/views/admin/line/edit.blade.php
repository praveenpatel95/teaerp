@extends('admin.layout.app')
@section('title', 'अपडेट लाइन')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/chosen/chosen.css')}}" rel="stylesheet">
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('line.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>अपडेट लाइन</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route' => array('line.update',$line->id),'method'=>'put','onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <label for="line_name">लाइन का नाम<span style="color: red"> *</span></label>
                                        <input type="text" name="line_name" class="form-control "
                                               placeholder="Line Name" required="" value="{{old('line_name',$line->line_name)}}">
                                        <div style="color: red">{{$errors->first('line_name')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-12 m-b-15 ">
                                    <p class="f-500 c-black "> रूट <span style="color: red"> *</span></p>
                                    {!! Form::select('route_id[]',[]+$line_route, $selected, ['required', 'class'=>'selectpicker' ,'multiple','data-placeholder'=>'Select Route','data-dropup-auto'=>"false",'data-live-search'=> 'true' ]) !!}
{{--                                    <select class="selectpicker" multiple name="route_id[]" data-live-search = "true"  data-dropup-auto ="false" required>--}}
{{--                                        @foreach($route as $key => $value)--}}
{{--                                            @if(in_array($key, $zoneCityIds))--}}
{{--                                                <option value="{{ $key }}" selected="true">{{ $value }}</option>--}}
{{--                                            @else--}}
{{--                                                <option value="{{ $key }}">{{ $value }}</option>--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
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
@push('footerscript')
    <script src="{{asset('theme/vendors/bower_components/chosen/chosen.jquery.js')}}"></script>
@endpush
