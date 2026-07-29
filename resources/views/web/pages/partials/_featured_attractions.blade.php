<!-- 5. Featured Attractions -->
<section class="section-padding bg-light pb-0">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <div>
                <h2 class="section-title mb-0">Must-See Attractions</h2>
                <p class="section-subtitle mb-0 mt-2">Discover the hidden gems and natural wonders of the Great Lakes state.</p>
            </div>
            <a href="{{ route('web.attractions.index') }}" class="btn btn-outline-primary rounded-pill">View All Attractions</a>
        </div>
        
        <div class="row g-4">
             @if(isset($attractions) && $attractions->count() > 0)
                @foreach($attractions->take(3) as $index => $attraction)
                <div class="col-lg-4 col-md-6">
                    <x-attraction-card :attraction="$attraction" :featured="false" />
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-attraction-card :attraction="(object)[
                        'name' => $i === 1 ? 'Pictured Rocks National Lakeshore' : 'Sleeping Bear Dunes',
                        'slug' => 'demo',
                        'city' => $i === 1 ? 'Munising' : 'Empire',
                        'description' => $i === 1 ? 'Experience majestic sandstone cliffs, pristine waterfalls, and turquoise waters.' : 'Experience towering sand dunes and spectacular views of Lake Michigan at this national lakeshore.',
                        'distance' => '2.5 miles away',
                        'travel_time_car' => '10 min drive',
                        'travel_time_walk' => '45 min walk',
                    ]" :featured="false" />
                </div>
                @endfor
            @endif
        </div>
    </div>
</section>
