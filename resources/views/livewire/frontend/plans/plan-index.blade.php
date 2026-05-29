<div>
    @include('partials.page-hero', ['title' => 'Choose Your Plan', 'subtitle' => 'Unlock more opportunities with a premium subscription. Upgrade anytime to apply to more jobs.'])

    <div class="py-12 md:py-16" style="background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px); background-size: 24px 24px;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($plans as $plan)
            <div class="relative bg-white rounded-2xl border {{ $plan->is_recommended ? 'border-teal-400 ring-2 ring-teal-100' : 'border-gray-200' }} flex flex-col overflow-hidden shadow-sm hover:shadow-lg transition-shadow" x-data="{ tab: 'monthly' }">
                @if($plan->is_recommended)
                <span class="absolute -top-0 left-0 right-0 text-center text-xs bg-gradient-to-r from-[#464d79] to-[#48B098] text-white px-4 py-1.5 font-semibold">Most Popular</span>
                @endif

                {{-- Plan Header --}}
                <div class="p-6 {{ $plan->is_recommended ? 'pt-9' : '' }}">
                    <div class="flex items-center gap-2 mb-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $plan->ranking->getBadgeClasses() }}">{{ $plan->ranking->getLabel() }}</span>
                    </div>

                    {{-- Duration tabs --}}
                    @if($plan->options->count() > 1)
                    <div class="flex gap-1 mb-4 bg-gray-100 rounded-lg p-0.5">
                        @foreach($plan->options as $opt)
                        <button @click="tab = '{{ $opt->duration_type->value }}'" :class="tab === '{{ $opt->duration_type->value }}' ? 'bg-white shadow-sm text-[#464d79]' : 'text-gray-500'" class="flex-1 py-1.5 text-xs font-medium rounded-md transition-colors">{{ $opt->label }}</button>
                        @endforeach
                    </div>
                    @endif

                    {{-- Pricing --}}
                    @foreach($plan->options as $opt)
                    <div x-show="tab === '{{ $opt->duration_type->value }}'" x-cloak>
                        <div class="flex items-baseline gap-1">
                            @if($opt->price == 0)
                            <span class="text-3xl font-bold text-gray-900">Free</span>
                            @else
                            <span class="text-3xl font-bold text-gray-900">₹{{ number_format($opt->price) }}</span>
                            <span class="text-sm text-gray-400">/{{ $opt->duration_type->value === 'yearly' ? 'year' : 'month' }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $opt->is_unlimited ? 'Unlimited applications' : $opt->applications_per_month . ' applications/month' }}
                        </p>
                    </div>
                    @endforeach
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-100 mx-6"></div>

                {{-- Features --}}
                <div class="p-6 flex-1">
                    <ul class="space-y-2.5">
                        @foreach($plan->description ?? [] as $feature)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-teal-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- CTA --}}
                <div class="p-6 pt-0">
                    @auth
                        @php
                            $activeSub = auth()->user()->activeSubscription();
                            $isCurrentPlan = $activeSub && $activeSub->subscription_plan_id === $plan->id;
                        @endphp
                        @if($isCurrentPlan)
                        <button disabled class="w-full h-11 rounded-xl text-sm font-semibold bg-gray-100 text-gray-400 cursor-not-allowed">Current Plan</button>
                        @elseif($plan->options->first()?->price == 0)
                        <a href="{{ route('jobseeker.plan') }}" class="w-full h-11 rounded-xl text-sm font-semibold inline-flex items-center justify-center border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">View Plan</a>
                        @else
                        <a href="#" class="w-full h-11 rounded-xl text-sm font-semibold inline-flex items-center justify-center text-white transition-colors" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">Upgrade</a>
                        @endif
                    @else
                    <a href="{{ route('jobseeker.register') }}" class="w-full h-11 rounded-xl text-sm font-semibold inline-flex items-center justify-center text-white transition-colors" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">Get Started</a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>
</div>
