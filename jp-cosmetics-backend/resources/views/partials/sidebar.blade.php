<!-- Sidebar -->
<div id="sidebar" class="sidebar p-4 text-slate-100 space-y-1 border-r border-slate-700/60 shadow-xl" style="background-color: rgb(58, 3, 67)">
    <!-- Header -->
    <div class="flex items-center justify-between pb-3 border-b border-slate-700/60">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-indigo-500/20 ring-1 ring-indigo-400/30 grid place-content-center">
          <span class="text-indigo-300 text-sm font-bold">AP</span>
        </div>
        <h1 class="text-xl font-extrabold tracking-tight">Admin Panel</h1>
      </div>
      <button id="closeSidebar"
              class="md:hidden rounded-lg p-2 hover:bg-slate-700/60 ring-1 ring-transparent hover:ring-slate-600 transition"
              aria-label="Close sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
          <path d="M12 13V20L4 12L12 4V11H20V13H12Z"></path>
        </svg>
      </button>
    </div>

    <!-- Dashboard -->
    <a href="{{ route('user.dashboard') }}"
      class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
              hover:bg-slate-700/50 hover:text-white
              ring-1 ring-transparent hover:ring-slate-600 transition
              {{ request()->routeIs('user.dashboard') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
          <path d="M3 12C3 12.5523 3.44772 13 4 13H10C10.5523 13 11 12.5523 11 12V4C11 3.44772 10.5523 3 10 3H4C3.44772 3 3 3.44772 3 4V12ZM3 20C3 20.5523 3.44772 21 4 21H10C10.5523 21 11 20.5523 11 20V16C11 15.4477 10.5523 15 10 15H4C3.44772 15 3 15.4477 3 16V20ZM13 20C13 20.5523 13.4477 21 14 21H20C20.5523 21 21 20.5523 21 20V12C21 11.4477 20.5523 11 20 11H14C13.4477 11 13 11.4477 13 12V20ZM14 3C13.4477 3 13 3.44772 13 4V8C13 8.55228 13.4477 9 14 9H20C20.5523 9 21 8.55228 21 8V4C21 3.44772 20.5523 3 20 3H14Z"></path>
        </svg>
        <span>Dashboard</span>
      </div>
    </a>


    {{-- Analytics --}}
    <details class="group">
      <summary
        class="cursor-pointer w-full list-none flex items-center justify-between rounded-lg px-3 py-2.5 text-left
              text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
              ring-1 ring-transparent hover:ring-slate-600 transition">
        <span class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
            <path d="M5 3a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2H5zm1 2h2v10H6V5zm4 4h2v6h-2V9zm4-2h2v8h-2V7z"/>
          </svg>
          Analytics
        </span>
        <svg xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24" fill="currentColor"
            class="size-5 rounded-full bg-slate-700/60 p-0.5 transition-transform duration-300 rotate-90 group-open:rotate-180">
          <path d="M12 8L18 14H6L12 8Z"></path>
        </svg>
      </summary>

      <div class="mt-1 ml-2 pl-3 border-l border-slate-700/60 font-medium text-slate-300">

        <a href="{{ route('analytics.total-sales') }}"
          class="menu-link flex items-center gap-3 px-2.5 py-2 rounded-md
                  hover:bg-slate-700/40 hover:text-white transition
                  {{ request()->routeIs('analytics.total-sales*') ? 'bg-slate-700/60 text-white' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 14.5v1a1 1 0 11-2 0v-1H9a1 1 0 110-2h2v-1h-1a3 3 0 110-6V6.5a1 1 0 112 0V7h2a1 1 0 110 2h-2v1h1a3 3 0 110 6h-1zm-1-8a1 1 0 100 2 1 1 0 000-2zm2 6a1 1 0 10-2 0 1 1 0 002 0z"/>
          </svg>
          <span>Total Sales</span>
        </a>

        <a href="{{ route('analytics.product-sales') }}"
          class="menu-link flex items-center gap-3 px-2.5 py-2 rounded-md
                  hover:bg-slate-700/40 hover:text-white transition
                  {{ request()->routeIs('analytics.product-sales*') ? 'bg-slate-700/60 text-white' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
            <path d="M20 7h-4V5a3 3 0 00-6 0v2H6a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V8a1 1 0 00-1-1zm-8-2a1 1 0 012 0v2h-2V5zm7 14H7V9h2v1a1 1 0 002 0V9h2v1a1 1 0 002 0V9h2v10z"/>
          </svg>
          <span>Product Sales</span>
        </a>

        <a href="{{ route('analytics.customer-orders') }}"
          class="menu-link flex items-center gap-3 px-2.5 py-2 rounded-md
                  hover:bg-slate-700/40 hover:text-white transition
                  {{ request()->routeIs('analytics.customer-orders*') ? 'bg-slate-700/60 text-white' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
            <path d="M17 8C17 10.7614 14.7614 13 12 13C9.23858 13 7 10.7614 7 8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8ZM12 14C7.58172 14 4 17.5817 4 22H20C20 17.5817 16.4183 14 12 14Z"/>
          </svg>
          <span>Customer Orders</span>
        </a>

      </div>
    </details>

    <!-- Category -->
    <a href="{{ route('category.list') }}"
    class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
           hover:bg-slate-700/50 hover:text-white
           ring-1 ring-transparent hover:ring-slate-600 transition
           {{ request()->routeIs('category.list') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
    <div class="flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90"><path d="M15.5 5C13.567 5 12 6.567 12 8.5C12 10.433 13.567 12 15.5 12C17.433 12 19 10.433 19 8.5C19 6.567 17.433 5 15.5 5ZM10 8.5C10 5.46243 12.4624 3 15.5 3C18.5376 3 21 5.46243 21 8.5C21 9.6575 20.6424 10.7315 20.0317 11.6175L22.7071 14.2929L21.2929 15.7071L18.6175 13.0317C17.7315 13.6424 16.6575 14 15.5 14C12.4624 14 10 11.5376 10 8.5ZM3 4H8V6H3V4ZM3 11H8V13H3V11ZM21 18V20H3V18H21Z"></path></svg>
      <span>Category</span>
    </div>
  </a>

  <!--Brand-->
  <a href="{{ route('brand.list') }}"
    class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
          hover:bg-slate-700/50 hover:text-white
          ring-1 ring-transparent hover:ring-slate-600 transition
          {{ request()->routeIs('brand.list') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
    <div class="flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90"><path d="M10 6V8H6V18H4V8H0V6H10ZM12 6H14.5L17.4999 11.196L20.5 6H23V18H21V9.133L17.4999 15.196L14 9.135V18H12V6Z"></path></svg>
      <span>Brand</span>
    </div>
  </a>

  <!--Skin Type-->
  <a href="{{ route('skin-type.list') }}"
    class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
          hover:bg-slate-700/50 hover:text-white
          ring-1 ring-transparent hover:ring-slate-600 transition
          {{ request()->routeIs('skin-type.*') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
      <div class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
              <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
          </svg>
          <span>Skin Type</span>
      </div>
  </a>

    <!--Customer-->
    <a href="{{ route('customer.list') }}"
    class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
          hover:bg-slate-700/50 hover:text-white
          ring-1 ring-transparent hover:ring-slate-600 transition
          {{ request()->routeIs('customer.list') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
    <div class="flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90"><path d="M12 10C14.2091 10 16 8.20914 16 6 16 3.79086 14.2091 2 12 2 9.79086 2 8 3.79086 8 6 8 8.20914 9.79086 10 12 10ZM5.5 13C6.88071 13 8 11.8807 8 10.5 8 9.11929 6.88071 8 5.5 8 4.11929 8 3 9.11929 3 10.5 3 11.8807 4.11929 13 5.5 13ZM21 10.5C21 11.8807 19.8807 13 18.5 13 17.1193 13 16 11.8807 16 10.5 16 9.11929 17.1193 8 18.5 8 19.8807 8 21 9.11929 21 10.5ZM12 11C14.7614 11 17 13.2386 17 16V22H7V16C7 13.2386 9.23858 11 12 11ZM5 15.9999C5 15.307 5.10067 14.6376 5.28818 14.0056L5.11864 14.0204C3.36503 14.2104 2 15.6958 2 17.4999V21.9999H5V15.9999ZM22 21.9999V17.4999C22 15.6378 20.5459 14.1153 18.7118 14.0056 18.8993 14.6376 19 15.307 19 15.9999V21.9999H22Z"></path></svg>
      <span>Customer</span>
    </div>
    </a>

  <!--Products-->
  <a href="{{ route('product.list') }}"
  class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
        hover:bg-slate-700/50 hover:text-white
        ring-1 ring-transparent hover:ring-slate-600 transition
        {{ request()->routeIs('product.list') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
  <div class="flex items-center gap-3">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90"><path d="M17.2847 10.6683L22.5 13.9909L17.248 17.3368L12 13.9934L6.75198 17.3368L1.5 13.9909L6.7152 10.6684L1.5 7.34587L6.75206 4L11.9999 7.34335L17.2481 4L22.5 7.34587L17.2847 10.6683ZM17.2112 10.6684L11.9999 7.3484L6.78869 10.6683L12 13.9883L17.2112 10.6684ZM6.78574 18.4456L12.0377 15.1L17.2898 18.4456L12.0377 21.7916L6.78574 18.4456Z"></path></svg>
    <span>Product</span>
  </div>
  </a>

    <!--Order-->
    <a href="{{ route('order.list') }}"
    class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
          hover:bg-slate-700/50 hover:text-white
          ring-1 ring-transparent hover:ring-slate-600 transition
          {{ request()->routeIs('order.list') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
    <div class="flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90"><path d="M8 4H21V6H8V4ZM4.5 6.5C3.67157 6.5 3 5.82843 3 5C3 4.17157 3.67157 3.5 4.5 3.5C5.32843 3.5 6 4.17157 6 5C6 5.82843 5.32843 6.5 4.5 6.5ZM4.5 13.5C3.67157 13.5 3 12.8284 3 12C3 11.1716 3.67157 10.5 4.5 10.5C5.32843 10.5 6 11.1716 6 12C6 12.8284 5.32843 13.5 4.5 13.5ZM4.5 20.4C3.67157 20.4 3 19.7284 3 18.9C3 18.0716 3.67157 17.4 4.5 17.4C5.32843 17.4 6 18.0716 6 18.9C6 19.7284 5.32843 20.4 4.5 20.4ZM8 11H21V13H8V11ZM8 18H21V20H8V18Z"></path></svg>
      <span>Order</span>
    </div>
    </a>

    <!--wishlist-->
    <a href="{{ route('wishlist.list') }}"
    class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
          hover:bg-slate-700/50 hover:text-white
          ring-1 ring-transparent hover:ring-slate-600 transition
          {{ request()->routeIs('wishlist.list') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
    <div class="flex items-center gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90"><path d="M12.001 4.52853C14.35 2.42 17.98 2.49 20.2426 4.75736C22.5053 7.02472 22.583 10.637 20.4786 12.993L11.9999 21.485L3.52138 12.993C1.41705 10.637 1.49571 7.01901 3.75736 4.75736C6.02157 2.49315 9.64519 2.41687 12.001 4.52853Z"></path></svg>
      <span>Wishlist</span>
    </div>
    </a>

    <!-- Product Request -->
    <a href="{{ route('product.product-requests.index') }}"
      class="menu-link block rounded-lg px-3 py-2.5 font-medium text-sm
            hover:bg-slate-700/50 hover:text-white
            ring-1 ring-transparent hover:ring-slate-600 transition
            {{ request()->routeIs('product.product-requests.index') ? 'bg-slate-700/60 text-white ring-slate-600' : 'text-slate-200' }}">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
          <path d="M12 2L3 7v10l9 5 9-5V7l-9-5zm0 2.2L18.6 7 12 9.8 5.4 7 12 4.2zM5 9.2l6 3.3v7.2l-6-3.3V9.2zm8 10.5v-7.2l6-3.3v7.2l-6 3.3z"/>
          <path d="M11 10h2v2h2v2h-2v2h-2v-2H9v-2h2v-2z"/>
        </svg>

        {{-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90"><path d="M12.001 4.52853C14.35 2.42 17.98 2.49 20.2426 4.75736C22.5053 7.02472 22.583 10.637 20.4786 12.993L11.9999 21.485L3.52138 12.993C1.41705 10.637 1.49571 7.01901 3.75736 4.75736C6.02157 2.49315 9.64519 2.41687 12.001 4.52853Z"></path></svg> --}}
        <span>Product Request</span>
      </div>
    </a>

    @hasPermission('reviews.list')
    <a href="{{ route('reviews.list') }}"
      class="cursor-pointer w-full list-none flex items-center gap-3 rounded-lg px-3 py-2.5 text-left
              text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
              ring-1 ring-transparent hover:ring-slate-600 transition
              {{ request()->routeIs('reviews.*') ? 'bg-slate-700/60 text-white ring-slate-600' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
      </svg>
      <span>Reviews</span>
    </a>
    @endHasPermission



  <!-- Settings (native dropdown, no JS) -->
    <details class="group" {{ 
          request()->routeIs('user.roles.*') || 
          request()->routeIs('users.*') || 
          request()->routeIs('business-settings.*') ||
          request()->routeIs('blog.*') ||
          request()->routeIs('notice.*') ||
          request()->routeIs('faq.*') ||
          request()->routeIs('settings.*') ||
          request()->routeIs('promotion-popup.*') ||
          request()->routeIs('header-sliders.*') ||
          request()->routeIs('footer-sliders.*')
          ? 'open' : '' 
      }}>
      <summary
        class="cursor-pointer w-full list-none flex items-center justify-between rounded-lg px-3 py-2.5 text-left
              text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
              ring-1 ring-transparent hover:ring-slate-600 transition">
        <span class="flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
            <path d="M12 14V22H4C4 17.5817 7.58172 14 12 14ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM14.5946 18.8115C14.5327 18.5511 14.5 18.2794 14.5 18C14.5 17.7207 14.5327 17.449 14.5945 17.1886L13.6029 16.6161L14.6029 14.884L15.5952 15.4569C15.9883 15.0851 16.4676 14.8034 17 14.6449V13.5H19V14.6449C19.5324 14.8034 20.0116 15.0851 20.4047 15.4569L21.3971 14.8839L22.3972 16.616L21.4055 17.1885C21.4673 17.449 21.5 17.7207 21.5 18C21.5 18.2793 21.4673 18.551 21.4055 18.8114L22.3972 19.3839L21.3972 21.116L20.4048 20.543C20.0117 20.9149 19.5325 21.1966 19.0001 21.355V22.5H17.0001V21.3551C16.4677 21.1967 15.9884 20.915 15.5953 20.5431L14.603 21.1161L13.6029 19.384L14.5946 18.8115ZM18 17C17.4477 17 17 17.4477 17 18C17 18.5523 17.4477 19 18 19C18.5523 19 19 18.5523 19 18C19 17.4477 18.5523 17 18 17Z"></path>
          </svg>
          Settings
        </span>
        <svg xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24" fill="currentColor"
            class="size-5 rounded-full bg-slate-700/60 p-0.5 transition-transform duration-300 rotate-90 group-open:rotate-180">
          <path d="M12 8L18 14H6L12 8Z"></path>
        </svg>
      </summary>

    <div class="mt-1 ml-2 pl-3 border-l border-slate-700/60 font-medium text-slate-300">
      @hasPermission('user.roles.list')
      <a href="{{ route('user.roles.list') }}"
         class="flex items-center gap-3 px-2.5 py-2 rounded-md
                hover:bg-slate-700/40 hover:text-white transition
                {{ request()->routeIs('user.roles.*') ? 'bg-slate-700/60 text-white' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
          <path d="M12 14V22H4C4 17.5817 7.58172 14 12 14ZM18 21.5L15.0611 23.0451L15.6224 19.7725L13.2447 17.4549L16.5305 16.9775L18 14L19.4695 16.9775L22.7553 17.4549L20.3776 19.7725L20.9389 23.0451L18 21.5ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13Z"></path>
        </svg>
        <span>Role</span>
      </a>
      @endHasPermission

      @hasPermission('users.list')
      <a href="{{ route('users.list') }}"
         class="flex items-center gap-3 px-2.5 py-2 rounded-md hover:bg-slate-700/40 hover:text-white transition
                {{ request()->routeIs('users.*') ? 'bg-slate-700/60 text-white' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
          <path d="M8 4H21V6H8V4ZM3 3.5H6V6.5H3V3.5ZM3 10.5H6V13.5H3V10.5ZM3 17.5H6V20.5H3V17.5ZM8 11H21V13H8V11ZM8 18H21V20H8V18Z"></path>
        </svg>
        <span>User</span>
      </a>
      @endHasPermission

      @hasPermission('business-settings.edit')
        <a href="{{ route('business-settings.edit') }}"
          class="flex items-center gap-3 px-2.5 py-2 rounded-md hover:bg-slate-700/40 hover:text-white transition
          {{ request()->routeIs('business-settings.edit') ? 'bg-slate-700/60 text-white' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
            <path d="M13 14.0619V22H4C4 17.5817 7.58172 14 12 14C12.3387 14 12.6724 14.021 13 14.0619ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM17.7929 19.9142L21.3284 16.3787L22.7426 17.7929L17.7929 22.7426L14.2574 19.2071L15.6716 17.7929L17.7929 19.9142Z"></path>
          </svg>
          <span>Business Setting</span>
        </a>
      @endHasPermission



      <!-- Blog Category Menu -->
      {{-- @hasPermission('blog.category.list') --}}
      <a href="{{ route('blog.category.list') }}"
        class="cursor-pointer w-full list-none flex items-center gap-3 rounded-lg px-3 py-2.5 text-left
                text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
                ring-1 ring-transparent hover:ring-slate-600 transition
                {{ request()->routeIs('blog.category.*') ? 'bg-slate-700/60 text-white ring-slate-600' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
          <path d="M9 2V4H5V8H3V2H9ZM15 2H21V8H19V4H15V2ZM3 10H5V14H3V10ZM19 10H21V14H19V10ZM9 20V22H3V16H5V20H9ZM19 20V16H21V22H15V20H19ZM17 8C17.5523 8 18 8.44772 18 9V15C18 15.5523 17.5523 16 17 16H7C6.44772 16 6 15.5523 6 15V9C6 8.44772 6.44772 8 7 8H17ZM16 10H8V14H16V10Z"></path>
        </svg>
        <span>Blog Categories</span>
      </a>
      {{-- @endHasPermission --}}

      <!-- Blog Menu -->
      {{-- @hasPermission('blog.list') --}}
      <a href="{{ route('blog.list') }}"
        class="cursor-pointer w-full list-none flex items-center gap-3 rounded-lg px-3 py-2.5 text-left
                text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
                ring-1 ring-transparent hover:ring-slate-600 transition
                {{ request()->routeIs('blog.list') || request()->routeIs('blog.create') || request()->routeIs('blog.edit') ? 'bg-slate-700/60 text-white ring-slate-600' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
          <path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z"></path>
        </svg>
        <span>Blogs</span>
      </a>
      {{-- @endHasPermission --}}

      <!-- Notice Menu (Simple) -->
      @hasPermission('notice.list')
        <a href="{{ route('notice.list') }}"
          class="cursor-pointer w-full list-none flex items-center gap-3 rounded-lg px-3 py-2.5 text-left
                  text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
                  ring-1 ring-transparent hover:ring-slate-600 transition
                  {{ request()->routeIs('notice.*') ? 'bg-slate-700/60 text-white ring-slate-600' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
            <path d="M5.46257 4.43262C7.21556 2.91688 9.5007 2 12 2C17.5228 2 22 6.47715 22 12C22 14.1361 21.3302 16.1158 20.1892 17.7406L17 12H20C20 7.58172 16.4183 4 12 4C9.84982 4 7.89777 4.84827 6.46023 6.22842L5.46257 4.43262ZM18.5374 19.5674C16.7844 21.0831 14.4993 22 12 22C6.47715 22 2 17.5228 2 12C2 9.86386 2.66979 7.88416 3.8108 6.25944L7 12H4C4 16.4183 7.58172 20 12 20C14.1502 20 16.1022 19.1517 17.5398 17.7716L18.5374 19.5674Z"></path>
          </svg>
          <span>Notices</span>
        </a>
      @endHasPermission


      <!-- FAQ Menu (Simple) -->
      @hasPermission('faq.list')
        <a href="{{ route('faq.list') }}"
          class="cursor-pointer w-full list-none flex items-center gap-3 rounded-lg px-3 py-2.5 text-left
                  text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
                  ring-1 ring-transparent hover:ring-slate-600 transition
                  {{ request()->routeIs('faq.*') ? 'bg-slate-700/60 text-white ring-slate-600' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
            <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 15H13V17H11V15ZM13 13.3551V14H11V12.5C11 11.9477 11.4477 11.5 12 11.5C12.8284 11.5 13.5 10.8284 13.5 10C13.5 9.17157 12.8284 8.5 12 8.5C11.2723 8.5 10.6656 9.01823 10.5288 9.70577L8.56731 9.31346C8.88637 7.70919 10.302 6.5 12 6.5C13.933 6.5 15.5 8.067 15.5 10C15.5 11.5855 14.4457 12.9248 13 13.3551Z"></path>
          </svg>
          <span>FAQs</span>
        </a>
      @endHasPermission

      <!-- Settings Menu (Simple) -->
      @hasPermission('settings.view')
        <a href="{{ route('settings.index') }}"
          class="cursor-pointer w-full list-none flex items-center gap-3 rounded-lg px-3 py-2.5 text-left
                  text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
                  ring-1 ring-transparent hover:ring-slate-600 transition
                  {{ request()->routeIs('settings.*') ? 'bg-slate-700/60 text-white ring-slate-600' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
            <path d="M12 1L21.5 6.5V17.5L12 23L2.5 17.5V6.5L12 1ZM12 3.311L4.5 7.653V16.347L12 20.689L19.5 16.347V7.653L12 3.311ZM12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12C16 14.2091 14.2091 16 12 16ZM12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z"></path>
          </svg>
          <span>Policies</span>
        </a>
      @endHasPermission


      <!-- Promotion Popup Menu (Simple) -->
      @hasPermission('promotion-popup.list')
      <a href="{{ route('promotion-popup.list') }}"
        class="cursor-pointer w-full list-none flex items-center gap-3 rounded-lg px-3 py-2.5 text-left
                text-sm font-semibold text-slate-200 hover:text-white hover:bg-slate-700/50
                ring-1 ring-transparent hover:ring-slate-600 transition
                {{ request()->routeIs('promotion-popup.*') ? 'bg-slate-700/60 text-white ring-slate-600' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
          <path d="M12.001 4.52853C14.35 2.42 17.98 2.49 20.2426 4.75736C22.5053 7.02472 22.583 10.637 20.4786 12.993L11.9999 21.485L3.52138 12.993C1.41705 10.637 1.49571 7.01901 3.75736 4.75736C6.02157 2.49315 9.64519 2.41687 12.001 4.52853ZM18.827 6.1701C17.3279 4.66794 14.9076 4.60701 13.337 6.01687L12.0019 7.21524L10.6661 6.01781C9.09098 4.60597 6.67506 4.66808 5.17157 6.17157C3.68183 7.66131 3.60704 10.0473 4.97993 11.6232L11.9999 18.6543L19.0201 11.6232C20.3935 10.0467 20.319 7.66525 18.827 6.1701Z"></path>
        </svg>
        <span>Promotion Popups</span>
      </a>
      @endHasPermission




      @hasPermission('header-sliders.edit')
        <a href="{{ route('header-sliders.index') }}"
          class="flex items-center gap-3 px-2.5 py-2 rounded-md hover:bg-slate-700/40 hover:text-white transition
          {{ request()->routeIs('header-sliders.*') ? 'bg-slate-700/60 text-white' : 'text-slate-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
                <path d="M13 14.0619V22H4C4 17.5817 7.58172 14 12 14C12.3387 14 12.6724 14.021 13 14.0619ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM17.7929 19.9142L21.3284 16.3787L22.7426 17.7929L17.7929 22.7426L14.2574 19.2071L15.6716 17.7929L17.7929 19.9142Z"></path>
            </svg>
            <span>Header Sliders</span>
        </a>
      @endHasPermission

      @hasPermission('footer-sliders.edit')
        <a href="{{ route('footer-sliders.index') }}"
          class="flex items-center gap-3 px-2.5 py-2 rounded-md hover:bg-slate-700/40 hover:text-white transition
          {{ request()->routeIs('footer-sliders.*') ? 'bg-slate-700/60 text-white' : 'text-slate-200' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
                <path d="M13 14.0619V22H4C4 17.5817 7.58172 14 12 14C12.3387 14 12.6724 14.021 13 14.0619ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM17.7929 19.9142L21.3284 16.3787L22.7426 17.7929L17.7929 22.7426L14.2574 19.2071L15.6716 17.7929L17.7929 19.9142Z"></path>
            </svg>
            <span>Footer Sliders</span>
        </a>
      @endHasPermission



      {{-- @hasPermission('account-user.index') --}}
      {{-- <a href="#"
         class="flex items-center gap-3 px-2.5 py-2 rounded-md hover:bg-slate-700/40 hover:text-white transition">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 opacity-90">
          <path d="M13 4H21V6H13V4ZM13 11H21V13H13V11ZM13 18H21V20H13V18ZM6.5 19C5.39543 19 4.5 18.1046 4.5 17C4.5 15.8954 5.39543 15 6.5 15C7.60457 15 8.5 15.8954 8.5 17C8.5 18.1046 7.60457 19 6.5 19ZM6.5 21C8.70914 21 10.5 19.2091 10.5 17C10.5 14.7909 8.70914 13 6.5 13C4.29086 13 2.5 14.7909 2.5 17C2.5 19.2091 4.29086 21 6.5 21ZM5 6V9H8V6H5ZM3 4H10V11H3V4Z"></path>
        </svg>
        <span>Change Password</span>
      </a> --}}
      {{-- @endHasPermission --}}
    </div>
  </details>

  <!-- Logout -->
  <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-slate-700/60">
    @csrf
    <button type="submit"
            class="w-full text-left rounded-lg px-3 py-2.5 font-semibold text-sm
                   text-rose-300 hover:text-rose-100 hover:bg-rose-500/10
                   ring-1 ring-transparent hover:ring-rose-400/30 transition">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 opacity-90">
          <path d="M3 12C3 12.5523 3.44772 13 4 13H10C10.5523 13 11 12.5523 11 12V4C11 3.44772 10.5523 3 10 3H4C3.44772 3 3 3.44772 3 4V12ZM3 20C3 20.5523 3.44772 21 4 21H10C10.5523 21 11 20.5523 11 20V16C11 15.4477 10.5523 15 10 15H4C3.44772 15 3 15.4477 3 16V20ZM13 20C13 20.5523 13.4477 21 14 21H20C20.5523 21 21 20.5523 21 20V12C21 11.4477 20.5523 11 20 11H14C13.4477 11 13 11.4477 13 12V20ZM14 3C13.4477 3 13 3.44772 13 4V8C13 8.55228 13.4477 9 14 9H20C20.5523 9 21 8.55228 21 8V4C21 3.44772 20.5523 3 20 3H14Z"></path>
        </svg>
        <span>Logout</span>
      </div>
    </button>
  </form>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const currentPath = window.location.pathname;

    // সব details element চেক করো
    document.querySelectorAll('details').forEach(function (details) {
        const activeLink = details.querySelector('a.menu-link[href]');
        if (!activeLink) return;

        // details এর ভেতরে কোনো link active কিনা চেক করো
        const hasActive = Array.from(details.querySelectorAll('a[href]')).some(function (link) {
            return link.pathname === currentPath || currentPath.startsWith(link.pathname);
        });

        if (hasActive) {
            details.setAttribute('open', true);
        }
    });

    // Active link highlight
    document.querySelectorAll('.menu-link').forEach(function (link) {
        if (link.pathname === currentPath || currentPath.startsWith(link.pathname)) {
            link.classList.add('bg-slate-700/60', 'text-white');
        }
    });
});
</script>