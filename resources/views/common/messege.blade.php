<!--Success Message-->
@if(session()->has('success_msg'))
    <script type="text/javascript">
        $.growl({
            icon: 'glyphicon glyphicon-thumbs-up',
            title: ' ',
            message: '{{session()->get('success_msg')}}'
        },{
            type: 'alert alert-success',
            offset: {
                x: 20,
                y: 85
            },
        });
    </script>
@endif
<!--Dange Message-->
@if(session()->has('danger_msg'))
    <script type="text/javascript">
        $.growl({
            icon: 'zmdi zmdi-block',
            title: ' ',
            message: '{{session()->get('danger_msg')}}'
        },{
            type: 'alert alert-danger',
            offset: {
                x: 20,
                y: 85
            },
        });
    </script>
@endif
