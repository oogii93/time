<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px; background-color: #3498db;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light" style="font-size: smaller; color: rgb(14, 13, 13);">太成ホールディングス</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">


                <div class="nav-item">
                    @can('departure_record_access')
                        <a href="{{ route('admin.departure-records.index') }}"
                            class="nav-link {{ request()->is('admin/departure-records') || request()->is('admin/departure-records/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-clock" style="color: rgb(15, 14, 14);"></i>
                            <span style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                出退勤時間
                            </span>
                        </a>
                    @endcan
                </div>




                @can('user_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }} {{ request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'active' : '' }} {{ request()->is('admin/users*') ? 'active' : '' }} {{ request()->is('admin/audit-logs*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa fa-calculator" style="color: rgb(15, 14, 14);"></i>
                            <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                経理計算
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">


                            <div class="nav-item">
                                <a href="{{ route('admin.index') }}"
                                    class="nav-link {{ request()->is('admin/index') ? 'active' : '' }}">
                                    <i class="fa fa-calculator" aria-hidden="true" style="color: black;"></i>
                                    <span style="font-size: smaller; color: black;">
                                        経理員
                                    </span>
                                </a>
                            </div>

                            <div class="nav-item">
                                <a href="{{ route('admin.CSV.index') }}"
                                    class="nav-link {{ request()->is('admin/CSV') ? 'active' : '' }}">
                                    <i class="fa fa-file" aria-hidden="true" style="color: black;"></i>

                                    <span style="font-size: smaller; color: black;">
                                        CSV
                                    </span>
                                </a>
                            </div>

                            <div class="nav-item">
                                <a href="{{ route('admin.CSVDay.index') }}"
                                    class="nav-link {{ request()->is('admin/CSVDay') ? 'active' : '' }}">
                                    <i class="fa fa-calculator" aria-hidden="true" style="color: black;"></i>
                                    <span style="font-size: smaller; color: black;">
                                        日付別CSV
                                    </span>
                                </a>
                            </div>







                        </ul>
                    </li>
                @endcan







                @can('user_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }} {{ request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'active' : '' }} {{ request()->is('admin/users*') ? 'active' : '' }} {{ request()->is('admin/audit-logs*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-users" style="color: rgb(15, 14, 14);"></i>
                            <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                ユーザーマネージ
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}"
                                        class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt" style="color: rgb(15, 14, 14);"></i>
                                        <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                            パーミッション </p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase" style="color: rgb(15, 14, 14);"></i>
                                        <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                            ロール
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user" style="color: rgb(15, 14, 14);"></i>
                                        <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                            ユーザーリスト
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt" style="color: rgb(15, 14, 14);"></i>
                                        <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                            アウディト
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('department_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.departments.index') }}"
                                        class="nav-link {{ request()->is('admin/departments') || request()->is('admin/departments/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-code-branch" style="color: rgb(15, 14, 14);">

                                        </i>
                                        <p style="font-size: smaller; color: black;">
                                            所属
                                        </p>
                                    </a>
                                </li>
                            @endcan




                        </ul>
                    </li>
                @endcan

                @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}"
                                href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon" style="color: black;"></i>
                                <!-- Changed color to black -->
                                <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                    パスワード変更
                                </p>
                            </a>
                        </li>
                    @endcan
                @endif

                <!--nyraw-->






                <!--butsah button -->

                <li class="nav-item">
                    <a href="{{ '/home' }}" class="nav-link">
                        <i class='fas fa-business-time' style="color: rgb(15, 14, 14);"></i>
                        <!-- Changed color to black -->
                        <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                            時間登録
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon" style="color: black;"></i>
                            <!-- Changed color to black -->
                            <p style="font-size: smaller; color: black;"> <!-- Changed color to black -->
                                ログアウト
                            </p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
