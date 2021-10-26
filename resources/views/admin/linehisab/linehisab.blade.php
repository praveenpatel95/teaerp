@extends('admin.layout.app')
@section('title', 'लाइन हिसाब')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="block-header m-t-20 ">
                            <h2>लाइन हिसाब</h2>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $color = [];
            $color[] = '#229ae0';
            $color[] = '#fda421e8';
            $color[] = '#bbf65d';
            $color[] = '#ec2e51';
            $color[] = '#777';
            $color[] = '#36e8d8';
            $color[] = '#e52165';
            $color[] = '#0d1137';
            $color[] = '#d72631';
            $color[] = '#a2d5c6';
            $color[] = '#077b8a';
            $color[] = '#5c3c92';
            $color[] = '#e2d810';
            $color[] = '#d9138a';
            $color[] = '#12a4d9';
            $color[] = '#322e2f';
            $color[] = '#000000';
            $color[] = '#1e3d59';
            $color[] = '#f5f0e1';
            $color[] = '#26495c';
            $color[] = '#ff6e40';
            $color[] = '#ecc19c';
            $color[] = '#1e847f';
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="mini-charts">
                        <div class="row">
                            @foreach($line_hisab as $row)
                                <div class="col-sm-6 col-md-3">
                                    <div class="mini-charts-item"
                                         style="background-color: @if($loop->iteration<=25){{$color[$loop->iteration]}}@else {{sprintf('#%06X', mt_rand(0, 0xFFFFFF))}} @endif">
                                        <div class="clearfix">
                                            <div class="count">
                                                <small>{{$row->line_name}}</small>
                                                <h2> ₹ {{\App\helper\CommonClass::getTotalAmount($row->id)}}</h2>
                                                <br>
                                                <small>कुल रूट:{{$row->TOTALROUTE}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table id="datatables" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>रूट</th>
                                    <th>कुल ग्राहक</th>
                                    <th>कुल बकाया राशि</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('footerscript')
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script>
        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('linehisab.getData') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'route_name', name: 'routes.route_name'},
                    {data: 'TOTALCUSTOMER', name: 'TOTALCUSTOMER',searchable: false},
                    {data: 'due_amount', name: 'due_amount'},
                ]

            });
        });
    </script>
@endpush
