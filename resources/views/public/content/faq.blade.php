@extends('public.content._prose')
@section('title', 'FAQ')
@section('page_title', 'Frequently asked questions')
@section('page_eyebrow', 'FAQ')
@section('body')
    <h2>Is this multi-tenant?</h2>
    <p>Yes. Each <code>Business</code> row scopes users and <code>business_modules</code> toggles. Super Admin operates globally.</p>
    <h2>What maps provider is used?</h2>
    <p>Leaflet with OpenStreetMap tiles on the public site; integrations hub supports additional engines per tenant.</p>
    <h2>Demo password?</h2>
    <p>All seeded demo users use <code>password</code> — see README for email patterns.</p>
@endsection
