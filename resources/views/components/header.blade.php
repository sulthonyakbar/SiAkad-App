<head>
    {{-- <style>
        .dropdown-item-unread {
            background-color: #f9f9f9;
        }

        .dropdown-item-read {
            background-color: #e9ecef;
        }
    </style> --}}
</head>

<div class="navbar-bg" style="background-color: #4A628A"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item mr-3">
                <span
                    class="nav-link font-weight-bold bg-white rounded-lg text-primary">{{ $currentAcademicYear ?? '-' }}</span>
            </li>
            <li class="nav-item mr-3">
                <span
                    class="nav-link font-weight-bold bg-white rounded-lg text-primary">{{ $currentSemester ?? '-' }}</span>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link" id="current-day"></span>
            </li>
            <li class="nav-item">
                <span class="nav-link" id="current-date"></span>
            </li>
            <li class="nav-item">
                <span class="nav-link" id="current-time"></span>
            </li>
        </ul>
    </div>

    <script>
        function updateTime() {
            var now = new Date();
            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
                'November', 'Desember'
            ];
            var day = days[now.getDay()];
            var time = now.toLocaleTimeString('en-US', {
                hour12: false
            });
            var date = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

            document.getElementById('current-day').textContent = day;
            document.getElementById('current-time').textContent = time;
            document.getElementById('current-date').textContent = date;

            setTimeout(updateTime, 1000);
        }

        updateTime();
    </script>

    <ul class="navbar-nav navbar-right">

        {{-- @if (auth()->user()->role === 'anggota')
            @php
                $unreadCount = $notifications->where('read', false)->count();
            @endphp
            <li class="dropdown dropdown-list-toggle">
                <a href="#" data-toggle="dropdown"
                    class="nav-link notification-toggle nav-link-lg {{ $unreadCount > 0 ? 'beep' : '' }}">
        <i class="far fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-list dropdown-menu-right">
            <div class="dropdown-header">Notifikasi
                <div class="float-right">
                    <form action="{{ route('notifikasi.tandaiBacaSemua') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link">Tandai semua telah dibaca</button>
                    </form>
                </div>
            </div>
            <div class="dropdown-list-content dropdown-list-icons">
                @forelse($notifications as $notification)
                @php
                $data = json_decode($notification->data);
                @endphp
                <a href="#"
                    class="dropdown-item {{ $notification->read ? 'dropdown-item-read' : 'dropdown-item-unread' }} d-flex align-items-center">
                    <div class="dropdown-item-icon text-white"
                        style="background-color: {{ $notification->type == 'approval' ? ($notification->read ? '#a1e4cd' : '#39AE86') : ($notification->read ? '#df939e' : '#E74C3C') }};">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        {{ $data->message }}
                        <div class="description">
                            <small>{{ $data->description ?? '-' }}</small>
                        </div>
                        <div class="time">
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </a>
                @empty
                <div class="dropdown-item dropdown-item-read">
                    <div class="dropdown-item-icon bg-secondary text-white">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        Tidak ada notifikasi baru.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        </li>
        @endif --}}

        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                @if (auth()->user()->role === 'orangtua' && auth()->user()->siswa->foto)
                <img alt="Foto Profile" src="{{ asset(Auth::user()->siswa->foto) }}" class="rounded-circle mr-1"
                    style="max-width: 30px; max-height: 35px;">
                @elseif (auth()->user()->role === 'guru' || auth()->user()->role === 'admin' && auth()->user()->guru->foto)
                <img alt="Foto Profile" src="{{ asset(Auth::user()->guru->foto) }}" class="rounded-circle mr-1"
                    style="max-width: 30px; max-height: 35px;">
                @else
                <img alt="image" src="{{ asset('img/avatar/avatar-3.png') }}" class="rounded-circle mr-1"
                    style="max-width: 30px; max-height: 35px;">
                @endif
                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'guru')
                <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->guru->nama_guru }}</div>
                @else
                <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->siswa->nama_siswa }}</div>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @if (auth()->user()->role === 'orangtua')
                <form action="{{ route('akun.edit.siswa') }}" method="GET">
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Setting Akun
                    </button>
                </form>
                @elseif (auth()->user()->role === 'guru')
                <form action="{{ route('guru.profile') }}" method="GET">
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profile
                    </button>
                </form>
                <form action="{{ route('akun.edit.guru') }}" method="GET">
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Setting Akun
                    </button>
                </form>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
