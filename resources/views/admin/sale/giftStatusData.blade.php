<div class="row">
    @if($gift_data->gift_status == 0)
        <div class="col-sm-6">
            <h5 style="color: red">* गिफ्ट {{$gift_data->gift_product}}</h5>
        </div>
        <div class="col-sm-6">
            <label class="checkbox checkbox-inline m-r-20 m-t-10 " style="color: red">
                {{$gift_data->status}}
                <input type="checkbox" {{--{{$gift_data->gift_status == 1 ? 'checked' : ''}}--}} value="1"
                       onclick="CheckFunction()" id="giftStatus">
                <i class="input-helper"></i>
                अप्रूव करे (भेजें)
            </label>
        </div>
    @endif
</div>
