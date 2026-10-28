@extends('layouts.app')
@section('title', 'Maize diagnoses')
@section('page-title', $title)
@section('page-description', $description)
@section('content') @include('modules._table') @endsection
