@extends('layouts.default')

@section('title', $page->title)

@pushif(isset($page->style), 'styles')
    @if (str_starts_with($page->style, '<style>'))
        {!! $page->style !!}
    @else
        <style>{!! $page->style !!}</style>
    @endif
@endpushif

@section('content')
    @if ($page->header && $page->is_header_active)
        <div class="mb-6 h-[30rem] overflow-hidden">
            <img src="{{ $page->header }}" alt="{{ $page->title }}" class="w-full object-cover">
        </div>
    @endif
    <!--<div @class(['container mx-auto lg:px-32 md:px-16 md:px-8 px-4', 'mt-32' => empty($page->header) && !request()->mobile])>
        
    </div>-->
    {!! html_entity_decode(strip_tags($page->body)) !!}
@endsection

@pushif(isset($page->script), 'scripts')
    @if (str_starts_with($page->script, '<script>'))
        {!! $page->script !!}
    @else
        <script>{!! $page->script !!}</script>
    @endif
@endpushif
