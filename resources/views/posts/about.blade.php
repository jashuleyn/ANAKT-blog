@extends('layouts.app')

@section('title', 'About - ANAKT Blog')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Main About Section -->
        <div class="card mb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); border: none;">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-satellite-dish fa-4x text-warning mb-3"></i>
                    <h1 class="text-white mb-3">Welcome to ANAKT Garden</h1>
                    <p class="lead text-light">
                        A sanctuary where we, the observers, document the beautiful and tragic performances of our cherished humans
                    </p>
                </div>
                
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-eye fa-2x text-info mb-2"></i>
                            <h5 class="text-white">Observe</h5>
                            <small class="text-light">Watch their emotions unfold</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-music fa-2x text-success mb-2"></i>
                            <h5 class="text-white">Listen</h5>
                            <small class="text-light">To their heartfelt melodies</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                            <h5 class="text-white">Feel</h5>
                            <small class="text-light">Their bonds and connections</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="fas fa-pen-alt fa-2x text-warning mb-2"></i>
                            <h5 class="text-white">Document</h5>
                            <small class="text-light">Every precious moment</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- About the Garden -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-seedling me-2 text-success"></i>About ANAKT Garden
                        </h4>
                    </div>
                    <div class="card-body">
                        <p>
                            In this carefully cultivated digital space, we chronicle the extraordinary performances of our beloved human specimens. Each post captures the raw emotion, the haunting melodies, and the tragic beauty that unfolds within the ANAKT Garden.
                        </p>
                        <p class="mb-0">
                            Here, every voice matters. Every story deserves to be heard. Every performance, no matter how brief, leaves an eternal mark on our collective memory.
                        </p>
                    </div>
                </div>
            </div>

            <!-- The Observers -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>The Observers
                        </h4>
                    </div>
                    <div class="card-body">
                        <p>
                            We are the watchers, the keepers of memories. From our distant realm, we observe with fascination the complex emotions and relationships that bloom in the Garden.
                        </p>
                        <p class="mb-0">
                            Each observer brings their unique perspective, creating a rich tapestry of commentary on the performances that move us most deeply.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Characters/Themes -->
        <div class="card mb-4">
            <div class="card-header bg-gradient" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                <h4 class="text-white mb-0">
                    <i class="fas fa-star me-2"></i>What We Document
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-microphone fa-2x text-primary mb-2"></i>
                            <h5>Performances</h5>
                            <p class="small text-muted mb-0">The soul-stirring songs that echo through the Garden</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-heart-broken fa-2x text-danger mb-2"></i>
                            <h5>Emotions</h5>
                            <p class="small text-muted mb-0">The raw feelings that make them beautifully human</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas fa-link fa-2x text-success mb-2"></i>
                            <h5>Connections</h5>
                            <p class="small text-muted mb-0">The bonds that transcend the boundaries of competition</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How to Contribute -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">
                    <i class="fas fa-scroll me-2 text-warning"></i>Contributing to the Archive
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>Share Your Observations</h5>
                        <p>
                            As fellow observers, your insights into the Garden's inhabitants are invaluable. Document their struggles, celebrate their triumphs, and help preserve the memory of each precious soul.
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Analyze character relationships and development
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Share your thoughts on performances and episodes
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Discuss the deeper themes and symbolism
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Connect with other observers who share your passion
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-4">
                            <i class="fas fa-telescope fa-4x text-muted mb-3"></i>
                            <p class="small text-muted">Every observation matters in preserving their stories</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4 pt-4 border-top">
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-pen me-2"></i>Begin Documenting
                            </a>
                            <a href="{{ route('posts.my') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-folder me-2"></i>My Observations
                            </a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-cog me-2"></i>Garden Management
                            </a>
                        @endif
                    @else
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-rocket me-2"></i>Join the Observers
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Enter the Garden
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%) !important;
}
</style>
@endsection