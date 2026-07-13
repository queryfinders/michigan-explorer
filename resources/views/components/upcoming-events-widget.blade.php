@props(['events' => collect()])

@php
    // Fallback events if none passed (for demo widget anywhere on site)
    if ($events->isEmpty()) {
        $events = \App\Models\Event::with('category')->where('status', 1)->where('start_date', '>=', now())->orderBy('start_date', 'asc')->take(3)->get();
        
        if ($events->isEmpty()) {
            $events = collect([
                (object)[
                    'name' => 'Grand Rapids Art Festival',
                    'slug' => 'demo',
                    'start_date' => now()->addDays(2),
                    'venue_name' => 'Calder Plaza',
                    'city' => 'Grand Rapids'
                ],
                (object)[
                    'name' => 'Detroit Jazz Fest',
                    'slug' => 'demo',
                    'start_date' => now()->addDays(14),
                    'venue_name' => 'Campus Martius',
                    'city' => 'Detroit'
                ],
                (object)[
                    'name' => 'Traverse City Cherry Festival',
                    'slug' => 'demo',
                    'start_date' => now()->addDays(30),
                    'venue_name' => 'Open Space Park',
                    'city' => 'Traverse City'
                ]
            ]);
        }
    }
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 font-heading">Upcoming Events</h5>
            <a href="{{ route('web.events.index') }}" class="text-primary text-decoration-none small fw-semibold">View All</a>
        </div>
    </div>
    <div class="card-body p-4">
        <ul class="list-unstyled mb-0">
            @foreach($events as $event)
                @php
                    $date = $event->start_date ? \Carbon\Carbon::parse($event->start_date) : now()->addDays(rand(1, 14));
                @endphp
                <li class="d-flex align-items-center mb-4 {{ $loop->last ? 'mb-0' : 'pb-4 border-bottom' }}">
                    
                    <!-- Small Date Box -->
                    <div class="bg-light rounded-3 text-center d-flex flex-column justify-content-center align-items-center me-3 flex-shrink-0 w-50px h-50px">
                        <span class="text-primary fw-bold text-uppercase fs-065rem lh-1">{{ $date->format('M') }}</span>
                        <span class="text-dark fw-bolder fs-5 lh-1">{{ $date->format('d') }}</span>
                    </div>
                    
                    <!-- Event Info -->
                    <div>
                        <h6 class="fw-bold mb-1 lh-sm">
                            <a href="{{ route('web.events.show', $event->slug ?? 'demo') }}" class="text-dark text-decoration-none transition-hover text-primary-hover">
                                {{ $event->name }}
                            </a>
                        </h6>
                        <div class="text-muted small">
                            <i class="fas fa-map-marker-alt text-primary opacity-75 me-1"></i> {{ $event->city ?? 'Michigan' }}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
