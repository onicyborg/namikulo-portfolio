@extends('layouts.master')

@section('page_title', 'Category')

@push('styles')
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
	@if (session('success'))
		<div id="success_alert" class="alert alert-success d-flex align-items-center p-5 mb-5" data-auto-hide="3000">
			<i class="ki-duotone ki-check-circle fs-2hx text-success me-4">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
			<div class="d-flex flex-column">
				<div class="fw-semibold">Berhasil</div>
				<div class="text-gray-700">{{ session('success') }}</div>
			</div>
		</div>
	@endif

	@if ($errors->any())
		<div id="error_alert" class="alert alert-danger d-flex align-items-center p-5 mb-5" data-auto-hide="3000">
			<i class="ki-duotone ki-information-5 fs-2hx text-danger me-4">
				<span class="path1"></span>
				<span class="path2"></span>
				<span class="path3"></span>
			</i>
			<div class="d-flex flex-column">
				<div class="fw-semibold">Terjadi kesalahan</div>
				<div class="text-gray-700">{{ $errors->first() }}</div>
			</div>
		</div>
	@endif

	<div class="card mb-5 mb-xl-8">
		<div class="card-header border-0 pt-5">
			<h3 class="card-title align-items-start flex-column">
				<span class="card-label fw-bold fs-3 mb-1">Master Data Category</span>
				<span class="text-muted mt-1 fw-semibold fs-7">Kelola daftar category untuk portfolio</span>
			</h3>
			<div class="card-toolbar">
				<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modal_category_create">
					<i class="ki-duotone ki-plus fs-2"></i>
					Tambah Category
				</button>
			</div>
		</div>

		<div class="card-body py-3">
			<div class="table-responsive">
				<table id="categories_table" class="table align-middle gs-0 gy-4">
					<thead>
						<tr class="fw-bold text-muted bg-light">
							<th class="ps-4 min-w-250px rounded-start">Name</th>
							<th class="min-w-200px">Slug</th>
							<th class="min-w-300px">Description</th>
							<th class="min-w-125px">Status</th>
							<th class="min-w-125px text-end rounded-end">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($categories as $category)
							<tr>
								<td class="ps-4">
									<div class="d-flex justify-content-start flex-column">
										<span class="text-gray-900 fw-bold mb-1 fs-6">{{ $category->name }}</span>
									</div>
								</td>
								<td>
									<span class="text-gray-900 fw-bold d-block mb-1 fs-6">{{ $category->slug }}</span>
								</td>
								<td>
									<span class="text-muted fw-semibold d-block fs-7">{{ $category->description }}</span>
								</td>
								<td>
									@if ($category->is_active)
										<span class="badge badge-light-success fs-7 fw-bold">Active</span>
									@else
										<span class="badge badge-light-danger fs-7 fw-bold">Inactive</span>
									@endif
								</td>
								<td class="text-end">
									<button
										type="button"
										class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 js-category-edit"
										data-id="{{ $category->id }}"
										data-name="{{ $category->name }}"
										data-description="{{ $category->description }}"
										data-is_active="{{ $category->is_active ? 1 : 0 }}"
									>
										<i class="ki-duotone ki-pencil fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</button>

									<button
										type="button"
										class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm js-category-delete"
										data-id="{{ $category->id }}"
										data-name="{{ $category->name }}"
									>
										<i class="ki-duotone ki-trash fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
										</i>
									</button>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_category_create" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-650px">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="fw-bold">Tambah Category</h2>
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<i class="ki-duotone ki-cross fs-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				</div>

				<form method="POST" action="{{ route('categories.store') }}">
					@csrf
					<div class="modal-body">
						<div class="mb-10">
							<label class="form-label required">Name</label>
							<input type="text" name="name" class="form-control form-control-solid" value="{{ old('name') }}" required />
						</div>

						<div class="mb-10">
							<label class="form-label">Description</label>
							<textarea name="description" class="form-control form-control-solid" rows="3">{{ old('description') }}</textarea>
						</div>

						<label class="form-check form-check-sm form-check-custom form-check-solid">
							<input class="form-check-input" type="checkbox" name="is_active" value="1" checked />
							<span class="form-check-label">Active</span>
						</label>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">
							<span class="indicator-label">Simpan</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_category_edit" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-650px">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="fw-bold">Edit Category</h2>
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<i class="ki-duotone ki-cross fs-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				</div>

				<form id="form_category_edit" method="POST" action="#">
					@csrf
					@method('PUT')
					<div class="modal-body">
						<div class="mb-10">
							<label class="form-label required">Name</label>
							<input id="edit_name" type="text" name="name" class="form-control form-control-solid" required />
						</div>

						<div class="mb-10">
							<label class="form-label">Description</label>
							<textarea id="edit_description" name="description" class="form-control form-control-solid" rows="3"></textarea>
						</div>

						<label class="form-check form-check-sm form-check-custom form-check-solid">
							<input id="edit_is_active" class="form-check-input" type="checkbox" name="is_active" value="1" />
							<span class="form-check-label">Active</span>
						</label>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">
							<span class="indicator-label">Update</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_category_delete" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-500px">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="fw-bold">Hapus Category</h2>
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<i class="ki-duotone ki-cross fs-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				</div>

				<form id="form_category_delete" method="POST" action="#">
					@csrf
					@method('DELETE')
					<div class="modal-body">
						<div class="text-gray-700">Kamu yakin ingin menghapus category <span class="fw-bold" id="delete_name"></span>?</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-danger">Hapus</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
	<script>
		(function () {
			// Auto-hide alerts
			document.querySelectorAll('[data-auto-hide]').forEach(function (el) {
				var timeout = parseInt(el.getAttribute('data-auto-hide'), 10);
				if (!isNaN(timeout)) {
					setTimeout(function () {
						el.style.transition = 'opacity 0.3s';
						el.style.opacity = '0';
						setTimeout(function () {
							el.remove();
						}, 300);
					}, timeout);
				}
			});

			var table = document.getElementById('categories_table');
			if (!table) return;

			$(table).DataTable({
				info: true,
				order: [],
				pageLength: 10,
				lengthChange: false
			});

			var editModalEl = document.getElementById('modal_category_edit');
			var deleteModalEl = document.getElementById('modal_category_delete');
			var editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;
			var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

			document.querySelectorAll('.js-category-edit').forEach(function (btn) {
				btn.addEventListener('click', function () {
					var id = btn.getAttribute('data-id');
					var name = btn.getAttribute('data-name');
					var description = btn.getAttribute('data-description') || '';
					var isActive = btn.getAttribute('data-is_active') === '1';

					var form = document.getElementById('form_category_edit');
					form.setAttribute('action', "{{ url('/categories') }}/" + id);

					document.getElementById('edit_name').value = name;
					document.getElementById('edit_description').value = description;
					document.getElementById('edit_is_active').checked = isActive;

					if (editModal) editModal.show();
				});
			});

			document.querySelectorAll('.js-category-delete').forEach(function (btn) {
				btn.addEventListener('click', function () {
					var id = btn.getAttribute('data-id');
					var name = btn.getAttribute('data-name');

					var form = document.getElementById('form_category_delete');
					form.setAttribute('action', "{{ url('/categories') }}/" + id);
					document.getElementById('delete_name').textContent = name;

					if (deleteModal) deleteModal.show();
				});
			});
		})();
	</script>
@endpush
