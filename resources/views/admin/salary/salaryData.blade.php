@foreach($salesmanData as $row)
    <input type="hidden" value="{{$row->id}}" name="salesman_id[]">
    <input type="hidden" value="{{$row->salary_amount}}" name="salary_amount[]">
    <input type="hidden" value="{{$work_hours = \App\Models\Attendance::where('salesman_id', $row->id)
                ->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->sum('work_hours')}}" name="work_hours[]">
    <input type="hidden" value="{{$salary = \App\Models\Attendance::where('salesman_id', $row->id)
                ->whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->sum('salary')}}" name="salary[]">
    <input type="hidden" value="{{$commission = \App\Models\Attendance::where('salesman_id', $row->id)
                    ->whereMonth('attendance_date', $month)
                    ->whereYear('attendance_date', $year)
                    ->sum('commission')}}" name="commission[]">
    <input type="hidden" value="{{$sale_commission =  \App\Models\Sale::where('salesman_id', $row->id)
                    ->whereMonth('sale_date', $month)
                    ->whereYear('sale_date', $year)
                    ->sum('sale_commission')}}" name="sale_commission[]">
    <input type="hidden" value="{{$total_amount = $salary + $commission + $sale_commission}}" name="total_amount[]">
    <input type="hidden" value="{{$advance_amount = \App\Models\AdvancePayment::where('salesman_id', $row->id)
                ->whereMonth('adv_date', $month)
                ->whereYear('adv_date', $year)
                ->sum('amount')}}" name="advance_amount[]">
@endforeach


