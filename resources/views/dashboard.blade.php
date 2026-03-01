@extends('layouts.master')

@section('page_title', 'Home')

@section('content')
	<div class="row g-5 g-xl-10">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="fw-bold fs-3 mb-2">Namikulo Portfolio</div>
					<div class="text-gray-600">Kamu sudah login sebagai {{ auth()->user()->name }}.</div>
					<div class="text-gray-600 mt-2">Gunakan menu di sidebar untuk mengelola category, portfolio, dan testimoni.</div>
				</div>
			</div>
		</div>
	</div>
@endsection

