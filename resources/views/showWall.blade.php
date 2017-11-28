@extends('app')
@section('content')
    <style>
        #sub *{
            vertical-align: top;
        }
        #sub{
            margin-top: 10px;
            float: right;
        }
        #up-image{
            position: absolute;
            top:0px;
            width: 100%;
            height: 100%;
            cursor:pointer;
            overflow: hidden;
            border: 1px #000 solid;
            opacity: 0;
            filter:alpha(opacity=0);
        }
    </style>
<div id="app">
    <div style="height: 80px;width: 100%;margin: 0 auto;text-align: center;">
        <h1 style="color: #fff;padding-top: 20px;">{{ $walls['wall_name'] }}</h1>
    </div>
    <div id="header" >
        <ul class="nav nav-tabs" id="shownav ">
            @foreach($walls['tabs'] as $key=>$wall)
                <li @if($type==$key) class="active" @endif id="{{ $key }}a" style="width: {{ 100/count($walls['tabs']) }}%;">
                    <a class="wa"  href="javascript:void(0)">{{ $wall }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div id="showfrom" style="height: 150px">
        <form id="image-form" action="{{ url('addwall') }}" method="post" onsubmit="return validate_form(this)" enctype="multipart/form-data">
            <div class="form-group">
                <textarea class="form-control" rows="3" name="content" maxlength="140" onkeyup="this.value = this.value.substring(0, 140)" placeholder="说点什么吧."></textarea>
                <div id="sub">
                    <div style="position:relative;display: inline-block;width: 40px; height: 34px;padding-right: 5px;">
                        <i class="ivu-icon ivu-icon-image" style="font-size: 35px;color: #fff;"></i>
                        <input type="file" name="image" accept="image/*" id="up-image" autocomplete="off">
                    </div>
                    <select name="type" id="type" style="width: 55px; height: 34px;">
                        @foreach($walls['tabs'] as $key=>$wall)
                            @if($wall != '全部')
                                <option value ="{{ $key }}">{{ $wall }}</option>
                            @endif
                        @endforeach
                    </select>
                    <input class="form-control" name="name" id="name" maxlength="6" type="text" width="6" style="width: 80px;display: inline-block"  placeholder="昵称">
                    <button type="submit" class="btn btn-default" >Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div id="image-div" style="margin: auto;text-align: center;">
        <img id="image" src="" alt="" style="display: none;width: 50%;padding-bottom: 10px;">
    </div>
    <img id="bcg" src="{{ asset('background.jpg') }}" ondragstart="return false">
    <div id="wall">
    </div>
    <div id="getmore" class="showcon" style="text-align: center">
        <i class="ivu-icon ivu-icon-chevron-down"></i>点击查看更多
    </div>
    <div class="refresh" onclick="location.reload()"><icon class="glyphicon glyphicon-refresh"></icon></div>
 </div>

<script src="{{ asset('js/jquery-form.js') }}"></script>

<script>

    var type='{{ $type }}';
    var last=0;
    $(function () {
	    var options = {
            success: showResponse,
            dataType: 'json'
        };
        $('#up-image').on('change', function () {
            $('#image-form').attr('action','{{ url('gimage') }}');
            $('#image-form').ajaxForm(options).submit();
            $('#image-form').attr('action','{{ url('addwall') }}');
        });
	    function showResponse(response) {
	        if(response.success) {
                $('#image').show();
                $('#image').attr("src", response.image);
            }
        }
        $('#name').blur(function () {
            $.cookie('name',$('#name').val());
        });
        if($.cookie('name')){
            $('#name').val($.cookie('name'));
        }
        $.get("{{ url('ajwall') }}/"+type+"/"+0,function (data) {
            $('#wall').append(data);
            if($('.showreply').length==0){
                last=0;
            }else {
                last = $('.showreply').eq($('.showreply').length - 1).attr('id');
            }
            if($('.end').length!=0){
                $('#getmore').html('- - 没有啦 - -');
                $('#getmore').removeAttr('id');
            }
        });
        $('#getmore').click(function () {
            $.get("{{ url('ajwall') }}/"+type+"/"+last,function (data) {
                $('#wall').append(data);
                if($('.showreply').length==0){
                    last=0;
                }else {
                    last = $('.showreply').eq($('.showreply').length - 1).attr('id');
                }
                if($('.end').length!=0){
                    $('#getmore').html('- - 没有啦 - -');
                }
            });
        });
        $('li').click(function () {
            $('li').removeClass('active');
            $(this).addClass('active');
            var id=parseInt($(this).attr('id'));
            type = id;
            last = 10;
		if(id)
            $('#type').val(id);
		else
		$('#type').val(1);
            $('#wall').html('<i class="ivu-icon ivu-icon-load-a load"></i>');
            $.get("{{ url('ajwall') }}/"+id+"/"+0,function (data) {
                $('#wall').html(data);
                if($('.showreply').length==0){
                    last=0;
                }else {
                    last = $('.showreply').eq($('.showreply').length - 1).attr('id');
                }
                if($('.end').length==0){
                    $('#getmore').html('<i class="ivu-icon ivu-icon-chevron-down"></i>点击查看更多');
                }
                if($('.end').length!=0){
                    $('#getmore').html('- - 没有啦 - -');
                }
            });
        });
        $(document).on('click','.agree',function () {
            var x=$(this).text();
            var id=$(this).parent().attr('id');
            if ($(this).hasClass('onagree')) {
                $(this).removeClass('onagree');
                x--;
                $.get("{{ url('agree') }}/"+id+"/-1",function () {});
            } else {
                $(this).addClass('onagree');
                x++;
                $.get("{{ url('agree') }}/"+id+"/1",function () {});
            }
            $(this).children('.agreenum').text(x);
        });
    });
    function ag() {
        if($.cookie('agreed')!=null){
            var x = $.cookie('agreed');
            if(x > 0){
                var th = $('#'+x).children('.agree');
                if(!$(th).hasClass('onagree')) {
                    $(this).addClass('onagree');
                    x++;
                    $.get("{{ url('agree') }}/" + x + "/1", function () {
                    });
                }
                $.cookie('agreed','0');
            }else if(x < 0){
                x*=-1;
                var th = $('#'+x).children('.agree');
                if($(th).hasClass('onagree')) {
                    $(this).removeClass('onagree');
                    x--;
                    $.get("{{ url('agree') }}/"+ x +"/-1",function () {});
                }
                $.cookie('agreed','0');
            }
        }
    }
    setInterval("ag()",100);
    if (window.DeviceMotionEvent) {
        window.addEventListener("devicemotion", function() {}, false);
    }
    function validate_form(thisform) {
        with (thisform)
        {
            if (content.value==null||content.value=='')
            {content.focus();return false}
            else {
                return true;
            }
        }
    }
</script>
@endsection
