@extends('layouts.app')

@section('title', 'Grand Santhi Coffee Shop - User Management')

@section('css')
@endsection

@section('content')

<x-card-breadcrumb title="User" :isCreate="false" createUrl="/" />
<form id="user-form">
	<div class="bg-white border rounded-xl shadow-xl p-6">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

			<x-form-input name="name"  id="name" label="Nama User"  placeholder="Masukkan nama " icon="ti ti-user" required  :value="old('name', $user->name ?? '')"/>
				<x-form-input  type="email" name="email"  id="email" label="Email"  placeholder="user@example.com" icon="ti ti-mail" required :value="old('email', $user->email ?? '')"/>
					<x-form-input  type="tel" name="phone"  id="phone" label="Nomor Telepon"  placeholder="08123456789" icon="ti ti-phone" required  :value="old('phone', $user->phone ?? '')"/>
						<x-form-input  name="full_name"  id="full_name" label="Nama Lengkap"  placeholder="Nama lengkap" icon="ti ti-id" required  :value="old('full_name', $user->full_name ?? '')"/>

							<x-form-select name="role" id="role" label="Role User" icon="ti ti-shield" required>
								@php
								$selectedRole = old('role', $user->role ?? '');
								@endphp
								<option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>Admin</option>
								<option value="cashier" {{ $selectedRole === 'cashier' ? 'selected' : '' }}>Cashier</option>
								<option value="customer" {{ $selectedRole === 'customer' ? 'selected' : '' }}>Customer</option>
							</x-form-select>
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
					<x-button-spinner id="btn-save" text="Simpan User" loading-text="Menyimpan..." />
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

		window.userManager = new CrudManager({
			entity: 'user',
			modalId: 'create-modal',
			formId: 'user-form',
			apiUrl: '/admin/user',
			dataTableUrl: '{{ route('admin.user.tableUser') }}',
			formFields: ['nama', 'email', 'phone', 'full_name', 'role'],
			columns:  []
		});
		window.userManager.isEditMode = {{ $user ? 'true' : 'false' }};
		window.userManager.currentId = {{ $user->id ?? 'null' }};
	});
</script>
@endsection