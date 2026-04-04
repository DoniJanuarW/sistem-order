@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - table Management')

@section('css')
@endsection

@section('content')

<x-card-breadcrumb title="table" :isCreate="false" createUrl='/' />
<form id="table-form">
	<div class="bg-white border rounded-xl shadow-xl p-6">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<x-form-input name="table_number"  id="table_number" label="No table"  placeholder="Masukan nomor table " icon="ti ti-user" required  :value="old('table_number', $table->table_number ?? '')"/>
				<x-form-input name="status"  id="status" label="status"  placeholder="input status" icon="ti ti-mail" required :value="old('status', $table->status ?? '')"/>

				</div>
				<div class="flex gap-2 my-3">
					<button type="reset" class="flex items-center gap-2 px-5 py-2
						!bg-[#dc3545] text-white rounded-lg
						hover:bg-[#F83B3B] active:bg-[#F83B3B]
						focus:ring-4 focus:ring-[#F83B3B]
						transition-all duration-200 shadow-md hover:shadow-lg
						text-sm font-medium">
						Reset
					</button>
				<x-button-spinner id="btn-save" text="Simpan" loading-text="Menyimpan..." />
			</div>
		</div>
	</form>

	<x-loading-spinner id="loading-spinner" />

	@endsection

	@section('js')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets/js/action/crud-manager.js') }}"></script>
<script>
	$(document).ready(function() {

		window.tableManager = new CrudManager({
			entity: 'table',
			modalId: 'create-modal',
			formId: 'table-form',
			apiUrl: '/admin/table',
			dataTableUrl: '{{ route('admin.table.tableTable') }}',
			formFields: ['table_number', 'status'],
			columns:  []
		});
		window.tableManager.isEditMode = {{ $table ? 'true' : 'false' }};
		window.tableManager.currentId = {{ $table->id ?? 'null' }};
	});
</script>
@endsection