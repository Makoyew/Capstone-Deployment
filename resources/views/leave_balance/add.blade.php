<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Leave Days') }}
        </h2>
    </x-slot>
    <div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800">Add Leave Days</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Profile Image</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Employee Name</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Department</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">Gender</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-100 employee-row" data-target="#addLeaveModal" data-employee-id="{{ $user->id }}" data-employee-first-name="{{ $user->first_name }}" data-employee-surname="{{ $user->surname }}" data-employee-department="{{ $user->department ? $user->department->name : 'N/A' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-center">
                                        @if ($user->profile_picture)
                                            <a href="{{ route('users.show', $user) }}">
                                                <img src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }} Profile Picture" class="object-cover w-12 h-12 rounded-full">
                                            </a>
                                        @else
                                            <a href="{{ route('users.show', $user) }}">
                                                <img src="{{ asset('images/default-profile.jpeg') }}" alt="{{ $user->name }} Profile Picture" class="object-cover w-12 h-12 rounded-full">
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">{{ $user->first_name }} {{ $user->surname }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">{{ $user->department ? $user->department->name : 'N/A' }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">{{ $user->gender }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addLeaveModal" tabindex="-1" role="dialog" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('leave-balance.add-leave-days') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLeaveModalLabel">Add Leave Days</h5>
                    </div>
                    <div class="modal-body">
                        <p>Selected Employee: <span id="selectedEmployeeName"></span></p>
                        <p class="mt-2"><span id="selectedEmployeeDepartment"></span></p>
                        <div class="form-group mt-2">
                            <label for="leave_type_modal">Leave Type:</label>
                            <select name="leave_type" id="leave_type_modal" class="form-control" required>
                                <option value="" selected disabled>Select Leave Type</option>
                                <option value="vacation">Vacation Leave</option>
                                <option value="sick">Sick Leave</option>
                                <option value="personal">Personal Leave</option>
                                <option value="fiesta">Fiesta Leave</option>
                                <option value="birthday">Birthday Leave</option>
                                <option value="maternity">Maternity Leave</option>
                                <option value="paternity">Paternity Leave</option>
                                <option value="educational">Educational Leave</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="days_to_add_modal">Days to Add:</label>
                            <input type="number" name="days_to_add" id="days_to_add_modal" class="form-control" required>
                        </div>
                        <input type="hidden" name="user_id" id="employee_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-modal_add">Add Leave Days</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.employee-row').click(function () {
                var employeeId = $(this).data('employee-id');
                var employeeFirstName = $(this).data('employee-first-name');
                var employeeSurname = $(this).data('employee-surname');
                var employeeDepartment = $(this).data('employee-department');
                var employeeGender = $(this).data('employee-gender');
                $('#employee_id').val(employeeId);
                $('#selectedEmployeeName').text(employeeFirstName + ' ' + employeeSurname);
                $('#selectedEmployeeDepartment').text('Department: ' + employeeDepartment);
                $('#selectedEmployeeGender').text('Gender: ' + employeeGender);
                $('#addLeaveModal').modal('show');
                $('#addLeaveModal').modal('show');
            });
        });
    </script>

    <style scoped>
    .btn-modal_add {
        background-color: #546ee2;
        color: white;
    }

    .btn-modal_add:hover {
        background-color: #4e64c5;
    }
    </style>
</x-app-layout>
