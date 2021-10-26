@extends('salesman.layout.app')
@section('title', 'Profile')
@push('headerscript')

@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('salesman.home')}}"><i
                                    class="zmdi zmdi-long-arrow-return"></i> Back</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>Profile</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>Update Basic Details</h2>
                            <br>
                        </div>
                        <div class="card-body card-padding">
                            <form method="POST" action="{{route('salesmanProfile.update', $data->id)}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="hidden" name="general" value="1">
                                            <input type="text" id="name" class="form-control"
                                                   name="name" value="{{old('name',$data->name)}}"
                                                   placeholder="Name">
                                            <div class="text-danger">{{$errors->first('name')}}</div>

                                        </div>
                                    </div>
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" id="email" class="form-control"
                                                   name="email" value="{{old('name',$data->email)}}"
                                                   placeholder="Email">
                                            <div class="text-danger">{{$errors->first('email')}}</div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <button type="submit" class="btn btn-primary ">Submit</button>&nbsp&nbsp&nbsp
                                            <button type="reset" class="btn btn-default ">Reset</button>
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
                            <h2>Update Password</h2>
                            <br>
                        </div>
                        <div class="card-body card-padding">
                            <form method="POST" action="{{route('salesmanProfile.update', $data->id)}}">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="oldpassword">Old Password <span class="text-danger">*</span></label>
                                            <input type="hidden" name="general" value="2">
                                            <input type="password" id="oldpassword"
                                                   class="form-control" name="oldpassword" required>
                                            <div class="text-danger">{{$errors->first('oldpassword')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="password">New Password <span class="text-danger">*</span></label>
                                            <input type="password" id="password"
                                                   class="form-control" name="password" required>
                                            <div class="text-danger">{{$errors->first('password')}}</div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" id="password_confirmation"
                                                   class="form-control" name="password_confirmation"
                                                   required>
                                            <div class="text-danger">{{$errors->first('password_confirmation')}}</div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 ">
                                        <div class="form-group fg-line">
                                            <button type="submit" class="btn btn-primary ">Submit</button>&nbsp&nbsp&nbsp
                                            <button type="reset" class="btn btn-default ">Reset</button>
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
