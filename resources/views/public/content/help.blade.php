@extends('public.content._prose')
@section('title', 'Help')
@section('page_title', 'Help center')
@section('page_eyebrow', 'Support')
@section('body')
    <p>In-app help articles and onboarding tours ship with the <code>help_center</code> module. This public page links operators to documentation as it is published.</p>
    <p><a href="{{ route('public.faq') }}">Read the FAQ</a> or <a href="{{ route('public.contact') }}">contact support</a>.</p>
@endsection
