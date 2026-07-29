<!-- 4. Featured Restaurants (Component-Driven) -->
<section class="overflow-hidden">
    <div class="container">
        
        <x-section-header 
            title="Featured Restaurants" 
            subtitle="Discover Michigan's best local restaurants, cafés, fine dining experiences, waterfront dining, family restaurants, and hidden culinary gems."
            actionUrl="{{ route('web.restaurants.index') }}"
            actionText="View All Restaurants"
        />
        
        <div class="row g-4 mt-2">
            @if(isset($restaurants) && $restaurants->count() > 0)
                @foreach($restaurants->take(3) as $restaurant)
                <div class="col-lg-4 col-md-6">
                    <x-restaurant-card :restaurant="$restaurant" :featured="false" :compact="true" />
                </div>
                @endforeach
            @else
                <!-- Static Fallback Data -->
                @for($i=1; $i<=3; $i++)
                <div class="col-lg-4 col-md-6">
                    <x-restaurant-card :restaurant="(object)[
                        'name' => $i === 1 ? 'Lakeside Prime Steakhouse' : 'The Harbor Cafe',
                        'city' => 'Traverse City',
                        'description' => 'Savor exquisite culinary masterpieces with breathtaking waterfront views.',
                        'starting_price' => '45',
                        'affiliate_url' => route('web.restaurants.show', 'demo'),
                        'category' => (object)['name' => $i === 1 ? 'Fine Dining' : 'Cafe']
                    ]" :featured="false" :compact="true" />
                </div>
                @endfor
            @endif
        </div>
        
    </div>
</section>
