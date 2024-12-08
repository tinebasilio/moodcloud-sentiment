@extends('layouts.app')

@section('title', 'Settings')
@section('header', 'Settings')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="card bg-base-200 shadow-xl">
        <div class="card-body">
            <h2 class="card-title text-2xl mb-6">Application Settings</h2>
            
            <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Theme Settings -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-lg font-medium">Theme</span>
                    </label>
                    <select name="theme" class="select select-bordered w-full">
                        <option value="light" {{ session('theme', 'light') === 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ session('theme', 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>
                </div>

                <!-- Language Settings -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-lg font-medium">Language</span>
                    </label>
                    <select name="language" class="select select-bordered w-full">
                        <option value="en" {{ session('language', 'en') === 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ session('language', 'en') === 'es' ? 'selected' : '' }}>Spanish</option>
                        <option value="fr" {{ session('language', 'en') === 'fr' ? 'selected' : '' }}>French</option>
                    </select>
                </div>

                <!-- Notification Settings -->
                <div class="form-control">
                    <label class="label cursor-pointer">
                        <span class="label-text text-lg font-medium">Enable Notifications</span>
                        <input type="checkbox" name="notifications" class="toggle toggle-primary" 
                               {{ session('notifications', true) ? 'checked' : '' }} />
                    </label>
                </div>

                <div class="divider"></div>

                <!-- Save Button -->
                <button type="submit" class="btn btn-primary w-full">
                    Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle theme changes
    const themeSelect = document.querySelector('select[name="theme"]');
    themeSelect?.addEventListener('change', function(e) {
        document.documentElement.setAttribute('data-theme', e.target.value);
    });

    // Show toast on successful copy
    const copyButton = document.querySelector('button[onclick]');
    copyButton?.addEventListener('click', function() {
        // Assuming you're using some toast library
        showToast('API key copied to clipboard!', 'success');
    });
});

function showToast(message, type = 'info') {
    Swal.fire({
        text: message,
        icon: type,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}
</script>
@endpush