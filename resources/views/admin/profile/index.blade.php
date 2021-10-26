@extends('admin.layout.app')
@section('title', 'प्रोफ़ाइल')
@push('headerscript')
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('admin.home')}}"><i
                                    class="zmdi zmdi-long-arrow-return"></i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>प्रोफ़ाइल</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>अपडेट बेसिक डिटेल</h2>

                        </div>
                        <div class="card-body card-padding">
                            <form method="POST" action="{{route('adminProfile.update', $data->id)}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="name">नाम <span class="text-danger">*</span></label>
                                            <input type="hidden" name="general" value="1">
                                            <input type="text" id="name" class="form-control"
                                                   name="name" value="{{old('name',$data->name)}}"
                                                   placeholder="Name">
                                            <div class="text-danger">{{$errors->first('name')}}</div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="email">ईमेल <span class="text-danger">*</span></label>
                                            <input type="email" id="email" class="form-control"
                                                   name="email" value="{{old('name',$data->email)}}"
                                                   placeholder="Email">
                                            <div class="text-danger">{{$errors->first('email')}}</div>
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
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>अपडेट पासवर्ड</h2>

                        </div>
                        <div class="card-body card-padding">
                            <form method="POST" action="{{route('adminProfile.update', $data->id)}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="oldpassword">Old पासवर्ड <span
                                                    class="text-danger">*</span></label>
                                            <input type="hidden" name="general" value="2">
                                            <input type="password" id="oldpassword"
                                                   class="form-control" name="oldpassword" required>
                                            <div class="text-danger">{{$errors->first('oldpassword')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="password">New पासवर्ड <span class="text-danger">*</span></label>
                                            <input type="password" id="password"
                                                   class="form-control" name="password" required>
                                            <div class="text-danger">{{$errors->first('password')}}</div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="password_confirmation">पासवर्ड की पुष्टि करें <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" id="password_confirmation"
                                                   class="form-control" name="password_confirmation"
                                                   required>
                                            <div class="text-danger">{{$errors->first('password_confirmation')}}</div>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('footerscript')
@endpush
