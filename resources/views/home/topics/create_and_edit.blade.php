@extends('home.layouts.app')
@section('title', isset($topic->id) ? '编辑话题'  : ' 新建话题')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/editor.css') }}">
    <link href="{{ asset('css/prism.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="fourteen column">
        <div class="ui segment">
            <div class="content extra-padding">
                @include('home.common.error')
                <form @if($topic->id) action="{{ route('topics.update', $topic->id) }}"
                      @else action="{{ route('topics.store') }}" @endif method="POST" class="ui form item-form"
                      accept-charset="UTF-8">
                    @if($topic->id)
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="field">
                        <input class="form-control" type="text" name="title" id="title-field"
                               value="{{ old('title', $topic->title ) }}" required="" placeholder="标题">
                    </div>
                    <div class="field">
                        <select name="category_id" class="ui dropdown">
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ $topic->category_id == $categorie->id ? 'selected' : '' }}>{{ $categorie->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="duke-pulse editor-fullscreen"></span>
                    <div class="field">
                        <textarea rows="15" id="editor" name="body" placeholder="请使用 Markdown 编写"
                                  required="">{{old('body', $topic->body_original )}}</textarea>
                    </div>
                    <div class="ui message">
                        <button type="submit" class="ui button teal publish-btn"><i class="icon send"></i> 发布</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/editor.js') }}"></script>
    <script src=" {{ asset('js/prism.js') }}"></script>
    <script>

        $(document).ready(function () {

            var interval = setInterval(function () {
                if (simplemde.isFullscreenActive()) {
                    $('.duke-pulse.editor-fullscreen').hide();
                    $(window).trigger('resize');
                    clearInterval(interval);
                }
            }, 1000);

            $('#category-select').on('change', function () {
                var current_cid = $(this).val();
                $('.category-hint').hide();
                $('.category-' + current_cid).fadeIn();
            });

            var simplemde = new SimpleMDE({
                spellChecker: false,
                autosave: {
                    enabled: true,
                    delay: 5000,
                    unique_id: "topic_content{{ isset($topic) ? $topic->id . '_' . str_slug($topic->updated_at) : '' }}"
                },
                forceSync: true,
                tabSize: 4,
                toolbar: [
                    "bold", "italic", "heading", "|", "quote", "code", "table",
                    "horizontal-rule", "unordered-list", "ordered-list", "|",
                    "link", "image", "|", "side-by-side", 'fullscreen', "|",
                    {
                        name: "guide",
                        action: function customFunction(editor) {
                            var win = window.open('https://github.com/riku/Markdown-Syntax-CN/blob/master/syntax.md', '_blank');
                            if (win) {
                                //Browser has allowed it to be opened
                                win.focus();
                            } else {
                                //Browser has blocked it
                                alert('Please allow popups for this website');
                            }
                        },
                        className: "fa fa-info-circle",
                        title: "Markdown 语法！",
                    },
                    {
                        name: "publish",
                        action: function customFunction(editor) {
                            $('#topic-submit').click();
                        },
                        className: "fa fa-paper-plane",
                        title: "发布话题",
                    }
                ],
            });

            simplemde.codemirror.on("refresh", function () {
                $(window).trigger('resize');
            });
            simplemde.codemirror.on("paste", function () {
                $(window).trigger('resize');
            });

            inlineAttachment.editors.codemirror4.attach(simplemde.codemirror, {
                uploadUrl: '{{ route('uploads.topics_upload_image') }}',
                extraParams: {
                    '_token': '{{ csrf_token() }}',
                },
                onFileUploadResponse: function (xhr) {
                    var result = JSON.parse(xhr.responseText),
                        filename = result.file_path;
                    //this.settings.jsonFieldName

                    if (result && filename) {
                        var newValue;
                        if (typeof this.settings.urlText === 'function') {
                            newValue = this.settings.urlText.call(this, filename, result);
                        } else {
                            newValue = this.settings.urlText.replace(this.filenameTag, filename);
                        }

                        var text = this.editor.getValue().replace(this.lastValue, newValue);
                        this.editor.setValue(text);
                        this.settings.onFileUploaded.call(this, filename);
                    }
                    return false;
                }
            });
        });
    </script>
@stop
@endsection
