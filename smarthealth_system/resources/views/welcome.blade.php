<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartHealth System - Hospital Taiping</title>
    
    <style>
        /* Font Import */
        @import url('https://fonts.bunny.net/css?family=inter:400,600,700,800');

        /* Basic Reset */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Light gray background */
        }

        /* Main Landing Page Container */
        .landing-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .landing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            max-width: 1200px;
            width: 100%;
            align-items: center;
        }
        
        /* Left Column: Text Content */
        .landing-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .landing-text p {
            font-size: 1.1rem;
            color: #4b5563;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        /* Right Column: Slideshow */
        .landing-slideshow {
            position: relative;
            width: 100%;
            padding-bottom: 66.67%; /* 3:2 aspect ratio */
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .landing-slideshow img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            animation: fadeAnimation 15s infinite;
        }

        /* Staggered animation delays for the slideshow */
        .landing-slideshow img:nth-child(2) {
            animation-delay: 5s;
        }
        .landing-slideshow img:nth-child(3) {
            animation-delay: 10s;
        }

        /* CSS Animation for the fade effect */
        @keyframes fadeAnimation {
            0% { opacity: 0; }
            17% { opacity: 1; }
            33% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 0; }
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 0.8rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #111827;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #374151;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #34D399;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background-color: #10B981;
            transform: translateY(-2px);
        }
        
        /* Responsive adjustments for smaller screens */
        @media (max-width: 900px) {
            .landing-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .landing-slideshow {
                margin-top: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <div class="landing-grid">
            <div class="landing-text">
                <h1>Welcome to SmartHealth by Hospital Taiping</h1>
                <p>
                    Your personal health partner. Monitor your vital signs, receive instant AI-powered health insights, and seamlessly manage your appointments with our dedicated team of healthcare professionals.
                </p>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Patient / Doctor Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="margin-left: 10px;">Register New Account</a>
                </div>
            </div>

            <div class="landing-slideshow">
                <img src="{{ asset('images/hospital1.jpg') }}" alt="Hospital Taiping Main Building">
                <img src="{{ asset('images/hospital2.jpg') }}" alt="Modern Hospital Interior">
                <img src="{{ asset('images/hospital3.jpg') }}" alt="Friendly Medical Staff">
            </div>
        </div>
    </div>
</body>
</html>