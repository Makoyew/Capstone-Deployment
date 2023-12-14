<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Employee Evaluation') }}
        </h2>
    </x-slot>

    @if (auth()->user()->isAdmin())
        <div class="container px-4 py-6 mx-auto">
            <form action="{{ route('evaluations.index') }}" method="GET" class="grid grid-cols-3 gap-4">
                <div class="flex items-center">
                    <label for="department_evaluation" class="mr-2 text-sm font-bold text-gray-700">Filter by Department:</label>
                    <div class="relative">
                        <select name="department_evaluation" id="department_evaluation" class="rounded-md dropdown">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" @if($selectedDepartment && $selectedDepartment == $dept->id) selected @endif>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 ml-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Filter</button>
                </div>
            </form>
        </div>
    @endif

    <div class="container p-6 mx-auto bg-white rounded-lg shadow-lg">
        @if(session('success'))
            <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500">
                <div class="flex">
                    <div class="py-1">
                        <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500">
                <div class="flex">
                    <div class="py-1">
                        <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-center text-gray-500 uppercase bg-gray-50">Profile Image</th>
                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-center text-gray-500 uppercase bg-gray-50">Name</th>
                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-center text-gray-500 uppercase bg-gray-50">Email</th>
                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-center text-gray-500 uppercase bg-gray-50">Department</th>
                    @if (!auth()->user()->isAdmin())
                        <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-center text-gray-500 uppercase bg-gray-50">Evaluation Action</th>
                    @endif
                    <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-center text-gray-500 uppercase bg-gray-50">...</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $hasEvaluations = false;
                @endphp
                @foreach($users as $user)
                    @if ($user->evaluations->isNotEmpty())
                        @php
                            $hasEvaluations = true;
                        @endphp
                    @endif
                    <tr>
                        <td class="px-6 py-4 text-center whitespace-no-wrap">
                            <div class="flex items-center justify-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    @if ($user->profile_picture)
                                        <img class="object-cover w-10 h-10 border-4 border-blue-500 rounded-full" src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }} Profile Picture">
                                    @else
                                        <img class="object-cover w-10 h-10 border-4 border-blue-500 rounded-full" src="{{ asset('images/default-profile.jpeg') }}" alt="{{ $user->name }} Profile Picture">
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-no-wrap">
                            <div class="ml-4">
                                <div class="text-sm font-medium leading-5 text-gray-900">{{ $user->first_name }} {{$user->middle_name}} {{ $user->surname }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-no-wrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center whitespace-no-wrap">
                            @if ($user->department)
                                {{ $user->department->name }}
                            @else
                                Department Not Assigned
                            @endif
                        </td>
                        @if (!auth()->user()->isAdmin())
                            <td class="px-6 py-4 text-center whitespace-no-wrap">
                                @if (!$user->hasEvaluated(auth()->user()))
                                    @if (auth()->user()->isSupervisor())
                                        <a href="{{ route('evaluations.form', ['user_id' => $user->id]) }}" class="px-2 py-2 font-semibold text-white transition duration-300 ease-in-out transform bg-blue-500 rounded-md hover:bg-blue-600 hover:scale-105">Evaluate</a>
                                    @endif
                                @else
                                    @if ($user->evaluations->isNotEmpty())
                                        @foreach ($user->evaluations as $evaluation)
                                            @if ($evaluation->submitted_at)
                                                <span class="block mt-1 text-sm text-black">
                                                    Submitted on {{ \Carbon\Carbon::parse($evaluation->submitted_at)->format('Y-m-d H:i:s') }}
                                                </span>
                                            @else
                                                <span class="block mt-1 text-sm text-gray-300">No submission date available</span>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="block mt-1 text-sm text-gray-300">No evaluation data found</span>
                                    @endif
                                @endif
                            </td>
                        @endif

                        <td class="px-6 py-4 text-center whitespace-no-wrap">
                            @if ($user->evaluations->isNotEmpty())
                                <a href="{{ route('evaluations.view', ['user_id' => $user->id]) }}" class="text-blue-500 hover:underline animated-eye">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if (auth()->user()->isAdmin() && !$hasEvaluations)
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center whitespace-no-wrap">
                            No evaluations submitted from department heads.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
