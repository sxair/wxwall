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
@foreach($results as $res)
<div class="showcon" style="background-color:
@foreach($colors as $k=>$color)
@if($k == $res->type)
{{ $color }}
@endif
@endforeach
;">
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
    @if($res->image)
        <div style="margin: auto;text-align: center"><img src="{{ asset('image/'.$res->image) }}" alt="image" style="width: 40%"></div>
    @endif
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
                <div class="replybtn"><a href="{{ url('showreply') }}/{{ $res->id }}" style="color: #fff"><i class="ivu-icon ivu-icon-chatbox-working" ></i> {{ $res->reply }}</a></div>
        </div>
    </div>
</div>
@endforeach
@if(count($results) < 10 || $results[count($results)-1]->id ==1 )
    <div class="end"></div>
@endif
