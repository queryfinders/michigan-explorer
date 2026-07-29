<!-- 3. Featured Hotels (Component-Driven) -->
<section class="section-padding bg-light overflow-hidden">
    <div class="container">
        
        <x-section-header 
            title="Featured Hotels" 
            subtitle="Discover handpicked hotels, luxury resorts, boutique stays, and budget-friendly accommodations across Michigan."
            actionUrl="{{ route('web.hotels.index') }}"
            actionText="View All Hotels"
        />
        
        <div class="row g-4 mt-2">
            @if(isset($hotels) && $hotels->count() > 0)
                @foreach($hotels->take(3) as $hotel)
                <div class="col-lg-4 col-md-6">
                    <x-hotel-card :hotel="$hotel" :featured="false" :compact="true" />
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-hotel-card :hotel="(object)[
                        'name' => 'The Grand Hotel Resort',
                        'city' => 'Mackinac Island',
                        'description' => 'Experience the pinnacle of luxury with breathtaking views and world-class amenities.',
                        'starting_price' => '399',
                        'affiliate_url' => '#'
                    ]" :featured="false" :compact="true" />
                </div>
                @endfor
            @endif
        </div>
        
    </div>
</section>
