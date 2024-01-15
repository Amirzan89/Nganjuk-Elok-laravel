@if($userAuth['role'] != 'masyarakat')
<li class="nav-item {{ $nav == 'dashboard' ? 'active' : ''}}">
  <a class="nav-link " href="/dashboard">
    <i class="bi bi-grid"></i>
    <span>Beranda</span>
  </a>
</li>
@endif

@if($userAuth['role'] == 'super admin' || $userAuth['role'] == 'admin event')
<li class="nav-item {{ $nav == 'event' ? 'active' : ''}}">
  <a class="nav-link " href="/event">
    <i class="bi bi-calendar-event"></i>
    <span>Kelola Event</span>
  </a>
</li>
@endif

@if($userAuth['role'] == 'super admin' || $userAuth['role'] == 'admin tempat')
<li class="nav-item {{ $nav == 'tempat' ? 'active' : ''}}">
  <a class="nav-link " href="/tempat">
    <i class="bi bi-building"></i>
    <span>Kelola Tempat</span>
  </a>
</li>
@endif

@if($userAuth['role'] == 'super admin' || $userAuth['role'] == 'admin seniman')
<li class="nav-item {{ $nav == 'seniman' ? 'active' : ''}}">
  <a class="nav-link " href="/seniman">
    <i class="bi bi-people"></i>
    <span>Kelola Seniman</span>
  </a>
</li>
@endif

@if($userAuth['role'] == 'super admin' || $userAuth['role'] == 'admin seniman')
<li class="nav-item {{ $nav == 'pentas' ? 'active' : ''}}">
  <a class="nav-link " href="/pentas">
    <i class="bi bi-megaphone"></i>
    <span>Kelola Izin Pentas</span>
  </a>
</li>
@endif

@if(in_array($userAuth['role'],['super admin','admin event','admin seniman','admin tempat']))
<li class="nav-item {{ $nav == 'pengguna' ? 'active' :  ''}}">
  <a class="nav-link " href="/pengguna">
    <i class="bi bi-people"></i>
    <span>Kelola Pengguna</span>
  </a>
</li>
@endif

@if($userAuth['role'] == 'super admin')
<li class="nav-item {{ $nav == 'admin' ? 'active' :  ''}}">
  <a class="nav-link " href="/admin">
    <i class="bi bi-people"></i>
    <span>Kelola Admin</span>
  </a>
</li>
@endif