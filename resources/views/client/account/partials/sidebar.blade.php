<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <div class="flex-shrink-0">
                <i class="fas fa-user-circle fa-2x text-primary"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                <small class="text-muted">{{ auth()->user()->email }}</small>
            </div>
        </div>

        <div class="list-group list-group-flush">
            <a href="{{ route('account') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('account') ? 'active' : '' }}">
                <i class="fas fa-user me-2"></i>Profile
            </a>
            <a href="{{ route('account.orders') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('account.orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag me-2"></i>Orders
            </a>
            <a href="{{ route('transactions.history') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('transactions.history*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag me-2"></i>Lịch sử giao dịch
            </a>
            <form action="{{ route('logout') }}" method="POST" class="list-group-item">
                @csrf
                <button type="submit" class="btn btn-link text-danger p-0">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
</div>
