@extends('layouts.master')

@section('page_title', 'Testimoni')

@push('styles')
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
	@if (session('success'))
		<div class="alert alert-success d-flex align-items-center p-5 mb-5" data-auto-hide="3000">
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
		<div class="alert alert-danger d-flex align-items-center p-5 mb-5" data-auto-hide="3000">
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
				<span class="card-label fw-bold fs-3 mb-1">Master Data Testimoni</span>
				<span class="text-muted mt-1 fw-semibold fs-7">Kelola testimoni client untuk ditampilkan di website</span>
			</h3>
			<div class="card-toolbar">
				<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modal_testimonial_create">
					<i class="ki-duotone ki-plus fs-2"></i>
					Tambah Testimoni
				</button>
			</div>
		</div>

		<div class="card-body py-3">
			<div class="table-responsive">
				<table id="testimonials_table" class="table align-middle gs-0 gy-4">
					<thead>
						<tr class="fw-bold text-muted bg-light">
							<th class="ps-4 min-w-275px rounded-start">Client</th>
							<th class="min-w-150px">Category</th>
							<th class="min-w-100px">Rating</th>
							<th class="min-w-125px">Status</th>
							<th class="min-w-200px text-end rounded-end">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($testimonials as $testimonial)
							@php
								$previewImages = $testimonial->images
									->map(function ($img) {
										return [
											'id' => $img->id,
											'url' => asset('storage/' . $img->image_path),
										];
									})
									->values();
							@endphp
							<tr>
								<td class="ps-4">
									<div class="d-flex justify-content-start flex-column">
										<span class="text-gray-900 fw-bold mb-1 fs-6">{{ $testimonial->client_name }}</span>
										<span class="text-muted fw-semibold d-block fs-7">{{ $testimonial->client_position }}{{ $testimonial->company_name ? ' - ' . $testimonial->company_name : '' }}</span>
									</div>
								</td>
								<td>
									<span class="text-gray-900 fw-bold d-block mb-1 fs-6">{{ $testimonial->category?->name }}</span>
								</td>
								<td>
									<span class="text-gray-900 fw-bold d-block mb-1 fs-6">{{ $testimonial->rating ?? '-' }}</span>
								</td>
								<td>
									@if ($testimonial->is_published)
										<span class="badge badge-light-success fs-7 fw-bold">Published</span>
									@else
										<span class="badge badge-light-warning fs-7 fw-bold">Draft</span>
									@endif
									@if ($testimonial->is_featured)
										<span class="badge badge-light-primary fs-7 fw-bold ms-2">Featured</span>
									@endif
								</td>
								<td class="text-end">
									<button
										type="button"
										class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 js-testimonial-preview"
										data-client_name="{{ $testimonial->client_name }}"
										data-client_position="{{ $testimonial->client_position }}"
										data-company_name="{{ $testimonial->company_name }}"
										data-category="{{ $testimonial->category?->name }}"
										data-rating="{{ $testimonial->rating }}"
										data-content="{{ $testimonial->content }}"
										data-is_published="{{ $testimonial->is_published ? 1 : 0 }}"
										data-is_featured="{{ $testimonial->is_featured ? 1 : 0 }}"
										data-images='@json($previewImages)'
									>
										<i class="ki-duotone ki-eye fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</button>

									<button
										type="button"
										class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 js-testimonial-edit"
										data-id="{{ $testimonial->id }}"
										data-category_id="{{ $testimonial->category_id }}"
										data-client_name="{{ $testimonial->client_name }}"
										data-client_position="{{ $testimonial->client_position }}"
										data-company_name="{{ $testimonial->company_name }}"
										data-rating="{{ $testimonial->rating }}"
										data-content="{{ $testimonial->content }}"
										data-is_published="{{ $testimonial->is_published ? 1 : 0 }}"
										data-is_featured="{{ $testimonial->is_featured ? 1 : 0 }}"
										data-images='@json($previewImages)'
									>
										<i class="ki-duotone ki-pencil fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</button>

									<button
										type="button"
										class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm js-testimonial-delete"
										data-id="{{ $testimonial->id }}"
										data-client_name="{{ $testimonial->client_name }}"
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

	<div class="modal fade" id="modal_testimonial_preview" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="fw-bold">Preview Testimoni</h2>
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<i class="ki-duotone ki-cross fs-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				</div>
				<div class="modal-body">
					<div id="preview_carousel_wrapper" class="mb-6"></div>
					<div class="fw-bold fs-3" id="preview_client"></div>
					<div class="text-muted fw-semibold" id="preview_meta"></div>
					<div class="separator my-5"></div>
					<div class="row g-4">
						<div class="col-md-4">
							<div class="fw-semibold">Category</div>
							<div class="text-gray-700" id="preview_category"></div>
						</div>
						<div class="col-md-4">
							<div class="fw-semibold">Rating</div>
							<div class="text-gray-700" id="preview_rating"></div>
						</div>
						<div class="col-md-4">
							<div class="fw-semibold">Status</div>
							<div class="text-gray-700" id="preview_status"></div>
						</div>
						<div class="col-12">
							<div class="fw-semibold">Content</div>
							<div class="text-gray-700" id="preview_content"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_testimonial_create" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="fw-bold">Tambah Testimoni</h2>
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<i class="ki-duotone ki-cross fs-1">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
					</div>
				</div>

				<form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="mb-10">
							<label class="form-label">Category (optional)</label>
							<select name="category_id" class="form-select form-select-solid">
								<option value="">Tanpa Category</option>
								@foreach ($categories as $cat)
									<option value="{{ $cat->id }}" {{ old('category_id') === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="row g-5">
							<div class="col-md-6">
								<label class="form-label required">Client Name</label>
								<input type="text" name="client_name" class="form-control form-control-solid" value="{{ old('client_name') }}" required />
							</div>
							<div class="col-md-6">
								<label class="form-label">Rating</label>
								<select name="rating" class="form-select form-select-solid">
									<option value="" selected disabled>- Select Rating -</option>
									<option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1</option>
									<option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2</option>
									<option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3</option>
									<option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4</option>
									<option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5</option>
								</select>
							</div>
						</div>

						<div class="row g-5 mt-1">
							<div class="col-md-6">
								<label class="form-label">Client Position</label>
								<input type="text" name="client_position" class="form-control form-control-solid" value="{{ old('client_position') }}" />
							</div>
							<div class="col-md-6">
								<label class="form-label">Company Name</label>
								<input type="text" name="company_name" class="form-control form-control-solid" value="{{ old('company_name') }}" />
							</div>
						</div>

						<div class="mb-10 mt-1">
							<label class="form-label required">Content</label>
							<textarea name="content" class="form-control form-control-solid" rows="4" required>{{ old('content') }}</textarea>
						</div>

						<div class="separator my-8"></div>

						<label class="form-label">Images</label>
						<div class="fv-row">
							<div class="dropzone" id="kt_dropzonejs_testimonial_create">
								<div class="dz-message needsclick">
									<i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
									<div class="ms-4">
										<h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
										<span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
									</div>
								</div>
							</div>
						</div>

						<div class="separator my-8"></div>

						<div class="row g-5">
							<div class="col-md-6">
								<label class="form-check form-check-sm form-check-custom form-check-solid">
									<input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} />
									<span class="form-check-label">Featured</span>
								</label>
							</div>
							<div class="col-md-6">
								<label class="form-check form-check-sm form-check-custom form-check-solid">
									<input class="form-check-input" type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} />
									<span class="form-check-label">Published</span>
								</label>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_testimonial_edit" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="fw-bold">Edit Testimoni</h2>
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
					</div>
				</div>

				<form id="form_testimonial_edit" method="POST" action="#" enctype="multipart/form-data">
					@csrf
					@method('PUT')
					<div class="modal-body">
						<div class="mb-10">
							<label class="form-label">Category (optional)</label>
							<select id="edit_category_id" name="category_id" class="form-select form-select-solid">
								<option value="">Tanpa Category</option>
								@foreach ($categories as $cat)
									<option value="{{ $cat->id }}">{{ $cat->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="row g-5">
							<div class="col-md-6">
								<label class="form-label required">Client Name</label>
								<input id="edit_client_name" type="text" name="client_name" class="form-control form-control-solid" required />
							</div>
							<div class="col-md-6">
								<label class="form-label">Rating</label>
								<select id="edit_rating" name="rating" class="form-select form-select-solid">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</div>
						</div>

						<div class="row g-5 mt-1">
							<div class="col-md-6">
								<label class="form-label">Client Position</label>
								<input id="edit_client_position" type="text" name="client_position" class="form-control form-control-solid" />
							</div>
							<div class="col-md-6">
								<label class="form-label">Company Name</label>
								<input id="edit_company_name" type="text" name="company_name" class="form-control form-control-solid" />
							</div>
						</div>

						<div class="mb-10 mt-1">
							<label class="form-label required">Content</label>
							<textarea id="edit_content" name="content" class="form-control form-control-solid" rows="4" required></textarea>
						</div>

						<div class="separator my-8"></div>

						<label class="form-label">Images</label>
						<div class="fv-row">
							<div class="dropzone" id="kt_dropzonejs_testimonial_edit">
								<div class="dz-message needsclick">
									<i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
									<div class="ms-4">
										<h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
										<span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
									</div>
								</div>
							</div>
						</div>

						<div class="separator my-8"></div>

						<div class="row g-5">
							<div class="col-md-6">
								<label class="form-check form-check-sm form-check-custom form-check-solid">
									<input id="edit_is_featured" class="form-check-input" type="checkbox" name="is_featured" value="1" />
									<span class="form-check-label">Featured</span>
								</label>
							</div>
							<div class="col-md-6">
								<label class="form-check form-check-sm form-check-custom form-check-solid">
									<input id="edit_is_published" class="form-check-input" type="checkbox" name="is_published" value="1" />
									<span class="form-check-label">Published</span>
								</label>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_testimonial_delete" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered mw-500px">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="fw-bold">Hapus Testimoni</h2>
					<div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
						<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
					</div>
				</div>

				<form id="form_testimonial_delete" method="POST" action="#">
					@csrf
					@method('DELETE')
					<div class="modal-body">
						<div class="text-gray-700">Kamu yakin ingin menghapus testimoni dari <span class="fw-bold" id="delete_client"></span>?</div>
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
			if (window.Dropzone) {
				Dropzone.autoDiscover = false;
			}

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

			var table = document.getElementById('testimonials_table');
			if (table) {
				$(table).DataTable({
					info: true,
					order: [],
					pageLength: 10,
					lengthChange: false
				});
			}

			var previewModalEl = document.getElementById('modal_testimonial_preview');
			var editModalEl = document.getElementById('modal_testimonial_edit');
			var deleteModalEl = document.getElementById('modal_testimonial_delete');
			var previewModal = previewModalEl ? new bootstrap.Modal(previewModalEl) : null;
			var editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;
			var deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;

			var createForm = document.querySelector('#modal_testimonial_create form');
			var editForm = document.getElementById('form_testimonial_edit');
			var removedImageIds = [];

			var createDz = null;
			var editDz = null;

			function initCreateDropzone() {
				var el = document.getElementById('kt_dropzonejs_testimonial_create');
				if (!el || !window.Dropzone) return;
				if (createDz) {
					createDz.destroy();
					createDz = null;
				}
				createDz = new Dropzone(el, {
					url: '#',
					paramName: 'file',
					autoProcessQueue: false,
					uploadMultiple: true,
					parallelUploads: 10,
					maxFiles: 10,
					maxFilesize: 10,
					acceptedFiles: 'image/*',
					addRemoveLinks: true,
				});
			}

			function initEditDropzone() {
				var el = document.getElementById('kt_dropzonejs_testimonial_edit');
				if (!el || !window.Dropzone) return;
				if (editDz) {
					editDz.destroy();
					editDz = null;
				}
				removedImageIds = [];
				editDz = new Dropzone(el, {
					url: '#',
					paramName: 'file',
					autoProcessQueue: false,
					uploadMultiple: true,
					parallelUploads: 10,
					maxFiles: 10,
					maxFilesize: 10,
					acceptedFiles: 'image/*',
					addRemoveLinks: true,
				});

				editDz.on('removedfile', function (file) {
					if (file && file.existingId) {
						removedImageIds.push(file.existingId);
					}
				});
			}

			function submitWithDropzone(form, dz, extraAppendCb) {
				if (!form) return;
				var action = form.getAttribute('action');
				var method = (form.getAttribute('method') || 'POST').toUpperCase();

				var fd = new FormData(form);
				if (dz) {
					dz.files.forEach(function (file) {
						if (file.existingId) return;
						fd.append('images[]', file);
					});
				}

				if (typeof extraAppendCb === 'function') {
					extraAppendCb(fd);
				}

				fetch(action, {
					method: method,
					body: fd,
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				}).then(function (res) {
					if (res.redirected) {
						window.location.href = res.url;
						return;
					}
					window.location.reload();
				}).catch(function () {
					form.submit();
				});
			}

			function escapeHtml(value) {
				return String(value ?? '')
					.replaceAll('&', '&amp;')
					.replaceAll('<', '&lt;')
					.replaceAll('>', '&gt;')
					.replaceAll('"', '&quot;')
					.replaceAll("'", '&#039;');
			}

			document.querySelectorAll('.js-testimonial-preview').forEach(function (btn) {
				btn.addEventListener('click', function () {
					var clientName = btn.getAttribute('data-client_name');
					var clientPosition = btn.getAttribute('data-client_position');
					var companyName = btn.getAttribute('data-company_name');
					var category = btn.getAttribute('data-category');
					var rating = btn.getAttribute('data-rating');
					var content = btn.getAttribute('data-content');
					var isPublished = btn.getAttribute('data-is_published') === '1';
					var isFeatured = btn.getAttribute('data-is_featured') === '1';

					var images = [];
					try {
						images = JSON.parse(btn.getAttribute('data-images') || '[]');
					} catch (e) {
						images = [];
					}

					document.getElementById('preview_client').textContent = clientName || '';
					document.getElementById('preview_meta').textContent = (clientPosition || '') + (companyName ? (' - ' + companyName) : '');
					document.getElementById('preview_category').textContent = category || '-';
					document.getElementById('preview_rating').textContent = rating || '-';
					document.getElementById('preview_status').textContent = (isPublished ? 'Published' : 'Draft') + (isFeatured ? ' / Featured' : '');
					document.getElementById('preview_content').textContent = content || '';

					var wrapper = document.getElementById('preview_carousel_wrapper');
					if (!wrapper) return;

					if (!images || images.length === 0) {
						wrapper.innerHTML = '<div class="text-muted">Tidak ada gambar.</div>';
					} else {
						var carouselId = 'testimonial_preview_carousel';
						var indicators = images.map(function (_img, index) {
							return '<button type="button" data-bs-target="#' + carouselId + '" data-bs-slide-to="' + index + '" ' + (index === 0 ? 'class="active" aria-current="true"' : '') + ' aria-label="Slide ' + (index + 1) + '"></button>';
						}).join('');

						var items = images.map(function (img, index) {
							return (
								'<div class="carousel-item ' + (index === 0 ? 'active' : '') + '">' +
									'<img src="' + escapeHtml(img.url) + '" class="d-block w-100 rounded" style="max-height: 360px; object-fit: cover;" alt="testimonial-image">' +
								'</div>'
							);
						}).join('');

						wrapper.innerHTML =
							'<div id="' + carouselId + '" class="carousel slide" data-bs-ride="carousel">' +
								'<div class="carousel-indicators">' + indicators + '</div>' +
								'<div class="carousel-inner">' + items + '</div>' +
								'<button class="carousel-control-prev" type="button" data-bs-target="#' + carouselId + '" data-bs-slide="prev">' +
									'<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
									'<span class="visually-hidden">Previous</span>' +
								'</button>' +
								'<button class="carousel-control-next" type="button" data-bs-target="#' + carouselId + '" data-bs-slide="next">' +
									'<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
									'<span class="visually-hidden">Next</span>' +
								'</button>' +
							'</div>';
					}

					if (previewModal) previewModal.show();
				});
			});

			document.querySelectorAll('.js-testimonial-edit').forEach(function (btn) {
				btn.addEventListener('click', function () {
					var id = btn.getAttribute('data-id');
					var images = [];
					try {
						images = JSON.parse(btn.getAttribute('data-images') || '[]');
					} catch (e) {
						images = [];
					}

					document.getElementById('edit_category_id').value = btn.getAttribute('data-category_id') || '';
					document.getElementById('edit_client_name').value = btn.getAttribute('data-client_name') || '';
					document.getElementById('edit_client_position').value = btn.getAttribute('data-client_position') || '';
					document.getElementById('edit_company_name').value = btn.getAttribute('data-company_name') || '';
					document.getElementById('edit_rating').value = btn.getAttribute('data-rating') || '';
					document.getElementById('edit_content').value = btn.getAttribute('data-content') || '';
					document.getElementById('edit_is_featured').checked = btn.getAttribute('data-is_featured') === '1';
					document.getElementById('edit_is_published').checked = btn.getAttribute('data-is_published') === '1';

					var form = document.getElementById('form_testimonial_edit');
					form.setAttribute('action', "{{ url('/testimonials') }}/" + id);

					initEditDropzone();
					if (editDz && images && images.length) {
						images.forEach(function (img) {
							var mock = {
								name: 'image.jpg',
								size: 12345
							};
							mock.existingId = img.id;
							editDz.emit('addedfile', mock);
							editDz.emit('thumbnail', mock, img.url);
							editDz.emit('complete', mock);
							editDz.files.push(mock);
						});
					}

					if (editModal) editModal.show();
				});
			});

			document.querySelectorAll('.js-testimonial-delete').forEach(function (btn) {
				btn.addEventListener('click', function () {
					var id = btn.getAttribute('data-id');
					var clientName = btn.getAttribute('data-client_name');

					var form = document.getElementById('form_testimonial_delete');
					form.setAttribute('action', "{{ url('/testimonials') }}/" + id);
					document.getElementById('delete_client').textContent = clientName || '';

					if (deleteModal) deleteModal.show();
				});
			});

			initCreateDropzone();
			if (createForm) {
				createForm.addEventListener('submit', function (e) {
					e.preventDefault();
					submitWithDropzone(createForm, createDz);
				});
			}

			if (editForm) {
				editForm.addEventListener('submit', function (e) {
					e.preventDefault();
					submitWithDropzone(editForm, editDz, function (fd) {
						removedImageIds.forEach(function (id) {
							fd.append('removed_images[]', id);
						});
					});
				});
			}
		})();
	</script>
@endpush
