@extends('home.layouts.app')
@section('title', $topic->title)
@section('description', $topic->excerpt)
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/social-share.js/1.0.16/css/share.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/css/prism.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="twelve wide column">
        @include('home.common.error')
        <div class="ui segment article-content">
            <div class="extra-padding">
                <h1>
                    <i class="grey file text outline icon"></i>
                    <span style="line-height: 34px;">{{$topic->title}}</span>

                    @can('update', $topic)
                        <div class="ui right floated buttons">
                            <a class="ui basic label" href="{{ route('topics.edit', $topic->id) }}"><i
                                        class="grey edit icon"></i></a>
                            <a class="ui basic label" href="javascript:;" data-method="delete"
                               data-url="{{ route('topics.destroy', $topic->id) }}" style="cursor:pointer;">
                                <i class="grey trash icon"></i>
                            </a>
                        </div>
                    @else
                        <div class="pull-right">
                            <div class="ui labeled button tiny">
                                <div class="ui compact button tiny kb-star basic " data-act="unstar">
                                    <i class="thumbs up icon"></i> <span
                                            class="state">{{ Auth::check() && Auth::user()->votedTopicd($topic->id) ? '已点赞' : '点赞' }}</span>
                                </div>
                                <a class="ui basic label star_count">{{$topic->voted_count}}</a>
                            </div>
                        </div>
                    @endcan
                </h1>
                <p class="article-meta">
                    <a class="ui basic image small label" href="{{route('users.show',$topic->user_id)}}"><img
                                src="{{$topic->user->avatar}}"> {{$topic->user->name}} </a>
                    <span class="ui label small basic"><i
                                class="clock icon"></i> {{$topic->created_at->diffForHumans()}}</span>
                </p>
                <p class="item-tags">
                    <i class="icon grey tags " style="font-size: 1.2em"></i>
                    <a class="ui tag label small " href="{{route('categories.show',$topic->category_id)}}">
                        {{$topic->category->name}}
                        <img class="tagged"
                             src="https://omu8v0x3b.qnssl.com/uploads/images/201703/15/1/hIYECohuQg.png?imageView2/1/w/200/h/200">
                    </a>
                </p>
                <div class="ui divider"></div>
                <div class="ui readme markdown-body">
                    {!! $topic->body !!}
                </div>
            </div>
        </div>
        <div class="ui message basic">
            <div class="social-share" data-initialized="true">
                <a href="#" class="social-share-icon icon-weibo"></a>
                <a href="#" class="social-share-icon icon-qq"></a>
                <a href="#" class="social-share-icon icon-qzone"></a>
            </div>
            <div class="clearfix"></div>
        </div>
        <voted-topic-button topic="{{$topic->id}}"></voted-topic-button>
        {{-- 用户回复列表 --}}
        <div class="ui threaded comments comment-list ">
            <div id="comments"></div>
            <div class="ui divider horizontal grey"><i class="icon comments"></i> 评论数量: {{$topic->reply_count}}</div>
            @include('home.topics._reply_list', ['replies' => $topic->replies()->with('user')->get()])
            <br>
            @include('home.topics._reply_box', ['topic' => $topic])
        </div>
    </div>

    {{-- 右边栏 --}}
    @include('home.topics._show_sidebar',['user'=> $topic->user,'topic' => $topic])
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/social-share.js/1.0.16/js/social-share.min.js"></script>
<script src=" {{ asset('vendor/js/prism.js') }}"></script>
<script>
    $(document).ready(function () {
        var $config = {
            title: '{{{ $topic->title }}} | from LC #laravel-china# {{ $topic->user->id != 1 ? '@kenuo' : '' }} {{ $topic->user->githu_name ? '@'.$topic->user->githu_name : '' }}',
            wechatQrcodeTitle: "微信扫一扫：分享", // 微信二维码提示文字
            wechatQrcodeHelper: '<p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p>',
            image: "https://dn-phphub.qbox.me/uploads/images/201701/29/1/pQimFCe1r5.png",
            sites: ['weibo', 'wechat', 'qzone', 'qq'],
        };
        socialShare('.social-share', $config);
    });
</script>
@stop
@stop