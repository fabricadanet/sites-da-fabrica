<!-- resources/views/components/landing-pricing.blade.php -->
<section id="planos" class="py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 gap-8">
            @foreach($plans as $plan)
                <div class="pricing-card">
                    <h3>{{ $plan->name }}</h3>
                    <div class="text-4xl font-bold">
                        R$ {{ number_format($plan->price, 2, ',', '.') }}
                    </div>
                    <p>{{ $plan->description }}</p>
                    
                    <ul>
                        @foreach($plan->features as $feature)
                            <li>
                                <i class="fas fa-check text-green-500"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>