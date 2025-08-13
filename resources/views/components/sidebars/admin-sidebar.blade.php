<!-- Admin Sidebar -->
<div id="sidebar" class="fixed inset-y-0 z-50 flex w-56 flex-col transition-transform duration-300 lg:translate-x-0 -translate-x-full">
    <div class="flex grow flex-col gap-y-2 overflow-y-auto bg-gray-100 px-2 py-3">
        <!-- Logo -->
        <div class="mb-14">
          
        </div>
        <!-- Navigation -->
        <nav class="flex flex-1 flex-col">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center text-gray-700 px-2 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>

                <!-- User Management -->
                <li>
                    <div class="relative">
                        <button id="user-management-btn" type="button" 
                                class="flex items-center justify-between w-full text-gray-700 px-2 py-2 rounded-md hover:bg-white hover:text-primary-800 {{ request()->is('admin/users*') || request()->is('admin/user-types*') || request()->is('admin/instructors*') || request()->is('admin/participants*') ? 'bg-white shadow-sm font-medium text-primary-800' : '' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span class="text-sm">User management</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="user-management-menu" class="pl-2 mt-1 block bg-white rounded-md shadow-sm py-1 mx-1">
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.users.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                user
                            </a>
                            <a href="{{ route('admin.user-types.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.user-types.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                usertype
                            </a>
                            <a href="#" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm hover:bg-white hover:text-primary-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                user rule
                            </a>
                            <a href="{{ route('admin.instructors.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.instructors.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                instructure
                            </a>
                            <a href="{{ route('admin.participants.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.participants.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                participant
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Training Management -->
                <li>
                    <div class="relative">
                        <button id="training-management-btn" type="button" 
                                class="flex items-center justify-between w-full text-gray-700 px-2 py-2 rounded-md hover:bg-white hover:text-primary-800 {{ request()->is('admin/course-schedule*') || request()->is('admin/course-types*') || request()->is('admin/courses*') || request()->is('admin/certificates*') ? 'bg-white shadow-sm font-medium text-primary-800' : '' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="text-sm">Training management</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="training-management-menu" class="pl-2 mt-1 block bg-white rounded-md shadow-sm py-1 mx-1">
                            <a href="{{ route('admin.course-schedule.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.course-schedule.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                course schedule
                            </a>
                            <a href="{{ route('admin.course-types.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.course-types.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                course type
                            </a>
                            <a href="{{ route('admin.courses.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.courses.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                courses
                            </a>
                            <a href="{{ route('admin.certificates.index') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.certificates.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                certificates
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Reports -->
                <li>
                    <div class="relative">
                        <button id="reports-btn" type="button" 
                                class="flex items-center justify-between w-full text-gray-700 px-2 py-2 rounded-md hover:bg-white hover:text-primary-800 {{ request()->is('admin/reports*') ? 'bg-white shadow-sm font-medium text-primary-800' : '' }}">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm">Report</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="reports-menu" class="pl-2 mt-1 block bg-white rounded-md shadow-sm py-1 mx-1">
                            <a href="{{ route('admin.reports.certificate-expired') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.reports.certificate-expired') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                certificate expired
                            </a>
                            <a href="{{ route('admin.reports.payment-report') }}" 
                               class="flex items-center text-gray-700 px-2 py-1.5 rounded-md text-sm {{ request()->routeIs('admin.reports.payment-report') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                </svg>
                                payment report
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>