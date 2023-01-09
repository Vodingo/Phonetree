<a href="{{ route('logout') }}" onclick="event.preventDefault(); localStorage.clear();
    document.getElementById('logout-form').submit();" data-icon="user" class="ui-btn-right" data-theme="b" class="pt-4">
   Logout
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>