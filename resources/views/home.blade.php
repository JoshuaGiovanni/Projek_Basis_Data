@extends('layouts.app')

@section('content')
<style>
body {
    background: linear-gradient(to bottom, #09101f 0%, #162e71ff 50%, #1E59D9 100%);
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: cover;
    color: #e9f2ff;
    min-height: 100vh;
    overflow-x: hidden;
    font-family: 'Inter', sans-serif;
}


section {
    background: transparent;
    padding: 6rem 2rem;
}

.panel, .rounded-xl {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s ease;
    transform: translateY(0);
}

.panel:hover, .rounded-xl:hover {
    transform: translateY(-6px);
    border-color: rgba(255, 255, 255, 0.25);
    box-shadow: 0 12px 25px rgba(0, 89, 255, 0.3);
}

.panel:hover svg, .rounded-xl:hover svg {
    transform: scale(1.12);
    transition: transform 0.3s ease;
}

h1, h2, h3, h4 {
    color: #fff;
}

.muted {
    color: #b8c7e0;
}

.btn-primary {
    background: linear-gradient(90deg, #60A5FA, #93C5FD);
    color: #0b1536;
    font-weight: 600;
    border-radius: 0.75rem;
    padding: 0.9rem 2.2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 14px rgba(147, 197, 253, 0.25);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-primary:hover {
    transform: translateY(-3px);
    background: linear-gradient(90deg, #93C5FD, #BFDBFE);
    box-shadow: 0 8px 20px rgba(147, 197, 253, 0.35);
}

.btn-outline {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.5);
    color: #fff;
    font-weight: 500;
    border-radius: 0.75rem;
    padding: 0.9rem 2.2rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-outline:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: #93C5FD;
    transform: translateY(-3px);
}
</style>
    
<section class="text-center pt-28 pb-20">
    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight tracking-tight text-white max-w-4xl mx-auto">
    Turn data into <span class="text-blue-400">insights</span>, and skills into <span class="text-blue-400">opportunities</span> — for anyone, anytime.
    </h1>
    <p class="mt-6 max-w-3xl mx-auto text-lg muted">
        Discover insights, share your skills, and unlock opportunities. DataMate connects you whether you need data analysis or can deliver it.
    </p>
    <div class="mt-10 flex justify-center gap-4">
        <a href="{{ route('register') }}" class="btn-primary">Create an account</a>
        <a href="{{ route('analysts.index') }}" class="btn-outline">Discover</a>
    </div>
</section>

<section>
    <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-white mb-4">How DataMate Works</h2>
        <p class="text-lg muted max-w-3xl mx-auto">
            Our platform provides a secure, efficient way to connect clients with data analysts for professional analytics services.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mb-24">
        <div class="panel rounded-2xl p-8">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">For Businesses</h3>
            <ul class="muted space-y-2">
                <li>• Browse verified data analysts</li>
                <li>• Post detailed project requirements</li>
                <li>• Review portfolios and ratings</li>
                <li>• Secure payment with admin verification</li>
                <li>• Receive work via cloud links</li>
            </ul>
        </div>

        <div class="panel rounded-2xl p-8">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">For Analysts</h3>
            <ul class="muted space-y-2">
                <li>• Create professional profiles</li>
                <li>• List your analytics services</li>
                <li>• Manage concurrent projects</li>
                <li>• Submit work via cloud storage</li>
                <li>• Build reputation through reviews</li>
            </ul>
        </div>

        <div class="panel rounded-2xl p-8">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Secure Process</h3>
            <ul class="muted space-y-2">
                <li>• Admin-verified payments</li>
                <li>• Structured workflow</li>
                <li>• Real-time tracking</li>
                <li>• Dispute resolution</li>
                <li>• Cloud sharing</li>
            </ul>
        </div>
    </div>

<div class="mb-20">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-white mb-4">Simple Process</h2>
        <p class="text-lg muted">From project posting to completion in 5 easy steps</p>
    </div>

    <div class="max-w-5xl mx-auto">
        <div class="grid md:grid-cols-5 gap-8 items-start">
            @for ($i = 1; $i <= 5; $i++)
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/5 text-blue-400 border-2 border-white/10 rounded-full flex items-center justify-center text-xl font-bold mx-auto mb-4">
                        {{ $i }}
                    </div>
                    <h4 class="font-semibold text-white mb-2">
                        {{ ['Browse & Book','Project Brief','Analysis','Delivery','Payment'][$i-1] }}
                    </h4>
                    <p class="text-sm muted">
                        {{ ['Find the right analyst and book their services','Submit detailed requirements and data','Analyst works on your project','Receive results via secure cloud link','Secure payment with admin verification'][$i-1] }}
                    </p>
                </div>
            @endfor
        </div>
    </div>
</div>

<div class="mb-20">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-white mb-4">Popular Analytics Services</h2>
        <p class="text-lg muted">Professional data analysis for every business need</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="rounded-xl p-6 border border-white/10 bg-white/5 hover:border-blue-400 transition-all duration-300 transform hover:-translate-y-2 hover:drop-shadow-[0_0_15px_rgba(59,130,246,0.3)]">
            <h4 class="font-semibold text-white mb-2">Data Visualization</h4>
            <p class="text-sm muted">Interactive dashboards and reports</p>
        </div>
        <div class="rounded-xl p-6 border border-white/10 bg-white/5 hover:border-blue-400 transition-all duration-300 transform hover:-translate-y-2 hover:drop-shadow-[0_0_15px_rgba(59,130,246,0.3)]">
            <h4 class="font-semibold text-white mb-2">Predictive Analytics</h4>
            <p class="text-sm muted">Forecasting and trend analysis</p>
        </div>
        <div class="rounded-xl p-6 border border-white/10 bg-white/5 hover:border-blue-400 transition-all duration-300 transform hover:-translate-y-2 hover:drop-shadow-[0_0_15px_rgba(59,130,246,0.3)]">
            <h4 class="font-semibold text-white mb-2">Business Intelligence</h4>
            <p class="text-sm muted">Strategic insights and KPI tracking</p>
        </div>
        <div class="rounded-xl p-6 border border-white/10 bg-white/5 hover:border-blue-400 transition-all duration-300 transform hover:-translate-y-2 hover:drop-shadow-[0_0_15px_rgba(59,130,246,0.3)]">
            <h4 class="font-semibold text-white mb-2">Machine Learning</h4>
            <p class="text-sm muted">AI models and automation</p>
        </div>
    </div>
</div>
    </div>
</section>

<section class="panel text-white rounded-2xl p-12 text-center max-w-5xl mx-auto mb-20">
    <h2 class="text-3xl font-bold mb-4">Ready to Transform Your Data?</h2>
    <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">
        Join businesses and analysts who trust DataMate for professional data analysis services.
    </p>
    <div class="flex justify-center gap-4">
        <a href="{{ route('register') }}" class="btn-primary">Start Your Project</a>
        <a href="{{ route('analysts.index') }}" class="btn-outline">Find an Analyst</a>
    </div>
</section>
@endsection