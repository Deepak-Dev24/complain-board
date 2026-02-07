<nav class="bg-gray-800">
<div class=" mx-auto px-2 sm:px-6 ">
<div class="relative flex h-16 items-center justify-between">

<!-- MOBILE TOGGLE -->
<div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
  <button onclick="toggleMenu()"
    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white">

    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>

  </button>
</div>


<!-- LEFT -->
<div class="flex flex-1 items-center sm:items-stretch sm:justify-start">

  


  <!-- DESKTOP MENU -->
  <div class="hidden sm:ml-6 sm:block">
    <div class="flex space-x-4">

      <a href="index.php"
        class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
        Dashboard
      </a>

      <a href="making_calls.php"
        class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
        Make Calls
      </a>
      <a href="complaints.php"
        class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
        Complaints
      </a>

    </div>
  </div>

</div>


<!-- RIGHT - LOGOUT -->
<div class="hidden sm:block">
  <a href="logout.php"
    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-red-700 hover:text-white">
    Logout
  </a>
</div>

</div>
</div>


<!-- ===== MOBILE MENU ===== -->
<div id="mobileMenu" class="hidden sm:hidden bg-gray-800">

<div class="space-y-1 px-2 pt-2 pb-3">

  <a href="index.php"
    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
    Dashboard
  </a>

  <a href="making_calls.php"
    class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white">
    Make Calls
  </a>

  <a href="logout.php"
    class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-red-700 hover:text-white">
    Logout
  </a>

</div>

</div>


<!-- TOGGLE SCRIPT -->
<script>
function toggleMenu() {
  document.getElementById("mobileMenu").classList.toggle("hidden");
}
</script>

</nav>
