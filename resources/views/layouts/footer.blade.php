<footer class="bg-white border-top py-3 px-4 text-center">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 text-muted small text-start">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
            <div class="col-md-6 text-muted small text-end">
                Powered by Laravel {{ Illuminate\Foundation\Application::VERSION }}
            </div>
        </div>
    </div>
</footer>
