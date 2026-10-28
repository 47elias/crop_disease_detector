@extends('layouts.app')
@section('title', 'Farmers')
@section('page-title', $title)
@section('page-description', $description)
@section('content') @include('modules._table') @endsection
