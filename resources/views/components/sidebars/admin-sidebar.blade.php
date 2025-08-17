<!-- Admin Sidebar -->
<div id="sidebar" class="fixed inset-y-0 z-50 flex w-52 flex-col transition-transform duration-300 lg:translate-x-0 -translate-x-full">
    <div class="flex grow flex-col bg-gray-50 px-3 py-4 overflow-hidden">
        <!-- Logo -->
        <div class="mb-6">
          
        </div>
        <!-- Navigation -->
        <nav class="flex flex-1 flex-col mt-7">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center text-gray-600 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-white shadow-sm font-medium text-blue-700' : 'hover:bg-white hover:text-blue-700' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <button id="user-management-btn" type="button" onclick="toggleSubmenu('user-management-menu', this)"
                                class="flex items-center justify-between w-full text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-white hover:text-blue-700 {{ request()->is('admin/users*') || request()->is('admin/user-types*') || request()->is('admin/instructors*') || request()->is('admin/participants*') ? 'bg-white shadow-sm font-medium text-blue-700' : '' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span class="text-xs">User Management</span>
                            </div>
                            <svg class="w-3 h-3 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="user-management-menu" class="pl-4 mt-1 hidden bg-gray-50 rounded-lg py-1">
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.users.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Users
                            </a>
                            <a href="{{ route('admin.user-types.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.user-types.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                User Types
                            </a>
                            <a href="{{ route('admin.instructors.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.instructors.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Instructors
                            </a>
                            <a href="{{ route('admin.participants.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.participants.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Participants
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Training Management -->
                <li>
                    <div class="relative">
                        <button id="training-management-btn" type="button" onclick="toggleSubmenu('training-management-menu', this)"
                                class="flex items-center justify-between w-full text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-white hover:text-blue-700 {{ request()->is('admin/course-schedule*') || request()->is('admin/course-types*') || request()->is('admin/courses*') || request()->is('admin/certificates*') ? 'bg-white shadow-sm font-medium text-blue-700' : '' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="text-xs">Training Management</span>
                            </div>
                            <svg class="w-3 h-3 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="training-management-menu" class="pl-4 mt-1 hidden bg-gray-50 rounded-lg py-1">
                            <a href="{{ route('admin.course-schedule.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.course-schedule.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Course Schedule
                            </a>
                            <a href="{{ route('admin.course-types.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.course-types.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Course Types
                            </a>
                            <a href="{{ route('admin.courses.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.courses.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Courses
                            </a>
                            <a href="{{ route('admin.certificates.index') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.certificates.*') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Certificates
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Reports -->
                <li>
                    <div class="relative">
                        <button id="reports-btn" type="button" onclick="toggleSubmenu('reports-menu', this)"
                                class="flex items-center justify-between w-full text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-white hover:text-blue-700 {{ request()->is('admin/reports*') ? 'bg-white shadow-sm font-medium text-blue-700' : '' }}">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-xs">Reports</span>
                            </div>
                            <svg class="w-3 h-3 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="reports-menu" class="pl-4 mt-1 hidden bg-gray-50 rounded-lg py-1">
                            <a href="{{ route('admin.reports.certificate-expired') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.reports.certificate-expired') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Certificate Expired
                            </a>
                            <a href="{{ route('admin.reports.payment-report') }}" 
                               class="flex items-center text-gray-500 px-2 py-1 rounded-md text-xs {{ request()->routeIs('admin.reports.payment-report') ? 'bg-white shadow-sm font-medium text-blue-600' : 'hover:bg-white hover:text-blue-600' }}">
                                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></div>
                                Payment Report
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
function toggleSubmenu(menuId, button) {
    const menu = document.getElementById(menuId);
    const arrow = button.querySelector('svg:last-child');
    
    if (menu) {
        menu.classList.toggle('hidden');
    }
    if (arrow) {
        arrow.classList.toggle('rotate-180');
    }
}
</script>