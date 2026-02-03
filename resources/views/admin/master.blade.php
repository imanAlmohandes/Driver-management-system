<!DOCTYPE html>
{{-- <html lang="en"> --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->currentLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', config('app.name'))</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .bg-light {
            background-color: #f2f2f2 !important;
        }
    </style>

    @yield('styles')
    @if (app()->currentLocale() == 'ar')
        <style>
            /* @import url('https://fonts.googleapis.com/css2?family=Changa:wght@200..800&display=swap'); */

            body {
                direction: rtl;
                text-align: right;
            }

            .animated--grow-in {
                right: -120px !important;
            }

            .modal-header .close {
                margin: -1rem 1rem -1rem 0 !important;
            }

            span.mr-2 {
                margin-right: .5rem !important;
                margin-left: 0.5rem !important;
            }

            button.mr-2,
            a.mr-2 {
                margin-right: 0rem !important;
                margin-left: 0.5rem !important;
            }

            .mr-3 {
                margin-right: 0rem !important;
                margin-left: 1rem !important;

            }

            a.ml-2 {
                margin-right: 0.5rem !important;
                margin-left: 0rem !important;

            }

            .dropdown-header {
                text-align: center;
            }

            /* div a.newMargin {
                margin-left: 1rem !important;

            } */


            .sidebar {
                padding: 0px
            }

            .sidebar .nav-item .nav-link {
                text-align: right;
            }

            .sidebar .nav-item .nav-link[data-toggle=collapse]::after {
                float: left;
                transform: rotate(0)
            }

            .sidebar .nav-item .nav-link[data-toggle=collapse].collapsed::after {
                transform: rotate(180deg)
            }

            .ml-auto,
            .mx-auto {
                margin-right: auto !important;
                margin-left: 0 !important;
            }

            .list-group {
                padding: 0px !important;
            }

            .dropdown-item {
                text-align: right;
            }
        </style>
    @endif
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        {{-- Sidebar --}}
        @include('admin.sidebar')
        @include('admin.confirm-modal')

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                {{-- Topbar --}}
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item -lang-->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-fw fa-language mx-2"></i>
                                {{-- <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ __('driver.languages') }} (
                                    {{ app()->currentLocale() }} )</span> --}}
                            </a>
                            <!-- Dropdown -lang-->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}"
                                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                        <img width="20" src="{{ asset('assets/img/' . $properties['flag']) }}"
                                            alt=""> {{ $properties['native'] }}
                                    </a>
                                @endforeach
                            </div>

                        </li>
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" id="notif-count"
                                    style="display:none"></span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown" id="notif-list">
                                <h6 class="dropdown-header">
                                    {{ __('driver.notificationsCenter') }}
                                </h6>
                                <a class="dropdown-item text-center small text-gray-500"
                                    href="{{ route('admin.notifications.index') }}">{{ __('driver.showAllNotifications') }}</a>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('driver.profile') }}
                                </a>
                                <div class="dropdown-divider"></div>

                                <!-- Logout Form -->
                                <form method="POST" action="{{ route('logout') }}" class="w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        {{ __('driver.logout') }}
                                    </button>
                                </form>
                            </div>
                        </li>

                        {{-- نضاف جديد --}}
                        <!-- Logout Modal-->
                        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                        <button class="close" type="button" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">Select "Logout" below if you are ready to end your
                                        current session.</div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button"
                                            data-dismiss="modal">Cancel</button>
                                        <a class="btn btn-primary" href="login.html">Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </nav>

                {{-- Page Content --}}
                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            {{-- Footer --}}
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="text-center my-auto">
                        <span>© {{ config('app.name') }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
    {{-- Real-time Notification Scripts (Cleaned Up) --}}
    {{-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script>

    <!-- TinyMCE Editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.6.0/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#mytextarea',
        });
    </script>
    <script>
        const countEl = document.getElementById('notif-count');
        const listEl = document.getElementById('notif-list');

        const dropdownHeader = '<h6 class="dropdown-header">{{ __('driver.notificationsCenter') }}</h6>';
        const viewAllLink =
            `<a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.notifications.index') }}">
            {{ __('driver.showAllNotifications') }}
        </a>`;

        function loadInitialNotifications() {
            if (!countEl || !listEl) return;

            fetch('{{ route('admin.notifications.json') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // counter
                    if (data.unread > 0) {
                        countEl.innerText = data.unread;
                        countEl.style.display = 'inline-block';
                    } else {
                        countEl.style.display = 'none';
                    }

                    // dropdown html
                    let html = '';
                    if (data.notifications && data.notifications.length > 0) {
                        data.notifications.forEach(n => {
                            const isUnread = n.read_at === null ? 'font-weight-bold' : '';
                            const color = n.data?.color ?? 'primary';
                            const icon = n.data?.icon ?? 'fa-bell';
                            const text = n.data?.text ?? 'New notification';

                            const readUrl = `{{ route('admin.notifications.read', ['id' => '__ID__']) }}`
                                .replace('__ID__', n.id);

                            html += `
                        <a class="dropdown-item d-flex align-items-center" href="${readUrl}">
                            <div class="mr-3">
                                <div class="icon-circle bg-${color}">
                                    <i class="fas ${icon} text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">${new Date(n.created_at).toLocaleString()}</div>
                                <span class="${isUnread}">${text}</span>
                            </div>
                        </a>
                    `;
                        });
                    } else {
                        html =
                            `<div class="dropdown-item text-center small text-gray-500">{{ __('driver.no_notifications') }}</div>`;
                    }

                    listEl.innerHTML = dropdownHeader + html + viewAllLink;
                })
                .catch(err => console.error('Notification JSON failed:', err));
        }

        document.addEventListener('DOMContentLoaded', () => {
            // تحميل مرة واحدة ليظهر الرقم فوق الجرس
            loadInitialNotifications();

            // تحميل عند الضغط على الجرس فقط
            $('#alertsDropdown').on('click', function() {
                loadInitialNotifications();
            });
        });
    </script>

    {{-- <script>
        //  Configure Toastr
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "progressBar": true,
            "timeOut": "7000" // 7 seconds
        };

        //  Configure Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config('broadcasting.connections.pusher.key') }}',
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            forceTLS: true
        });


        //  Cache DOM Elements
        const countEl = document.getElementById('notif-count');
        const listEl = document.getElementById('notif-list');

        const dropdownHeader = '<h6 class="dropdown-header">Alerts Center</h6>';
        const viewAllLink =
            `<a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.notifications.index') }}">Show All Alerts</a>`;

        //  Real-time Listener
        Echo.private('App.Models.User.{{ Auth::id() }}')
            .notification((notification) => {
                console.log('New Notification Received:', notification);

                toastr.info(notification.text || 'You have a new notification!');
                // We simply reload all notifications to get the latest state
                loadInitialNotifications();
            });


        //     //  Manually update the counter
        //     let currentCount = parseInt(countEl.innerText) || 0;
        //     currentCount++;
        //     countEl.innerText = currentCount;
        //     countEl.style.display = 'inline-block';

        //     //  Manually add the new notification to the top of the dropdown list
        //     const newNotificationHtml = `
        // <a class="dropdown-item d-flex align-items-center font-weight-bold" href="${notification.route || '#'}">
        //     <div class="mr-3"><div class="icon-circle bg-${notification.color || 'primary'}"><i class="fas ${notification.icon || 'fa-bell'} text-white"></i></div></div>
        //     <div><div class="small text-gray-500">${new Date().toLocaleDateString()}</div><span>${notification.text}</span></div>
        // </a>
        // `;
        //     // Remove the "No new alerts" message if it exists
        //     const noAlerts = listEl.querySelector('.no-alerts');
        //     if (noAlerts) {
        //         noAlerts.remove();
        //     }
        //     // Add the new notification after the header
        //     listEl.insertAdjacentHTML('afterbegin', newNotificationHtml);
        //     // We need to re-add the header because innerHTML overwrites
        //     listEl.innerHTML = dropdownHeader + listEl.innerHTML;
        // });

        //  Function to load notifications via AJAX
        function loadInitialNotifications() {
            fetch('{{ route('admin.notifications.json') }}')
                .then(res => res.json())
                .then(data => {
                    // Update counter
                    if (data.unread > 0) {
                        countEl.innerText = data.unread;
                        countEl.style.display = 'inline-block';
                    } else {
                        countEl.style.display = 'none';
                    }

                    // Build dropdown HTML
                    let html = '';
                    if (data.notifications.length > 0) {
                        data.notifications.forEach(n => {
                            let isUnread = n.read_at === null ? 'font-weight-bold' : '';
                            html += `<a class="dropdown-item d-flex align-items-center" href="{{ url('/') }}/en/admin/notifications/${n.id}/read">
                                <div class="mr-3"><div class="icon-circle bg-${n.data.color || 'primary'}"><i class="fas ${n.data.icon || 'fa-bell'} text-white"></i></div></div>
                                <div><div class="small text-gray-500">${new Date(n.created_at).toLocaleDateString()}</div><span class="${isUnread}">${n.data.text}</span></div>
                            </a>`;
                        });
                    } else {
                        html += '<div class="dropdown-item text-center small text-gray-500">No new alerts</div>';
                    }

                    listEl.innerHTML = dropdownHeader + html + viewAllLink;
                });
        }

        // Run the initial load function when the page is ready
        document.addEventListener('DOMContentLoaded', loadInitialNotifications);
    </script> --}}

    @yield('scripts')

</body>

</html>
