<?php
    function setdate($d){
        $now = strtotime(date('Y-m-d H:m:s'));
        $c = (int)($now-strtotime($d));
        if($c<60){
            echo "刚刚";
        }else if($c<60*60){
            $s=(int)($c/60);
            echo $s."分钟前";
        }else if($c<60*60*24){
            $s=(int)($c/60/60);
            echo $s."小时前";
        }else {
            echo date('y-m-d',strtotime($d));
        }
    }
?>
@extends('app')
@section('content')
    <div id="app">
        <div class="showcon" style="margin-top: 10px;background-color:
@foreach($colors as $k=>$color)
@if($k == $res->type)
{{ $color }}
@endif
@endforeach">
            <div class="showuser">
                {{ $res->name }} |
                @foreach($walls as $k=>$wall)
                    @if($k == $res->type)
                        {{ $wall }}
                    @endif
                @endforeach
            </div>
            <div class="showcontent">
                {{ $res->content }}
            </div>
            <div>
                <div class="showdate">
                    第{{ $res->id }}楼 {{ setdate($res->create_at) }}
                </div>
                <div class="showreply" id="{{ $res->id }}">
                    @if($res->onagree)
                        <div class="agree onagree"><i class="ivu-icon ivu-icon-heart" ></i> <p class="agreenum" style="display: inline-block">{{ $res->agree }}</p></div>
                    @else
                        <div class="agree"><i class="ivu-icon ivu-icon-heart" ></i> <p class="agreenum" style="display: inline-block">{{ $res->agree }}</p></div>
                    @endif
                </div>
            </div>
        </div>
        <div id="replyfrom">
            <form action="{{ url('addreply') }}" method="post" onsubmit="return validate_form(this)">
                <div class="form-group">
                    <textarea class="form-control" rows="1" name="content" maxlength="140" onkeyup="this.value = this.value.substring(0, 140)" placeholder="回复点什么吧."></textarea>
                    <div id="sub" style="margin-top: 8px">
                        <input type="hidden" name="wall_id" value="{{ $wall_id }}">
                        <input class="form-control" maxlength="6" type="text" width="6" style="width: 100px;display: inline-block"  placeholder="昵称">
                        <button type="submit" class="btn btn-default" >回复</button>
                    </div>
                </div>
            </form>

        </div>
        <img id="bcg" src="{{ asset('background.jpg') }}" ondragstart="return false">
        <div style="margin: 0 auto;text-align: center">
        @foreach($replys as $key=>$reply)
            <div class="reply">
                <div class="showuser">
                    {{ $reply->name }}
                </div>
                <div class="showcontent">
                    {{ $reply->content }}
                </div>
                    <div class="replydate" style="padding-bottom:3px">
                        第{{ count($replys)-$key }}楼 {{ setdate($reply->create_at) }}
                    </div>
            </div>
        @endforeach
        @if(count($replys)==0)
                <div class="reply" style="background-color: #222">
                    快来抢沙发～
                </div>
        @endif
        </div>
        <div class="back" onclick="history.back(-1)"><icon class="glyphicon glyphicon-chevron-left"></icon></div>
        <div class="refresh" onclick="location.reload()"><icon class="glyphicon glyphicon-refresh"></icon></div>
    </div>
    <script>
        $(function () {
            $(document).on('click','.agree',function () {
                var x=$(this).text();
                var id=$(this).parent().attr('id');
                if ($(this).hasClass('onagree')) {
                    $(this).removeClass('onagree');
                    x--;
                    $.get("{{ url('agree') }}/"+id+"/-1",function () {
                    });
                    $.cookie('agreed','-'+id,{path:'/'});
                } else {
                    $(this).addClass('onagree');
                    x++;
                    $.get("{{ url('agree') }}/"+id+"/1",function () {
                    });
                    $.cookie('agreed',id,{path:'/'});
                }
                $(this).children('.agreenum').text(x);
            });
        });
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
