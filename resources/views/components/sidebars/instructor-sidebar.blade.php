<!-- Instructor Sidebar -->
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
                    <a href="{{ route('instructor.dashboard') }}" 
                       class="flex items-center text-gray-700 px-2 py-2 rounded-md {{ request()->routeIs('instructor.dashboard') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </li>

                <!-- My Courses -->
                <li>
                    <a href="{{ route('instructor.courses.index') }}" 
                       class="flex items-center text-gray-700 px-2 py-2 rounded-md {{ request()->routeIs('instructor.courses.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="text-sm">My Courses</span>
                    </a>
                </li>

                <!-- Certificates -->
                <li>
                    <a href="{{ route('instructor.certificates.index') }}" 
                       class="flex items-center text-gray-700 px-2 py-2 rounded-md {{ request()->routeIs('instructor.certificates.*') ? 'bg-white shadow-sm font-medium text-primary-800' : 'hover:bg-white hover:text-primary-800' }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <span class="text-sm">Certificates</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>