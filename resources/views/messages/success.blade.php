@if ($msg = Session::get('success'))
    
    <div id="success-message" class="popup card green darken-1">
        <div class="card-content white-text">
            <span class="card-title">Pronto!</span>
            <p>{{ Session::get('success') }}</p>
        </div>
    </div>

@endif

