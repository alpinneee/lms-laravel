<!-- Instructor Navbar -->
<div class="sticky top-0 z-40 flex h-13 shrink-0 items-center border-b border-gray-200 bg-primary-800  sm:px-8 lg:px-4 bg-[#362d98] text-white py-2 px-4 mt-2 mx-4 rounded-2xl shadow-md">
        <button type="button" 
            id="sidebar-toggle"
            class="-m-2.5 p-2.5 text-white lg:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

     <!-- Logo -->
     <div class="flex items-center ml-2 lg:ml-0">
        <img src="{{ asset('img/LogoT4B.png') }}" alt="Train4Best Logo" class="h-8">
    </div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
       

        <div class="flex items-center gap-x-4 lg:gap-x-6 ml-auto">
           
            <!-- Profile dropdown -->
            <div class="relative">
                <button type="button" 
                        id="user-menu-button"
                        class="-m-1.5 flex items-center p-1.5" 
                        aria-expanded="false" 
                        aria-haspopup="true">
                    <span class="sr-only">Open user menu</span>
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                            {{ substr(auth()->user()->name ?? 'I', 0, 1) }}
                        </div>
                       
                    </div>
                </button>

                <!-- Dropdown menu -->
                <div id="user-menu" 
                     class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 hidden">
                    <a href="{{ route('profile.show') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>