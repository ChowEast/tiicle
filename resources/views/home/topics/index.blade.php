@extends('home.layouts.app')
@section('title', isset($category) ? $category->name  : '话题列表')
@section('content')
    <div class="twelve wide column right-side stacked segments">
        <div class="ui segment">
            <div class="content extra-padding">
                @if (isset($category))
                <div>
                    <h1 class="pull-left">
                        <img class="ui middle aligned tiny image tagged" src="https://omu8v0x3b.qnssl.com/uploads/images/201703/26/1/u5BuIO5Ujn.png?imageView2/1/w/200/h/200">
                        <a class="ui tag label orange" href="{{ route('categories.show', $category->id) }}">{{$category->name}}</a>

                    </h1>
                    <div class="login-required ui compact floating subscribe button subscribe-wrap  pull-right teal" data-act="subscribe" data-id="10">
                        <span class="state">关注标签</span>
                    </div>
                </div>
                @else
                    <h1>
                        <a class="ui icon button teal" href="https://tiicle.com/items/create"><i class="icon plus"></i> 记录编码技巧</a>
                    </h1>
                @endif
                <div style="clear: both"></div>
                <div class="ui attached tabular menu stackable">
                    <a class="item active" data-tab="first" href="#"><i class="icon feed"></i> 活跃</a>
                    <a class="item " href="#"><i class="icon wait"></i> 最新</a>
                </div>
                {{-- 话题列表 --}}
                @include('home.topics._topic_list', ['topics' => $topics])

                {{-- 分页 --}}
                {!! $topics->links() !!}

            </div>
        </div>
    </div>
@endsection