@extends('layouts.app')

@section('title', 'About - ANAKT Blog')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <img src="{{ asset('images/ANAKT-logo.png') }}" alt="ANAKT" style="height: 80px;" class="mb-3">
                        <h1 class="display-4">About ANAKT Blog</h1>
                        <p class="lead text-muted">The Ultimate Destination for Alien Stage Fans</p>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded">
                                <h4><i class="fas fa-star text-warning me-2"></i>Our Mission</h4>
                                <p>To create a vibrant community where Alien Stage enthusiasts can share theories, discuss episodes, analyze characters, and connect with fellow fans who share the same passion for this incredible series.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded">
                                <h4><i class="fas fa-users text-primary me-2"></i>Our Community</h4>
                                <p>Join thousands of fans who dive deep into the complex world of Alien Stage, exploring the relationships between Till, Ivan, Sua, Mizi, and all the compelling characters.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4">What is Alien Stage?</h3>
                        <p>Alien Stage is a captivating series that explores themes of love, sacrifice, and survival in an otherworldly entertainment system. The story follows human contestants who must perform for alien audiences, with their lives hanging in the balance.</p>
                        
                        <p>The series is renowned for its:</p>
                        <ul>
                            <li><strong>Complex Character Relationships:</strong> From Till and Ivan's intricate dynamic to Sua and Mizi's touching bond</li>
                            <li><strong>Emotional Storytelling:</strong> Each episode delivers powerful emotional moments that resonate with viewers</li>
                            <li><strong>Stunning Visuals:</strong> Beautiful animation and character designs that bring the story to life</li>
                            <li><strong>Thought-Provoking Themes:</strong> Exploration of humanity, love, and survival in extreme circumstances</li>
                        </ul>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4">What You'll Find Here</h3>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-comments text-primary me-3 fa-2x"></i>
                                    <div>
                                        <h5>Episode Discussions</h5>
                                        <p class="mb-0">In-depth analysis and theories about each episode</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-heart text-danger me-3 fa-2x"></i>
                                    <div>
                                        <h5>Character Analysis</h5>
                                        <p class="mb-0">Deep dives into character motivations and relationships</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-lightbulb text-warning me-3 fa-2x"></i>
                                    <div>
                                        <h5>Fan Theories</h5>
                                        <p class="mb-0">Creative theories about what might happen next</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paint-brush text-success me-3 fa-2x"></i>
                                    <div>
                                        <h5>Fan Content</h5>
                                        <p class="mb-0">Art, stories, and creative content from the community</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded text-center">
                        <h4>Join Our Community Today!</h4>
                        <p>Whether you're a longtime fan or just discovered Alien Stage, you're welcome here. Share your thoughts, read amazing content, and connect with fellow fans.</p>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary me-2">
                                <i class="fas fa-user-plus me-2"></i>Join Now
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </a>
                        @else
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Write Your First Post
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection