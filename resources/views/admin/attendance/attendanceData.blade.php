<?php
$i = 0
?>
@foreach($data as $row)
    <tr>
        <input type="hidden" name="salesman_id[]" value="{{$row->sId}}">

        <td>{{$loop->iteration}}
            <input type="hidden" name="general" value=" 2">
        </td>
        <td style="color: deepskyblue">{{$row->name}}</td>
        <td><input type="text" name="work_hours[]" id="wrkHrs{{$i}}"
                   class="form-control inputclass floatnumber "
                   value="{{$row->work_hours}}"
                   placeholder="काम के घंटे" required>
        </td>
        <td >
            <label class="radio radio-inline">
                {!!Form::radio('type['.$i.']', '1' ,($row->type ==1?true:null),['id'=> 'absent'.$i.'']) !!}
                <i class="input-helper"></i>
                अनुपस्थित
            </label>
            <label class="radio radio-inline ">
                {!!Form::radio('type['.$i.']', '2',($row->type ==2?true:null), ['id'=> 'holiday'.$i.'']) !!}
                <i class="input-helper"></i>
                छुट्टी
            </label>
        </td>
        <td><input type="text" name="commission[]" id="commission{{$i}}"
                   class="form-control inputclass floatnumber "
                   value="{{$row->commission}}"
                   placeholder="कमीशन" >
        </td>
    </tr>
    <?php
    $i++;
    ?>
@endforeach
<script>
    $('.floatnumber').on('input', function () {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    });
    /*Holiday Absent*/
    $("input[id^=absent]").on('input', function (){
        var lastnum = $(this).attr('id').slice(-1);

        $("#wrkHrs" + lastnum).val('0'). attr("readonly", true);
    })

    $("input[id^=holiday]").on('input', function (){
        var lastnum = $(this).attr('id').slice(-1);
        $("#wrkHrs" + lastnum).val('8'). attr("readonly", true);
    })

    $("input[id^=wrkHrs]").on('input', function () {
        var value = $(this).val();
        var max = 24;

        if ((value !== '') && (value.indexOf('.') === -1)) {

            $(this).val(Math.max(Math.min(value, max)));
        }
    });
</script>




