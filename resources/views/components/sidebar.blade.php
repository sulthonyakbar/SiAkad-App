<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand my-3">
            <a class="d-flex align-items-center justify-content-center">
                <img alt="image" src="{{ asset('images/logo-slb.png') }}"
                    style="width: 50px; height: auto; margin-right: 10px;">
                <h4>SiAkad</h4>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a>
                <img alt="image" src="{{ asset('images/logo-slb.png') }}" style="width: 40px; height: auto;">
            </a>
        </div>
        <ul class="sidebar-menu">
            @if (auth()->user()->role === 'admin')
                <li class="menu-header text-dark">Admin</li>

                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-dark">
                        <i class="fas fa-fire"></i><span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header text-dark">Master Data</li>

                {{-- Data Siswa --}}
                <li class="nav-item dropdown {{ request()->routeIs('siswa.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-users"></i><span>Data Siswa</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('siswa.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('siswa.index') ? 'active' : '' }}"
                                href="{{ route('siswa.index') }}">Daftar Siswa</a></li>
                        <li><a class="nav-link {{ request()->routeIs('siswa.create') ? 'active' : '' }}"
                                href="{{ route('siswa.create') }}">Tambah Data Siswa</a></li>
                    </ul>
                </li>

                {{-- Data Alumni --}}
                <li class="nav-item dropdown {{ request()->routeIs('alumni.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-user-graduate"></i><span>Data Alumni</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('alumni.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('alumni.index') ? 'active' : '' }}"
                                href="{{ route('alumni.index') }}">Daftar Alumni</a></li>
                    </ul>
                </li>

                {{-- Data Pegawai --}}
                <li class="nav-item dropdown {{ request()->routeIs('pegawai.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-chalkboard-teacher"></i><span>Data Pegawai</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('pegawai.*') ? 'display: block;' : '' }}">
                        <li>
                            <a class="nav-link {{ request()->routeIs('pegawai.guru.index') ? 'active' : '' }}"
                                href="{{ route('pegawai.guru.index') }}">Daftar Guru</a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->routeIs('pegawai.admin.index') ? 'active' : '' }}"
                                href="{{ route('pegawai.admin.index') }}">Daftar Admin</a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->routeIs('pegawai.create') ? 'active' : '' }}"
                                href="{{ route('pegawai.create') }}">Tambah Data Pegawai</a>
                        </li>
                    </ul>
                </li>

                {{-- Kelas --}}
                <li class="nav-item dropdown {{ request()->routeIs('kelas.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-door-open"></i><span>Data Kelas</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('kelas.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('kelas.index') ? 'active' : '' }}"
                                href="{{ route('kelas.index') }}">Daftar Kelas</a></li>
                        <li><a class="nav-link {{ request()->routeIs('kelas.create') ? 'active' : '' }}"
                                href="{{ route('kelas.create') }}">Tambah Data Kelas</a></li>
                    </ul>
                </li>

                {{-- Mata Pelajaran --}}
                <li class="nav-item dropdown {{ request()->routeIs('mapel.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-book"></i><span>Data Mata Pelajaran</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('mapel.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('mapel.index') ? 'active' : '' }}"
                                href="{{ route('mapel.index') }}">Daftar Mapel</a></li>
                        <li><a class="nav-link {{ request()->routeIs('mapel.create') ? 'active' : '' }}"
                                href="{{ route('mapel.create') }}">Tambah Data Mapel</a></li>
                    </ul>
                </li>

                <li class="menu-header text-dark">Akademik</li>

                {{-- Nilai --}}
                <li class="nav-item dropdown {{ request()->routeIs('admin.nilai.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-chart-bar"></i><span>Data Nilai</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('admin.nilai.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('admin.nilai.index') ? 'active' : '' }}"
                                href="{{ route('admin.nilai.index') }}">Daftar Nilai</a></li>
                    </ul>
                </li>

                {{-- Presensi --}}
                <li class="nav-item dropdown {{ request()->routeIs('admin.presensi.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-user-check"></i><span>Data Presensi</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('admin.presensi.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('admin.presensi.index') ? 'active' : '' }}"
                                href="{{ route('admin.presensi.index') }}">Daftar Presensi</a></li>
                    </ul>
                </li>

                {{-- Jadwal Pelajaran --}}
                <li class="nav-item dropdown {{ request()->routeIs('jadwal.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-calendar-week"></i><span>Data Jadwal Pelajaran</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('jadwal.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('jadwal.index') ? 'active' : '' }}"
                                href="{{ route('jadwal.index') }}">Daftar Jadwal</a></li>
                        <li><a class="nav-link {{ request()->routeIs('jadwal.create') ? 'active' : '' }}"
                                href="{{ route('jadwal.create') }}">Tambah Jadwal</a></li>
                    </ul>
                </li>

                {{-- Kartu Studi --}}
                <li class="nav-item dropdown {{ request()->routeIs('kartu.studi.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-id-card-alt"></i><span>Data Kartu Studi</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('kartu.studi.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('kartu.studi.index') ? 'active' : '' }}"
                                href="{{ route('kartu.studi.index') }}">Daftar Kartu Studi</a></li>
                        <li><a class="nav-link {{ request()->routeIs('kartu.studi.create') ? 'active' : '' }}"
                                href="{{ route('kartu.studi.create') }}">Penempatan Kelas</a></li>
                    </ul>
                </li>

                {{-- Pengumuman --}}
                <li class="menu-header text-dark">Informasi</li>

                <li class="nav-item dropdown {{ request()->routeIs('pengumuman.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-bullhorn"></i><span>Pengumuman</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('pengumuman.*') ? 'display: block;' : '' }}">
                        <li>
                            <a class="nav-link {{ request()->routeIs('pengumuman.index') ? 'active' : '' }}"
                                href="{{ route('pengumuman.index') }}">Daftar Pengumuman</a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->routeIs('pengumuman.create') ? 'active' : '' }}"
                                href="{{ route('pengumuman.create') }}">Tambah Pengumuman</a>
                        </li>
                    </ul>
                </li>

                {{-- Kategori Pengumuman --}}
                <li class="nav-item dropdown {{ request()->routeIs('kategori.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-newspaper"></i><span>Kategori Pengumuman</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('kategori.*') ? 'display: block;' : '' }}">
                        <li>
                            <a class="nav-link {{ request()->routeIs('kategori.index') ? 'active' : '' }}"
                                href="{{ route('kategori.index') }}">Daftar Kategori</a>
                        </li>
                        <li>
                            <a class="nav-link {{ request()->routeIs('kategori.create') ? 'active' : '' }}"
                                href="{{ route('kategori.create') }}">Tambah Kategori</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-header text-dark">Manajemen User</li>

                <li class="nav-item dropdown {{ request()->routeIs('*akun.index') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-user-circle"></i><span>Data Akun User</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('*akun.index') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('siswa.akun.index') ? 'active' : '' }}"
                                href="{{ route('siswa.akun.index') }}">Daftar Akun Siswa</a></li>
                        <li><a class="nav-link {{ request()->routeIs('guru.akun.index') ? 'active' : '' }}"
                                href="{{ route('guru.akun.index') }}">Daftar Akun Guru</a></li>
                        <li><a class="nav-link {{ request()->routeIs('admin.akun.index') ? 'active' : '' }}"
                                href="{{ route('admin.akun.index') }}">Daftar Akun Admin</a></li>
                    </ul>
                </li>
            @endif

            @if (auth()->user()->role === 'guru')
                <li class="menu-header text-dark">Guru</li>
                <li class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('guru.dashboard') }}" class="nav-link text-dark">
                        <i class="fas fa-fire"></i><span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header text-dark">Non Akademik</li>

                {{-- Data Aktivitas Siswa --}}
                <li class="nav-item dropdown {{ request()->routeIs('aktivitas.*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-running"></i><span>Data Aktivitas Harian</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('aktivitas.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('kartu.studi.index') ? 'active' : '' }}"
                                href="{{ route('aktivitas.index') }}">Daftar Aktivitas Harian</a></li>
                        <li><a class="nav-link {{ request()->routeIs('kartu.studi.create') ? 'active' : '' }}"
                                href="{{ route('aktivitas.create') }}">Tambah Aktivitas Harian</a></li>
                    </ul>
                </li>

                <li class="menu-header text-dark">Akademik</li>

                {{-- Presensi --}}
                <li class="nav-item dropdown {{ request()->routeIs('presensi.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-user-check"></i><span>Data Presensi</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('presensi.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('presensi.index') ? 'active' : '' }}"
                                href="{{ route('presensi.index') }}">Daftar Presensi </a></li>
                        <li><a class="nav-link {{ request()->routeIs('presensi.create') ? 'active' : '' }}"
                                href="{{ route('presensi.create') }}">Tambah Presensi</a></li>
                    </ul>
                </li>

                {{-- Nilai --}}
                <li class="nav-item dropdown {{ request()->routeIs('nilai.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-chart-bar"></i><span>Data Nilai</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('nilai.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('nilai.index') ? 'active' : '' }}"
                                href="{{ route('nilai.index') }}">Daftar Nilai</a></li>
                        {{-- <li><a class="nav-link {{ request()->routeIs('nilai.create') ? 'active' : '' }}"
                                href="">Tambah Nilai</a></li> --}}
                    </ul>
                </li>

                {{-- Bobot Penilaian --}}
                <li class="nav-item dropdown {{ request()->routeIs('bobot.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-balance-scale"></i><span>Data Bobot Penilaian</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('bobot.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('bobot.index') ? 'active' : '' }}"
                                href="{{ route('bobot.index') }}">Daftar Bobot Penilaian</a></li>
                        <li><a class="nav-link {{ request()->routeIs('bobot.create') ? 'active' : '' }}"
                                href="{{ route('bobot.create') }}">Tambah Bobot Penilaian</a></li>
                    </ul>
                </li>

                {{-- Rekapan Siswa --}}
                <li class="nav-item dropdown {{ request()->routeIs('rekapan.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-book"></i><span>Data Rekapan Siswa</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('rekapan.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('rekapan.index') ? 'active' : '' }}"
                                href="{{ route('rekapan.index') }}">Daftar Rekapan Siswa</a></li>
                    </ul>
                </li>
            @endif

            @if (auth()->user()->role === 'orangtua')
                <li class="menu-header text-dark">Siswa</li>
                <li class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('siswa.dashboard') }}" class="nav-link text-dark">
                        <i class="fas fa-fire"></i><span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header text-dark">Non Akademik</li>

                {{-- Feedback --}}
                <li class="nav-item dropdown {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-comments"></i><span>Data Aktivitas Harian</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('feedback.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('feedback.index') ? 'active' : '' }}"
                                href="{{ route('feedback.index') }}">Daftar Aktivitas Harian</a></li>
                        {{-- <li><a class="nav-link {{ request()->routeIs('nilai.create') ? 'active' : '' }}"
                                href="">Tambah Nilai</a></li> --}}
                    </ul>
                </li>

                <li class="menu-header text-dark">Akademik</li>

                {{-- Presensi --}}
                <li class="nav-item dropdown {{ request()->routeIs('siswa.presensi.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-user-check"></i><span>Data Presensi Siswa</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('siswa.presensi.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('siswa.presensi.index') ? 'active' : '' }}"
                                href="{{ route('siswa.presensi.index') }}">Daftar Presensi</a></li>
                    </ul>
                </li>

                {{-- Nilai --}}
                <li class="nav-item dropdown {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-chart-bar"></i><span>Data Nilai Siswa</span>
                    </a>
                    <ul class="dropdown-menu"
                        style="{{ request()->routeIs('siswa.nilai.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('siswa.nilai.index') ? 'active' : '' }}"
                                href="{{ route('siswa.nilai.index') }}">Daftar Nilai</a></li>
                    </ul>
                </li>

                 {{-- Rekapan Siswa --}}
                <li class="nav-item dropdown {{ request()->routeIs('siswa.rekapan.*') ? 'active' : '' }}">
                    <a href="#" class="nav-link has-dropdown text-dark">
                        <i class="fas fa-book"></i><span>Data Rekapan Siswa</span>
                    </a>
                    <ul class="dropdown-menu" style="{{ request()->routeIs('siswa.rekapan.*') ? 'display: block;' : '' }}">
                        <li><a class="nav-link {{ request()->routeIs('siswa.rekapan.index') ? 'active' : '' }}"
                                href="{{ route('siswa.rekapan.index') }}">Daftar Rekapan Siswa</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </aside>
</div>
