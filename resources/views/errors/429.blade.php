@extends('errors.layout')

@section('code', '429')
@section('title', 'Çok Fazla İstek')
@section('message', 'Kısa sürede çok fazla istek gönderdiniz. Lütfen biraz bekleyip tekrar deneyin.')
@section('hint', 'Bu koruma, sistemin güvenli ve stabil kalması için uygulanır.')
